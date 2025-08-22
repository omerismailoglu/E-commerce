<?php

namespace App\Http\Controllers\Api;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends ApiController
{
    public function index(Request $request)
    {
        $cart = $request->user()->load(['cart.items.product'])->cart;
        return $this->successResponse($cart);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $cart = $request->user()->cart;
        $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
        $item->quantity = ($item->exists ? $item->quantity : 0) + $validated['quantity'];
        $item->save();

        return $this->successResponse($cart->load('items.product'), 'Sepete eklendi', 201);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $request->user()->cart;
        $item = $cart->items()->where('product_id', $validated['product_id'])->first();
        if (!$item) {
            return $this->errorResponse('Ürün sepette bulunamadı', [], 404);
        }
        $item->quantity = $validated['quantity'];
        $item->save();

        return $this->successResponse($cart->load('items.product'), 'Sepet güncellendi');
    }

    public function remove(Request $request, int $productId)
    {
        $cart = $request->user()->cart;
        $deleted = $cart->items()->where('product_id', $productId)->delete();
        if (!$deleted) {
            return $this->errorResponse('Ürün sepette bulunamadı', [], 404);
        }
        return $this->successResponse($cart->load('items.product'), 'Ürün sepetten çıkarıldı');
    }

    public function clear(Request $request)
    {
        $cart = $request->user()->cart;
        $cart->items()->delete();
        return $this->successResponse($cart->fresh('items.product'), 'Sepet temizlendi');
    }
}


