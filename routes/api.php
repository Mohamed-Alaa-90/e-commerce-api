<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductImageController;
use Illuminate\Support\Facades\Route;


Route::prefix("v1")->group(function () {
    #auth    
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    #user
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

    });
    #categories
    Route::apiResource('categories', CategoryController::class);
    #products
    Route::apiResource('products', ProductController::class);
    #images
    Route::post('/products/{product}/images/', [ProductImageController::class, 'store']);
    Route::delete('/products/{product}/images/{image}', [ProductImageController::class, 'destroy']);
});