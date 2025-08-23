<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();

        return response()->json([
            'success' => true,
            'message' => 'Categories retrieved successfully',
            'data' => $categories
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

        // Debug: Request data'yı logla
        \Log::info('Category creation request:', $request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            \Log::error('Category validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category = Category::create($request->all());
            \Log::info('Category created successfully:', $category->toArray());
        } catch (\Exception $e) {
            \Log::error('Category creation failed:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    public function show($id)
    {
        $category = Category::with('products')->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Category retrieved successfully',
            'data' => $category
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

        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $category->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category
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

        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}
