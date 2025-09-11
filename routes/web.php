<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\APi\RoleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ChartController;
use App\Http\Controllers\Admin\OwnerController;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\UniversalController;
use App\Http\Controllers\UniversalAdminController;
use App\Http\Controllers\IsolatedGuardAuthController;
use App\Http\Controllers\MemberController;


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
Route::get('/run-migrate', function () {
    // Pastikan table sessions migration sudah dibuat
    if (!file_exists(database_path('migrations/' . date('Y_m_d') . '_000000_create_sessions_table.php'))) {
        \Artisan::call('session:table');
    }
    // Jalankan migrate dan tampilkan outputnya!
    \Artisan::call('migrate', ['--force' => true]);
    return '<pre>' . \Artisan::output() . '</pre>';
});

// Route::get('/run-seed', function () {
//     // Jalankan db:seed dan tampilkan outputnya!
//     \Artisan::call('db:seed', ['--force' => true]);
//     return '<pre>' . \Artisan::output() . '</pre>';
// });

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

// Owner Authentication (separate page, uses admin guard with owner role)
Route::prefix('owner')->group(function () {
    Route::get('/login', function () {
        return view('auth.owner-login-vue');
    })->name('owner.login');

    Route::post('/login', [IsolatedGuardAuthController::class, 'ownerLogin']);
    Route::post('/logout', [IsolatedGuardAuthController::class, 'ownerLogout'])->name('owner.logout');
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
    Route::get('/members', function () {
        return view('admin.members');
    })->name('members');
    Route::get('/roles', [UniversalAdminController::class, 'roles'])->name('roles');
    
    // Chart API routes
    Route::get('/dashboard/revenue-chart', [ChartController::class, 'revenueChart'])->name('dashboard.revenue-chart');
    Route::get('/dashboard/orders-chart', [ChartController::class, 'ordersChart'])->name('dashboard.orders-chart');
    Route::get('/dashboard/categories-chart', [ChartController::class, 'categoriesChart'])->name('dashboard.categories-chart');

    // Owner Dashboard
    Route::get('/owner', [OwnerController::class, 'index'])->name('owner');
    Route::get('/owner/summary', [OwnerController::class, 'summary'])->name('owner.summary');
});

// Kasir Routes (Kasir only)
Route::middleware(['auth:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/pos', [UniversalAdminController::class, 'pos'])->name('pos');
    Route::get('/payment', function () {
        return view('kasir.payment');
    })->name('payment');
    Route::get('/report', function () {
        return view('kasir.report');
    })->name('report');
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
    
    // Members API
    Route::get('/members/api', [MemberController::class, 'index']);
    Route::post('/members/api', [MemberController::class, 'store']);
    Route::get('/members/api/{member}', [MemberController::class, 'show']);
    Route::put('/members/api/{member}', [MemberController::class, 'update']);
    Route::delete('/members/api/{member}', [MemberController::class, 'destroy']);
    Route::get('/members/api/search', [MemberController::class, 'search']);

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

    // Member API Routes
    Route::get('/members/api', [MemberController::class, 'index']);
    Route::post('/members/api', [MemberController::class, 'store']);
    Route::get('/members/api/{member}', [MemberController::class, 'show']);
    Route::put('/members/api/{member}', [MemberController::class, 'update']);
    Route::delete('/members/api/{member}', [MemberController::class, 'destroy']);
    Route::get('/members/api/search', [MemberController::class, 'search']);
});