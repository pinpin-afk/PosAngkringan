<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function revenueChart()
    {
        $data = [];
        $startDate = Carbon::now()->subDays(6);
        
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $revenue = Order::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total');
            
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'revenue' => (int) $revenue
            ];
        }
        
        return response()->json($data);
    }
    
    public function ordersChart()
    {
        $data = [];
        $startDate = Carbon::now()->subDays(6);
        
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $orders = Order::whereDate('created_at', $date)->count();
            
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'orders' => $orders
            ];
        }
        
        return response()->json($data);
    }
    
    public function categoriesChart()
    {
        $categories = Category::withCount('products')->get();
        
        $data = $categories->map(function ($category) {
            return [
                'name' => $category->name,
                'count' => $category->products_count
            ];
        });
        
        return response()->json($data);
    }
}
