<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class OrderController extends Controller
{
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $orders = $user->orders()->with('items.product')->get();

        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully',
            'data' => $orders,
            'errors' => []
        ]);
    }

    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty',
                'data' => null,
                'errors' => []
            ], 400);
        }

        // Enhanced stock validation
        $stockErrors = [];
        foreach ($cart->items as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                $stockErrors[] = [
                    'product_id' => $item->product->id,
                    'product_name' => $item->product->name,
                    'requested_quantity' => $item->quantity,
                    'available_stock' => $item->product->stock_quantity,
                    'shortage' => $item->quantity - $item->product->stock_quantity
                ];
            }
        }

        if (!empty($stockErrors)) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock for some products',
                'data' => [
                    'stock_errors' => $stockErrors
                ],
                'errors' => []
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Calculate total
            $totalAmount = $cart->items->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'pending'
            ]);

            // Create order items and update stock
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);

                // Update stock with detailed tracking
                $product = $item->product;
                $oldStock = $product->stock_quantity;
                $newStock = $oldStock - $item->quantity;
                
                $product->update([
                    'stock_quantity' => $newStock,
                    'updated_at' => now()
                ]);

                // Log stock change
                \Log::info("Stock updated for product {$product->name}: {$oldStock} -> {$newStock} (Order #{$order->id})");
            }

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            // Send email notification
            $this->sendOrderConfirmationEmail($order);

            $order->load('items.product');

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order' => $order,
                    'items_count' => $order->items->count()
                ],
                'errors' => []
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'data' => null,
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    public function show($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $order = $user->orders()->with('items.product')->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'data' => null,
                'errors' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully',
            'data' => $order,
            'errors' => []
        ]);
    }

    /**
     * Update order status (Admin only)
     */
    public function updateStatus(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        
        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
                'data' => null,
                'errors' => []
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'data' => null,
                'errors' => []
            ], 404);
        }

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Send status update email
        $this->sendStatusUpdateEmail($order, $oldStatus);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'data' => [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $order->status
            ],
            'errors' => []
        ]);
    }

    /**
     * Send order confirmation email
     */
    private function sendOrderConfirmationEmail($order)
    {
        try {
            $user = $order->user;
            $items = $order->items;
            
            $subject = "Order Confirmation #{$order->id}";
            $message = "Dear {$user->name},\n\n";
            $message .= "Your order has been confirmed!\n\n";
            $message .= "Order Details:\n";
            $message .= "Order ID: #{$order->id}\n";
            $message .= "Total Amount: $" . number_format($order->total_amount, 2) . "\n";
            $message .= "Status: " . ucfirst($order->status) . "\n\n";
            $message .= "Items:\n";
            
            foreach ($items as $item) {
                $message .= "- {$item->product->name} x {$item->quantity} = $" . number_format($item->price * $item->quantity, 2) . "\n";
            }
            
            $message .= "\nThank you for your purchase!\n";
            $message .= "Best regards,\nE-Commerce Team";
            
            // Send email (you can replace this with Laravel Mail facade)
            mail($user->email, $subject, $message);
            
            \Log::info("Order confirmation email sent to {$user->email} for order #{$order->id}");
            
        } catch (\Exception $e) {
            \Log::error("Failed to send order confirmation email: " . $e->getMessage());
        }
    }

    /**
     * Send status update email
     */
    private function sendStatusUpdateEmail($order, $oldStatus)
    {
        try {
            $user = $order->user;
            
            $subject = "Order Status Update #{$order->id}";
            $message = "Dear {$user->name},\n\n";
            $message .= "Your order status has been updated!\n\n";
            $message .= "Order ID: #{$order->id}\n";
            $message .= "Previous Status: " . ucfirst($oldStatus) . "\n";
            $message .= "New Status: " . ucfirst($order->status) . "\n\n";
            $message .= "Thank you for your patience!\n";
            $message .= "Best regards,\nE-Commerce Team";
            
            mail($user->email, $subject, $message);
            
            \Log::info("Status update email sent to {$user->email} for order #{$order->id}: {$oldStatus} -> {$order->status}");
            
        } catch (\Exception $e) {
            \Log::error("Failed to send status update email: " . $e->getMessage());
        }
    }
}
