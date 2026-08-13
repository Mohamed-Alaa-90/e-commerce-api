<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $category = Category::latest()->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Categories fetched successfully',
            'data' => $category,
        ]);
    }

    public function show(Category $category)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Category fetched successfully',
            'data' => $category
        ]);
    }


    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Category Created Successfully',
            'data' => $category
        ]);
    }


    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Category Updated Successfully',
            'data' => $category
        ]);

    }


    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Catrgory Deleted Successfully'
        ]);
    }
}
