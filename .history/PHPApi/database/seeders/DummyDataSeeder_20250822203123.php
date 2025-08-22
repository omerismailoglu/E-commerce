<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // Kategoriler oluştur
        $categories = [
            [
                'name' => 'Elektronik',
                'description' => 'Telefon, tablet, bilgisayar ve diğer elektronik cihazlar',
                'products' => [
                    [
                        'name' => 'iPhone 15 Pro',
                        'description' => 'Apple iPhone 15 Pro 128GB Titanium, A17 Pro çip, 48MP kamera',
                        'price' => 899.99,
                        'stock_quantity' => 25
                    ],
                    [
                        'name' => 'Samsung Galaxy S24',
                        'description' => 'Samsung Galaxy S24 256GB Phantom Black, Snapdragon 8 Gen 3',
                        'price' => 799.99,
                        'stock_quantity' => 30
                    ],
                    [
                        'name' => 'MacBook Air M3',
                        'description' => 'Apple MacBook Air 13" M3 çip, 8GB RAM, 256GB SSD',
                        'price' => 1199.99,
                        'stock_quantity' => 15
                    ],
                    [
                        'name' => 'iPad Air 5',
                        'description' => 'Apple iPad Air 5 64GB Wi-Fi, M1 çip, 10.9" Liquid Retina',
                        'price' => 599.99,
                        'stock_quantity' => 20
                    ]
                ]
            ],
            [
                'name' => 'Giyim & Moda',
                'description' => 'Erkek, kadın ve çocuk giyim ürünleri',
                'products' => [
                    [
                        'name' => 'Nike Air Max 270',
                        'description' => 'Nike Air Max 270 erkek spor ayakkabı, siyah-beyaz',
                        'price' => 129.99,
                        'stock_quantity' => 50
                    ],
                    [
                        'name' => 'Levi\'s 501 Jeans',
                        'description' => 'Levi\'s 501 Original Fit erkek kot pantolon, mavi',
                        'price' => 89.99,
                        'stock_quantity' => 40
                    ],
                    [
                        'name' => 'Zara Blazer Ceket',
                        'description' => 'Zara kadın blazer ceket, siyah, slim fit',
                        'price' => 159.99,
                        'stock_quantity' => 35
                    ],
                    [
                        'name' => 'Adidas Tiro Track',
                        'description' => 'Adidas Tiro Track erkek eşofman takımı, gri',
                        'price' => 79.99,
                        'stock_quantity' => 45
                    ]
                ]
            ],
            [
                'name' => 'Ev & Yaşam',
                'description' => 'Ev dekorasyonu, mobilya ve yaşam ürünleri',
                'products' => [
                    [
                        'name' => 'IKEA MALM Yatak Odası',
                        'description' => 'IKEA MALM yatak odası takımı, beyaz, 160x200',
                        'price' => 299.99,
                        'stock_quantity' => 10
                    ],
                    [
                        'name' => 'Philips Hue Starter Kit',
                        'description' => 'Philips Hue akıllı LED ampul starter kit, 3 ampul + bridge',
                        'price' => 199.99,
                        'stock_quantity' => 25
                    ],
                    [
                        'name' => 'KitchenAid Stand Mixer',
                        'description' => 'KitchenAid Artisan stand mixer, 4.8L, kırmızı',
                        'price' => 399.99,
                        'stock_quantity' => 15
                    ],
                    [
                        'name' => 'Dyson V15 Detect',
                        'description' => 'Dyson V15 Detect Absolute Extra süpürge, kablosuz',
                        'price' => 699.99,
                        'stock_quantity' => 12
                    ]
                ]
            ],
            [
                'name' => 'Spor & Outdoor',
                'description' => 'Spor ekipmanları ve outdoor aktivite ürünleri',
                'products' => [
                    [
                        'name' => 'Fitbit Charge 6',
                        'description' => 'Fitbit Charge 6 fitness tracker, kalp atış hızı monitörü',
                        'price' => 159.99,
                        'stock_quantity' => 30
                    ],
                    [
                        'name' => 'Nike Basketball',
                        'description' => 'Nike Spalding NBA Official Game Basketball',
                        'price' => 49.99,
                        'stock_quantity' => 60
                    ],
                    [
                        'name' => 'GoPro Hero 11',
                        'description' => 'GoPro Hero 11 Black action kamera, 5.3K video',
                        'price' => 399.99,
                        'stock_quantity' => 20
                    ],
                    [
                        'name' => 'The North Face Jacket',
                        'description' => 'The North Face Nuptse down jacket, siyah, erkek',
                        'price' => 249.99,
                        'stock_quantity' => 25
                    ]
                ]
            ],
            [
                'name' => 'Kitap & Hobi',
                'description' => 'Kitaplar, müzik aletleri ve hobi malzemeleri',
                'products' => [
                    [
                        'name' => 'Kindle Paperwhite',
                        'description' => 'Amazon Kindle Paperwhite e-reader, 8GB, su geçirmez',
                        'price' => 139.99,
                        'stock_quantity' => 40
                    ],
                    [
                        'name' => 'Yamaha P-45 Piano',
                        'description' => 'Yamaha P-45 dijital piyano, 88 tuş, weighted keys',
                        'price' => 499.99,
                        'stock_quantity' => 8
                    ],
                    [
                        'name' => 'Lego Star Wars',
                        'description' => 'Lego Star Wars Millennium Falcon, 7541 parça',
                        'price' => 159.99,
                        'stock_quantity' => 15
                    ],
                    [
                        'name' => 'Canon EOS R7',
                        'description' => 'Canon EOS R7 mirrorless kamera, 33MP, 4K video',
                        'price' => 1499.99,
                        'stock_quantity' => 5
                    ]
                ]
            ]
        ];

        // Kategorileri ve ürünleri oluştur
        foreach ($categories as $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'description' => $categoryData['description']
            ]);

            foreach ($categoryData['products'] as $productData) {
                Product::create([
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'stock_quantity' => $productData['stock_quantity'],
                    'category_id' => $category->id
                ]);
            }
        }

        $this->command->info('Dummy data başarıyla oluşturuldu!');
        $this->command->info('Toplam ' . count($categories) . ' kategori ve ' . (count($categories) * 4) . ' ürün eklendi.');
    }
}
