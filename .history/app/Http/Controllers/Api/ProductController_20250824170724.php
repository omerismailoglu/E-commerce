<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Category;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

/**
 * Product Controller
 * 
 * Handles product CRUD operations with advanced filtering and search
 * Admin authentication required for create, update, delete operations
 */
class ProductController extends Controller
{
    /**
     * Get products with advanced filtering, search, and pagination
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Apply search filter (name, description, category name)
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

        // Apply category filters
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Apply multiple category filter
        if ($request->has('category_ids')) {
            $categoryIds = explode(',', $request->category_ids);
            $query->whereIn('category_id', $categoryIds);
        }

        // Apply price range filters
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply stock availability filter
        if ($request->has('in_stock')) {
            if ($request->in_stock === 'true') {
                $query->where('stock_quantity', '>', 0);
            } elseif ($request->in_stock === 'false') {
                $query->where('stock_quantity', '=', 0);
            }
        }

        // Apply stock quantity range filters
        if ($request->has('min_stock')) {
            $query->where('stock_quantity', '>=', $request->min_stock);
        }
        if ($request->has('max_stock')) {
            $query->where('stock_quantity', '<=', $request->max_stock);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['name', 'price', 'stock_quantity', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Apply pagination
        $perPage = min($request->get('limit', 20), 100); // Max 100 items per page
        $products = $query->paginate($perPage);

        // Build metadata for response
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

    /**
     * Create a new product (Admin only)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Check admin authentication
        $authResult = $this->checkAdminAuth();
        if ($authResult) {
            return $authResult;
        }

        // Validate product data
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

        // Create new product
        $product = Product::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product->load('category')
        ], 201);
    }

    /**
     * Get a specific product by ID
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Update a product (Admin only)
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Check admin authentication
        $authResult = $this->checkAdminAuth();
        if ($authResult) {
            return $authResult;
        }

        // Find product
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Validate update data
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

        // Update product
        $product->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product->load('category')
        ]);
    }

    /**
     * Delete a product (Admin only)
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Check admin authentication
        $authResult = $this->checkAdminAuth();
        if ($authResult) {
            return $authResult;
        }

        // Find and delete product
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

    /**
     * Check if authenticated user is admin
     * 
     * @return \Illuminate\Http\JsonResponse|null
     */
    private function checkAdminAuth()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user || $user->role !== 'admin') {
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

        return null; // Authentication successful
    }
}
