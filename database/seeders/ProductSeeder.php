<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Danh sách 12 sản phẩm mẫu công nghệ
        $sampleProducts = [
            ['name' => 'Laptop Dell XPS 13 Plus', 'price' => 45000000],
            ['name' => 'MacBook Pro 14 M3', 'price' => 52000000],
            ['name' => 'Chuột Logitech MX Master 3S', 'price' => 2500000],
            ['name' => 'Bàn phím cơ Keychron K2 Pro', 'price' => 2100000],
            ['name' => 'Màn hình LG UltraGear 27"', 'price' => 8500000],
            ['name' => 'Tai nghe Sony WH-1000XM5', 'price' => 7900000],
            ['name' => 'PC Gaming i9 14900K', 'price' => 85000000],
            ['name' => 'Ghế Công thái học Herman Miller', 'price' => 35000000],
            ['name' => 'Ổ cứng SSD Samsung 990 Pro 1TB', 'price' => 3200000],
            ['name' => 'RAM Corsair Vengeance 32GB', 'price' => 2800000],
            ['name' => 'Webcam Logitech Brio 4K', 'price' => 4100000],
            ['name' => 'Loa Bluetooth Marshall Stanmore III', 'price' => 9500000],
        ];

        foreach ($sampleProducts as $item) {
            Product::create([
                'name' => $item['name'],
                // Slug sẽ tự động tạo nhờ Model boot()
                'price' => $item['price'],
                'quantity' => rand(10, 100), // Random số lượng kho
                'sold' => rand(0, 50),       // Random đã bán
                'description' => "Mô tả chi tiết cho sản phẩm {$item['name']}. Sản phẩm chính hãng, bảo hành 12 tháng.",
                'status' => 'active',
                'image' => null, // Không cần ảnh
                'gallery' => null,
            ]);
        }
    }
}