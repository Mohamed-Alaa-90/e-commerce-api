<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductIndexRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request)
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
        ])->latest()
            ->with([
                'categories:id,name,slug',
                'images:id,product_id,image'
            ])
            ->paginate($per_page);
        return view('admin.products.index', compact('products'));

    }
}
