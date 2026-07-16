<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Bảng Đơn hàng (Lưu thông tin khách + tổng tiền)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã đơn (VD: ORD-001)
            
            // Thông tin khách hàng (Nhập tay)
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('shipping_address')->nullable(); // Có thể null nếu mua tại quầy
            $table->text('note')->nullable();
            
            // Tiền nong
            $table->decimal('subtotal', 15, 0); // Tổng tiền hàng
            $table->decimal('shipping_fee', 15, 0)->default(0); // Phí ship
            $table->decimal('total_amount', 15, 0); // Khách phải trả (sub + ship)
            
            // Phương thức & Trạng thái
            $table->string('payment_method')->default('cash'); // cash (tiền mặt), transfer (ck)
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid'); 
            $table->enum('status', ['pending', 'shipping', 'completed', 'cancelled'])->default('pending');
            
            $table->timestamps();
        });

        // 2. Bảng Chi tiết đơn (Lưu mua cái gì, giá bao nhiêu)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            
            // Sản phẩm (Set null nếu lỡ xóa SP gốc để giữ lịch sử)
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            
            $table->string('product_name'); // Lưu cứng tên SP tại thời điểm mua
            $table->string('product_image')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 15, 0); // Lưu cứng giá tại thời điểm mua
            $table->decimal('subtotal', 15, 0); // quantity * price
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};