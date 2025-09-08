<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\APi\RoleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\UniversalController;
use App\Http\Controllers\UniversalAdminController;
use App\Http\Controllers\IsolatedGuardAuthController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ============================================================================
// PUBLIC ROUTES
// ============================================================================

// Redirect root to kasir login
Route::get('/', function () {
    return redirect()->route('kasir.login');
});

// Universal login redirect
Route::get('/login', function () {
    return redirect()->route('kasir.login');
})->name('login');

// ============================================================================
// AUTHENTICATION ROUTES
// ============================================================================

// Admin Authentication
Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('auth.admin-login-vue');
    })->name('admin.login');
    
    Route::post('/login', [IsolatedGuardAuthController::class, 'adminLogin']);
    Route::post('/logout', [IsolatedGuardAuthController::class, 'adminLogout'])->name('admin.logout');
});

// Kasir Authentication
Route::prefix('kasir')->group(function () {
    Route::get('/login', function () {
        return view('auth.kasir-login-vue');
    })->name('kasir.login');
    
    Route::post('/login', [IsolatedGuardAuthController::class, 'kasirLogin']);
    Route::post('/logout', [IsolatedGuardAuthController::class, 'kasirLogout'])->name('kasir.logout');
});

// ============================================================================
// SESSION CHECK ROUTES
// ============================================================================

Route::get('/check-admin-session', [IsolatedGuardAuthController::class, 'checkAdminSession'])->name('check.admin.session');
Route::get('/check-kasir-session', [IsolatedGuardAuthController::class, 'checkKasirSession'])->name('check.kasir.session');

// ============================================================================
// PROTECTED ROUTES
// ============================================================================

// Admin Routes (Admin only)
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [UniversalAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard-data', [UniversalAdminController::class, 'getData'])->name('dashboard.data');
    Route::get('/products', [UniversalAdminController::class, 'products'])->name('products');
    Route::get('/categories', [UniversalAdminController::class, 'categories'])->name('categories');
    Route::get('/orders', [UniversalAdminController::class, 'orders'])->name('orders');
    Route::get('/users', [UniversalAdminController::class, 'users'])->name('users');
    Route::get('/roles', [UniversalAdminController::class, 'roles'])->name('roles');
});

// Kasir Routes (Kasir only)
Route::middleware(['auth:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/pos', [UniversalAdminController::class, 'pos'])->name('pos');
});

// Cross-Role Access Routes
Route::middleware(['auth'])->group(function () {
    // Admin accessing POS
    Route::get('/admin/pos', function () {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return view('admin.pos');
        } else {
            return redirect()->route('kasir.pos');
        }
    })->name('admin.pos');
    
    // Kasir accessing dashboard
    Route::get('/kasir/dashboard', function () {
        $user = auth()->user();
        if ($user->isKasir()) {
            return view('admin.dashboard');
        } else {
            return redirect()->route('admin.dashboard');
        }
    })->name('kasir.dashboard');
});

// Universal Routes (Role-based redirect)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UniversalController::class, 'dashboard'])->name('dashboard');
    Route::get('/pos', [UniversalController::class, 'pos'])->name('pos');
});

// ============================================================================
// API ROUTES FOR POS SYSTEM
// ============================================================================

Route::prefix('pos')->group(function () {
    // Products API
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    // Categories API
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    // Orders API
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::put('/orders/{order}', [OrderController::class, 'update']);
        // QRIS Flow
    Route::post('/orders/qris', [OrderController::class, 'createQris']);
    Route::get('/orders/{order}/status', [OrderController::class, 'checkStatus']);

    // Users API
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    // Roles API
    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::get('/roles/{role}', [RoleController::class, 'show']);
    Route::put('/roles/{role}', [RoleController::class, 'update']);
    Route::delete('/roles/{role}', [RoleController::class, 'destroy']);
    Route::get('/role-stats', [RoleController::class, 'getStats'])->name('role.stats');
});