<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductImageRequest;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function store(StoreProductImageRequest $request, Product $product)
    {
        $uploudedImage = [];
        try {
            DB::transaction(function () use (&$uploudedImage, $request, $product) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $uploudedImage[] = $path;
                    $product->images()->create([
                        'image' => $path,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            foreach ($uploudedImage as $path) {
                Storage::disk('public')->delete($path);
            }
            throw $e;
        }
        $product->loadMissing([
            'images:id,product_id,image',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product Images Uplouded Successfully',
            'data' => $product->images
        ], 201);
    }
    public function destroy(Product $product, int $image)
    {
        $productImage = $product->images()->findOrFail($image);
        $path = $productImage->image;


        DB::transaction(function () use ($productImage) {
            $productImage->delete();
        });
        $deleted = Storage::disk('public')->delete($path);
        if (!$deleted) {
            Log::warning('Failed to delete product image from storage', [
                'image' => $path,
                'product_image_id' => $productImage->id,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product Image Deleted Successfully'
        ]);

    }
}
