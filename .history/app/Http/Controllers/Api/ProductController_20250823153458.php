<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Category;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Advanced search by name and description
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'ilike', '%' . $searchTerm . '%')
                  ->orWhere('description', 'ilike', '%' . $searchTerm . '%')
                  ->orWhereHas('category', function($categoryQuery) use ($searchTerm) {
                      $categoryQuery->where('name', 'ilike', '%' . $searchTerm . '%');
                  });
            });
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by multiple categories
        if ($request->has('category_ids')) {
            $categoryIds = explode(',', $request->category_ids);
            $query->whereIn('category_id', $categoryIds);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by stock availability
        if ($request->has('in_stock')) {
            if ($request->in_stock === 'true') {
                $query->where('stock_quantity', '>', 0);
            } elseif ($request->in_stock === 'false') {
                $query->where('stock_quantity', '=', 0);
            }
        }

        // Filter by stock quantity range
        if ($request->has('min_stock')) {
            $query->where('stock_quantity', '>=', $request->min_stock);
        }
        if ($request->has('max_stock')) {
            $query->where('stock_quantity', '<=', $request->max_stock);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['name', 'price', 'stock_quantity', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination with metadata
        $perPage = min($request->get('limit', 20), 100); // Max 100 items per page
        $products = $query->paginate($perPage);

        // Add additional metadata
        $metadata = [
            'total_products' => $products->total(),
            'current_page' => $products->currentPage(),
            'per_page' => $products->perPage(),
            'last_page' => $products->lastPage(),
            'has_more_pages' => $products->hasMorePages(),
            'filters_applied' => [
                'search' => $request->search ?? null,
                'category_id' => $request->category_id ?? null,
                'min_price' => $request->min_price ?? null,
                'max_price' => $request->max_price ?? null,
                'in_stock' => $request->in_stock ?? null,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ],
            'metadata' => $metadata,
            'errors' => []
        ]);
    }

    public function store(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.',
                    'data' => null,
                    'errors' => []
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
                'data' => null,
                'errors' => []
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product->load('category')
        ], 201);
    }

    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product retrieved successfully',
            'data' => $product
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.',
                    'data' => null,
                    'errors' => []
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
                'data' => null,
                'errors' => []
            ], 401);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|min:3|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $product->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product->load('category')
        ]);
    }

    public function destroy($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.',
                    'data' => null,
                    'errors' => []
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
                'data' => null,
                'errors' => []
            ], 401);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
}
