<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function getData()
    {
        // Statistik penjualan hari ini
        $todayOrders = Order::whereDate('created_at', today())->get();
        $todayRevenue = $todayOrders->sum('total');
        $todayOrdersCount = $todayOrders->count();

        //total produk dan kategori
        $totalProducts = Product::all()->count();
        $totalCategories = Category::all()->count();

        //total pesanan
        $totalOrders = Order::where('status', 'completed')->count();

        //total semua pendapatan
        $totalRevenue = Order::where('status', 'completed')->sum('total');

        // Statistik penjualan bulan ini
        $monthOrders = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();
        $monthRevenue = $monthOrders->sum('total');
        $monthOrdersCount = $monthOrders->count();

        // Produk terlaris
        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        // Penjualan per hari (7 hari terakhir)
        $salesChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sales = Order::whereDate('created_at', $date)->sum('total');
            $salesChart[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('D'),
                'sales' => $sales
            ];
        }

        return response()->json([
            'todayRevenue' => $todayRevenue,
            'todayOrdersCount' => $todayOrdersCount,
            'monthRevenue' => $monthRevenue,
            'monthOrdersCount' => $monthOrdersCount,
            'topProducts' => $topProducts,
            'chartData' => $salesChart,
            'totalProducts' => $totalProducts,
            'totalCategories' => $totalCategories,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue
        ]);
    }
}