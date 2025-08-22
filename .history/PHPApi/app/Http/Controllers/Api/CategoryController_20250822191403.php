<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends ApiController
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        Log::info('Categories listed', ['count' => $categories->count()]);
        return $this->successResponse($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'description' => ['nullable', 'string'],
        ]);
        
        $category = Category::create($validated);
        
        Log::info('Category created', [
            'category_id' => $category->id,
            'name' => $category->name,
            'admin_user_id' => $request->user()->id
        ]);
        
        return $this->successResponse($category, 'Kategori oluşturuldu', 201);
    }

    // put
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'min:2'],
            'description' => ['nullable', 'string'],
        ]);
        
        $oldName = $category->name;
        $category->update($validated);
        
        Log::info('Category updated', [
            'category_id' => $category->id,
            'old_name' => $oldName,
            'new_name' => $category->name,
            'admin_user_id' => $request->user()->id
        ]);
        
        return $this->successResponse($category, 'Kategori güncellendi');
    }

    // delete
    public function destroy(Category $category)
    {
        $categoryName = $category->name;
        $category->delete();
        
        Log::info('Category deleted', [
            'category_id' => $category->id,
            'category_name' => $categoryName,
            'admin_user_id' => $request->user()->id
        ]);
        
        return $this->successResponse(null, 'Kategori silindi');
    }
}


