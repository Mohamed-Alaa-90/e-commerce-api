<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        $products = Product::query()->select([
            'id',
            'name',
            'slug',
            'description',
            'price',
            'stock',
            'is_active',
            'created_at'
        ])->with(['categories:id,name,slug', 'images:id,product_id,image'])
            ->latest()
            ->paginate($per_page);

        return response()->json([
            'status' => 'success',
            'message' => 'Products fetched successfully',
            'date' => $products
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        $product = DB::transaction(function () use ($validated) {
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'is_active' => $validated['is_active'] ?? true,
            ]);
            $product->categories()->sync($validated['category_ids']);

            return $product;
        });

        $product->loadMissing([
            'categories:id,name,slug',
            'images:id,product_id,image'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product Created Successfully',
            'data' => $product
        ], 201);
    }


    public function show(Product $product)
    {
        $product->loadMissing([
            'categories:id:name:slug',
            'images:id,product_id,image'
        ]);

        return response()->json([
            'success',
            'message' => 'Product fetch Successfully',
            'product' => $product
        ]);
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        $product = DB::transaction(
            function () use ($validated, $product) {
                $product->update([
                    'name' => $validated['name'] ?? $product->name,
                    'slug' => $validated['slug'] ?? $product->slug,
                    'description' => array_key_exists('description', $validated) ? $validated['description'] : $product->description,
                    'price' => $validated['price'] ?? $product->price,
                    'stock' => $validated['stock'] ?? $product->stock,
                    'is_active' => $validated['is_active'] ?? $product->is_active
                ]);
                if (array_key_exists('categories_ids', $validated)) {
                    $product->categories()->sync($validated['categories_ids']);
                }

                return $product;
            }
        );
        $product->loadMissing([
            'categories:id,name,slug',
            'images:id,product_id,image'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product Updated Successfully',
            'data' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product Deleted Successfully'
        ]);
    }
}
