<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index()
    {
        return view('admin.owner');
    }

    public function summary()
    {
        $today = Carbon::today();
        $start7 = Carbon::now()->subDays(6)->startOfDay();

        $totalRevenue = Order::where('status', 'completed')->sum('total');
        $todayRevenue = Order::where('status', 'completed')->whereDate('created_at', $today)->sum('total');
        $ordersCount = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $avgOrderValue = $completedOrders > 0 ? round($totalRevenue / $completedOrders, 2) : 0;
        $totalProducts = Product::count();
        $totalCategories = Category::count();

        $lowStock = Product::where('stock', '<=', 5)->orderBy('stock')->take(5)->get(['id', 'name', 'stock']);

        // Revenue last 7 days
        $revenue7 = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $start7->copy()->addDays($i);
            $revenue = Order::where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total');
            $revenue7[] = [
                'date' => $date->format('Y-m-d'),
                'revenue' => (float) $revenue,
            ];
        }

        // Top products by quantity in last 30 days
        $start30 = Carbon::now()->subDays(30);
        $topProducts = OrderItem::selectRaw('product_id, SUM(quantity) as qty')
            ->whereHas('order', function ($q) use ($start30) {
                $q->where('status', 'completed')->where('created_at', '>=', $start30);
            })
            ->groupBy('product_id')
            ->orderByDesc('qty')
            ->take(5)
            ->get()
            ->map(function ($row) {
                $product = Product::find($row->product_id);
                return [
                    'name' => $product?->name ?? 'Produk',
                    'qty' => (int) $row->qty,
                ];
            });

        return response()->json([
            'cards' => [
                'totalRevenue' => (float) $totalRevenue,
                'todayRevenue' => (float) $todayRevenue,
                'orders' => (int) $ordersCount,
                'avgOrderValue' => (float) $avgOrderValue,
                'totalProducts' => (int) $totalProducts,
                'totalCategories' => (int) $totalCategories,
            ],
            'lowStock' => $lowStock,
            'revenue7' => $revenue7,
            'topProducts' => $topProducts,
        ]);
    }
}


