<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Simple API Routes for POS System
Route::get('/pos/products', function () {
    try {
        $products = \App\Models\Product::with('category')
            ->where('is_active', true)
            ->get();
        return response()->json($products);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/pos/categories', function () {
    try {
        $categories = \App\Models\Category::where('is_active', true)->get();
        return response()->json($categories);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// API Routes for POS System (use web middleware to enable session access)
Route::prefix('pos')->middleware('web')->group(function () {
    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::put('/orders/{order}', [OrderController::class, 'update']);

    Route::get('/orders/{order}/status', [OrderController::class, 'checkStatus']);
    // Generic Midtrans charge
    Route::post('/orders/charge', [OrderController::class, 'charge']);
});

// (Removed) Midtrans Webhook

// Xendit Webhook (no auth)
Route::post('/xendit/webhook', \App\Http\Controllers\XenditWebhookController::class);
