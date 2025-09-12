<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentWebhookController;
use App\Http\Controllers\Api\MidtransController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Api\UserController;

// ============================================================================
// PUBLIC API ROUTES (No Authentication Required)
// ============================================================================

// Public product and category data for POS
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

// Webhooks (no authentication required)
Route::post('/midtrans/webhook', [MidtransController::class, 'webhook']);
Route::post('/pos/midtrans/webhook', [MidtransController::class, 'webhook']);
Route::post('/xendit/webhook', \App\Http\Controllers\XenditWebhookController::class);
Route::post('/pos/payments/telegram-webhook/{secret}', [PaymentWebhookController::class, 'telegram']);

// Offline sync endpoints (no authentication required)
Route::prefix('sync')->group(function () {
    Route::get('/master', [SyncController::class, 'getMasterUpdates']);
    Route::post('/orders', [SyncController::class, 'pushOfflineOrders']);
});

// ============================================================================
// KASIR API ROUTES (Kasir Authentication Required)
// ============================================================================

Route::prefix('kasir')->middleware(['web', 'auth:kasir'])->group(function () {
    // Products Management (Kasir can view only)
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    // Categories Management (Kasir can view only)
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);

    // Orders Management
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/report', [OrderController::class, 'report']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::put('/orders/{order}', [OrderController::class, 'update']);
    Route::post('/orders/{order}/verify-transfer', [OrderController::class, 'verifyTransfer']);
    Route::get('/orders/{order}/status', [OrderController::class, 'checkStatus']);
    Route::post('/orders/charge', [OrderController::class, 'charge']);
    
    // Draft order management
    Route::get('/orders/drafts', [OrderController::class, 'getDrafts']);
    Route::delete('/orders/drafts/{id}', [OrderController::class, 'deleteDraft']);
    Route::put('/orders/drafts/{id}', [OrderController::class, 'updateDraft']);
    
    // Order cancellation
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelOrder']);

    // Payment processing
    Route::post('/midtrans/snap-token', [MidtransController::class, 'createSnapToken']);
    
    // Member Management (Kasir can view and manage members)
    Route::get('/members', [MemberController::class, 'index']);
    Route::post('/members', [MemberController::class, 'store']);
    Route::get('/members/{member}', [MemberController::class, 'show']);
    Route::put('/members/{member}', [MemberController::class, 'update']);
    Route::delete('/members/{member}', [MemberController::class, 'destroy']);
    Route::get('/members/search', [MemberController::class, 'search']);
});

// ============================================================================
// ADMIN API ROUTES (Admin Authentication Required)
// ============================================================================

Route::prefix('admin')->middleware(['web', 'auth:admin'])->group(function () {
    // Products Management (Full CRUD)
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    // Categories Management (Full CRUD)
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    // Orders Management (Admin can view all orders)
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/report', [OrderController::class, 'report']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::put('/orders/{order}', [OrderController::class, 'update']);
    Route::post('/orders/{order}/verify-transfer', [OrderController::class, 'verifyTransfer']);

    // Member Management
    Route::get('/members', [MemberController::class, 'index']);
    Route::post('/members', [MemberController::class, 'store']);
    Route::get('/members/{member}', [MemberController::class, 'show']);
    Route::put('/members/{member}', [MemberController::class, 'update']);
    Route::delete('/members/{member}', [MemberController::class, 'destroy']);
    Route::get('/members/search', [MemberController::class, 'search']);

    // Users Management (Admin can view and manage users)
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});

// ============================================================================
// SANCTUM API ROUTES (For API Token Authentication)
// ============================================================================

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
