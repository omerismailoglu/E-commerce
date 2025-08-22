<?php

namespace App\Http\Controllers\Api;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Cart; // Added this import for Cart model

class CartController extends ApiController
{
    // get
    public function index(Request $request)
    {
        $cart = $request->user()->load(['cart.items.product'])->cart;
        Log::info('Cart viewed', [
            'user_id' => $request->user()->id,
            'items_count' => $cart->items->count()
        ]);
        return $this->successResponse($cart);
    }

    // post
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        
        // Ensure cart exists
        $cart = $request->user()->cart;
        if (!$cart) {
            $cart = Cart::create(['user_id' => $request->user()->id]);
        }
        
        $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
        $oldQuantity = $item->exists ? $item->quantity : 0;
        $item->quantity = $oldQuantity + $validated['quantity'];
        $item->save();

        Log::info('Item added to cart', [
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'old_quantity' => $oldQuantity,
            'new_quantity' => $item->quantity
        ]);

        return $this->successResponse($cart->load('items.product'), 'Sepete eklendi', 201);
    }

    // put
    public function update(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $request->user()->cart;
        if (!$cart) {
            return $this->errorResponse('Sepet bulunamadı', [], 404);
        }
        
        $item = $cart->items()->where('product_id', $validated['product_id'])->first();
        if (!$item) {
            Log::warning('Cart update failed: Item not found', [
                'user_id' => $request->user()->id,
                'product_id' => $validated['product_id']
            ]);
            return $this->errorResponse('Ürün sepette bulunamadı', [], 404);
        }
        
        $oldQuantity = $item->quantity;
        $item->quantity = $validated['quantity'];
        $item->save();

        Log::info('Cart item updated', [
            'user_id' => $request->user()->id,
            'product_id' => $validated['product_id'],
            'old_quantity' => $oldQuantity,
            'new_quantity' => $validated['quantity']
        ]);

        return $this->successResponse($cart->load('items.product'), 'Sepet güncellendi');
    }

    // delete
    public function remove(Request $request, int $productId)
    {
        $cart = $request->user()->cart;
        if (!$cart) {
            return $this->errorResponse('Sepet bulunamadı', [], 404);
        }
        
        $item = $cart->items()->where('product_id', $productId)->first();
        
        if (!$item) {
            Log::warning('Cart item removal failed: Item not found', [
                'user_id' => $request->user()->id,
                'product_id' => $productId
            ]);
            return $this->errorResponse('Ürün sepette bulunamadı', [], 404);
        }
        
        $deleted = $cart->items()->where('product_id', $productId)->delete();
        
        Log::info('Item removed from cart', [
            'user_id' => $request->user()->id,
            'product_id' => $productId,
            'quantity_removed' => $item->quantity
        ]);
        
        return $this->successResponse($cart->load('items.product'), 'Ürün sepetten çıkarıldı');
    }

    // delete all
    public function clear(Request $request)
    {
        $cart = $request->user()->cart;
        if (!$cart) {
            return $this->errorResponse('Sepet bulunamadı', [], 404);
        }
        
        $itemsCount = $cart->items->count();
        $cart->items()->delete();
        
        Log::info('Cart cleared', [
            'user_id' => $request->user()->id,
            'items_removed' => $itemsCount
        ]);
        
        return $this->successResponse($cart->fresh('items.product'), 'Sepet temizlendi');
    }
}


