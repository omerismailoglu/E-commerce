<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    public function index(Request $request)
    {
        $query = Product::query()->with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $driver = config('database.default');
            $connection = config("database.connections.$driver.driver");
            $likeOperator = $connection === 'pgsql' ? 'ILIKE' : 'LIKE';
            $query->where('name', $likeOperator, "%$search%");
        }

        $limit = (int) ($request->input('limit', 20));
        $products = $query->paginate($limit);
        return $this->successResponse($products);
    }

    public function show(Product $product)
    {
        $product->load('category');
        return $this->successResponse($product);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'gt:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);
        $product = Product::create($validated);
        return $this->successResponse($product, 'Ürün oluşturuldu', 201);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'min:3'],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'gt:0'],
            'stock_quantity' => ['sometimes', 'integer', 'min:0'],
            'category_id' => ['sometimes', 'exists:categories,id'],
        ]);
        $product->update($validated);
        return $this->successResponse($product, 'Ürün güncellendi');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return $this->successResponse(null, 'Ürün silindi');
    }
}


