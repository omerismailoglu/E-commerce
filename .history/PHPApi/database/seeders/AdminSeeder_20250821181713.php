<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        Cart::firstOrCreate(['user_id' => $admin->id]);

        $cat = Category::firstOrCreate(['name' => 'Genel'], ['description' => 'Genel kategori']);
        Product::firstOrCreate(
            ['name' => 'Örnek Ürün'],
            [
                'description' => 'Örnek açıklama',
                'price' => 99.99,
                'stock_quantity' => 100,
                'category_id' => $cat->id,
            ]
        );
    }
}


