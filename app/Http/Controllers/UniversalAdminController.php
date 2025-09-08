<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Kasir\PosController;

class UniversalAdminController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return app(DashboardController::class)->index();
        } else {
            return view('admin.dashboard');
        }
    }
    
    public function getData()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return app(DashboardController::class)->getData();
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }
    
    public function products()
    {
        return view('admin.products');
    }
    
    public function categories()
    {
        return view('admin.categories');
    }
    
    public function orders()
    {
        return view('admin.orders');
    }

    public function users()
    {
        return view('admin.users');
    }
    
    public function roles()
    {
        return view('admin.roles');
    }
    
    public function pos()
    {
        $user = auth()->user();
        
        if ($user->isKasir()) {
            return app(PosController::class)->index();
        } else {
            return view('kasir.pos');
        }
    }
}
