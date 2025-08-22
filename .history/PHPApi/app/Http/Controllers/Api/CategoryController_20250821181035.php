<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function index()
    {
        return $this->successResponse(Category::orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ]);
        $category = Category::create($validated);
        return $this->successResponse($category, 'Kategori oluşturuldu', 201);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string'],
            'description' => ['nullable', 'string'],
        ]);
        $category->update($validated);
        return $this->successResponse($category, 'Kategori güncellendi');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return $this->successResponse(null, 'Kategori silindi');
    }
}


