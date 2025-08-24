<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

/**
 * Cart Controller
 * 
 * Handles shopping cart operations for authenticated users
 * Includes stock validation and cart management
 */
class CartController extends Controller
{
    /**
     * Get user's shopping cart with total calculation
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Authenticate user
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        // Get or create user's cart
        $cart = $user->cart;
        if (!$cart) {
            $cart = Cart::create(['user_id' => $user->id]);
        }

        $cart->load('items.product');

        // Calculate total
        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return response()->json([
            'success' => true,
            'message' => 'Cart retrieved successfully',
            'data' => [
                'cart' => $cart,
                'total' => $total
            ]
        ]);
    }

    /**
     * Add product to cart with stock validation
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(Request $request)
    {
        // Authenticate user
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get or create user's cart
        $cart = $user->cart;
        if (!$cart) {
            $cart = Cart::create(['user_id' => $user->id]);
        }

        // Check product stock
        $product = Product::find($request->product_id);
        if ($product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock'
            ], 400);
        }

        // Check if product already exists in cart
        $existingItem = $cart->items()->where('product_id', $request->product_id)->first();

        if ($existingItem) {
            // Update existing item quantity
            $existingItem->update([
                'quantity' => $existingItem->quantity + $request->quantity
            ]);
        } else {
            // Create new cart item
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        $cart->load('items.product');

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'data' => $cart
        ]);
    }

    /**
     * Update product quantity in cart
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCart(Request $request)
    {
        // Authenticate user
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get user's cart
        $cart = $user->cart;
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        // Check product stock
        $product = Product::find($request->product_id);
        if ($product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock'
            ], 400);
        }

        // Find and update cart item
        $cartItem = $cart->items()->where('product_id', $request->product_id)->first();
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart'
            ], 404);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        $cart->load('items.product');

        // Calculate updated total
        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'data' => [
                'cart' => $cart,
                'total' => $total
            ]
        ]);
    }

    /**
     * Remove product from cart
     * 
     * @param int $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromCart($productId)
    {
        // Authenticate user
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        // Get user's cart
        $cart = $user->cart;
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        // Find and remove cart item
        $cartItem = $cart->items()->where('product_id', $productId)->first();
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart'
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart successfully'
        ]);
    }

    /**
     * Clear all items from cart
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearCart()
    {
        // Authenticate user
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        // Get user's cart
        $cart = $user->cart;
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        // Clear all cart items
        $cart->items()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }

    /**
     * Authenticate user from JWT token
     * 
     * @return \App\Models\User|null
     */
    private function authenticateUser()
    {
        try {
            return JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return null;
        }
    }
}
