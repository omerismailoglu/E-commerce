<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends ApiController
{
    public function store(Request $request)
    {
        $user = $request->user();
        $cart = $user->load('cart.items.product')->cart;
        if (!$cart || $cart->items->isEmpty()) {
            return $this->errorResponse('Sepet boş', [], 400);
        }

        return DB::transaction(function () use ($cart, $user) {
            $total = 0;
            foreach ($cart->items as $item) {
                if ($item->quantity > $item->product->stock_quantity) {
                    return $this->errorResponse('Yetersiz stok: ' . $item->product->name, [], 422);
                }
                $total += $item->quantity * $item->product->price;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'status' => 'confirmed',
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
                $item->product->decrement('stock_quantity', $item->quantity);
            }

            $cart->items()->delete();

            return $this->successResponse($order->load('items.product'), 'Sipariş oluşturuldu', 201);
        });
    }

    public function index(Request $request)
    {
        $orders = $request->user()->load(['orders.items.product'])->orders()->latest()->paginate(20);
        return $this->successResponse($orders);
    }

    public function show(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            return $this->errorResponse('Yetkisiz erişim', [], 403);
        }
        return $this->successResponse($order->load('items.product'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'string'],
        ]);
        $order->update(['status' => $validated['status']]);
        return $this->successResponse($order, 'Sipariş durumu güncellendi');
    }
}


