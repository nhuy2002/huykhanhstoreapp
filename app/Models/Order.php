<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'customer_name',
        'customer_phone',
        'shipping_address',
        'note',
        'subtotal',
        'shipping_fee',
        'total_amount',
        'payment_method',
        'payment_status',
        'status',
        'payment_proof'
    ];

    // Quan hệ: 1 Đơn hàng có nhiều Sản phẩm con
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    // Tự động tạo mã đơn hàng (VD: HKC+2025+12345678)
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            // Tạo mã theo định dạng HKC+2025+XXXXXXXX và kiểm tra trùng lặp
            do {
                $code = 'HKC2025' . mt_rand(10000000, 99999999);
            } while (static::where('code', $code)->exists());

            $order->code = $code;
        });
    }
}
