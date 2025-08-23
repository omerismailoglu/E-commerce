<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin kullanıcısı oluştur
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Normal kullanıcı oluştur
        User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => Hash::make('user123'),
            'role' => 'user'
        ]);

        // Kategoriler oluştur
        $categories = [
            [
                'name' => 'Elektronik',
                'description' => 'Elektronik ürünler'
            ],
            [
                'name' => 'Giyim',
                'description' => 'Giyim ürünleri'
            ],
            [
                'name' => 'Kitap',
                'description' => 'Kitap ve yayınlar'
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);

            // Her kategori için 5 ürün oluştur
            for ($i = 1; $i <= 5; $i++) {
                Product::create([
                    'name' => "Ürün {$i} - {$categoryData['name']}",
                    'description' => "Bu {$categoryData['name']} kategorisindeki {$i}. üründür.",
                    'price' => rand(10, 1000),
                    'stock_quantity' => rand(1, 100),
                    'category_id' => $category->id
                ]);
            }
        }
    }
}
