<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên sản phẩm
            $table->string('slug')->unique();
            $table->decimal('price', 15, 0); // Giá (Lưu số lớn, 0 số lẻ cho VNĐ)
            $table->integer('quantity')->default(0); // Số lượng kho
            $table->integer('sold')->default(0); // Đã bán
            $table->string('image')->nullable(); // Ảnh đại diện
            $table->json('gallery')->nullable(); // Ảnh chi tiết (JSON Array)
            $table->text('description')->nullable(); // Mô tả
            $table->enum('status', ['active', 'inactive'])->default('active'); // Trạng thái
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};