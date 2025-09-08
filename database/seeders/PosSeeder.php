<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PosSeeder extends Seeder
{
    public function run()
    {
        // Create Users
        User::create([
            'name' => 'Admin',
            'email' => 'admin@pos.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@pos.com',
            'password' => Hash::make('password'),
            'role' => 'kasir'
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Makanan', 'description' => 'Berbagai jenis makanan'],
            ['name' => 'Minuman', 'description' => 'Berbagai jenis minuman'],
            ['name' => 'Snack', 'description' => 'Camilan ringan'],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create Products
        $products = [
            // Makanan
            ['name' => 'Nasi Goreng', 'price' => 15000, 'stock' => 50, 'category_id' => 1],
            ['name' => 'Mie Ayam', 'price' => 12000, 'stock' => 30, 'category_id' => 1],
            ['name' => 'Gado-gado', 'price' => 10000, 'stock' => 25, 'category_id' => 1],
            ['name' => 'Sate Ayam', 'price' => 20000, 'stock' => 40, 'category_id' => 1],
            ['name' => 'Bakso', 'price' => 8000, 'stock' => 35, 'category_id' => 1],
            
            // Minuman
            ['name' => 'Es Teh', 'price' => 3000, 'stock' => 100, 'category_id' => 2],
            ['name' => 'Es Jeruk', 'price' => 5000, 'stock' => 80, 'category_id' => 2],
            ['name' => 'Kopi Hitam', 'price' => 4000, 'stock' => 60, 'category_id' => 2],
            ['name' => 'Jus Alpukat', 'price' => 8000, 'stock' => 20, 'category_id' => 2],
            ['name' => 'Air Mineral', 'price' => 2000, 'stock' => 200, 'category_id' => 2],
            
            // Snack
            ['name' => 'Kerupuk', 'price' => 2000, 'stock' => 50, 'category_id' => 3],
            ['name' => 'Kacang', 'price' => 3000, 'stock' => 40, 'category_id' => 3],
            ['name' => 'Pisang Goreng', 'price' => 5000, 'stock' => 30, 'category_id' => 3],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
