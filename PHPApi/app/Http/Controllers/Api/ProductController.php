<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends ApiController
{
    // get all
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
        
        Log::info('Products listed', [
            'filters' => $request->only(['category_id', 'min_price', 'max_price', 'search', 'limit']),
            'total' => $products->total(),
            'page' => $products->currentPage()
        ]);
        
        return $this->successResponse($products);
    }

    // get by id
    public function show(Product $product)
    {
        $product->load('category');
        Log::info('Product viewed', ['product_id' => $product->id, 'name' => $product->name]);
        return $this->successResponse($product);
    }

    // post
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
        
        Log::info('Product created', [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'admin_user_id' => $request->user()->id
        ]);
        
        return $this->successResponse($product, 'Ürün oluşturuldu', 201);
    }

    // put
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'min:3'],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'gt:0'],
            'stock_quantity' => ['sometimes', 'integer', 'min:0'],
            'category_id' => ['sometimes', 'exists:categories,id'],
        ]);
        
        $oldData = $product->only(['name', 'price', 'stock_quantity']);
        $product->update($validated);
        
        Log::info('Product updated', [
            'product_id' => $product->id,
            'old_data' => $oldData,
            'new_data' => $product->only(['name', 'price', 'stock_quantity']),
            'admin_user_id' => $request->user()->id
        ]);
        
        return $this->successResponse($product, 'Ürün güncellendi');
    }

    // delete
    public function destroy(Product $product)
    {
        $productName = $product->name;
        $product->delete();
        
        Log::info('Product deleted', [
            'product_id' => $product->id,
            'product_name' => $productName,
            'admin_user_id' => $request->user()->id
        ]);
        
        return $this->successResponse(null, 'Ürün silindi');
    }
}


