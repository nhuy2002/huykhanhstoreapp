<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{

    public function index()
    {
        $data = [];

        // [SEC-TASK-003] Chỉ Admin mới xem được dữ liệu nhạy cảm
        if (auth()->user()->role === 'admin') {
            // 1. Tổng doanh thu (Chỉ tính các đơn đã hoàn thành)
            $data['totalRevenue'] = Order::where('status', 'completed')->sum('total_amount');

            // 2. Tổng số đơn hàng
            $data['totalOrders'] = Order::count();

            // 3. Đơn hàng trong ngày hôm nay
            $data['ordersToday'] = Order::whereDate('created_at', Carbon::today())->count();

            // 4. Tổng số tài khoản (User)
            $data['totalUsers'] = User::count();

            // 5. Lấy 3 đơn hàng mới nhất
            $data['recentOrders'] = Order::withCount('items')->latest()->take(3)->get();
        }

        $data['isAdmin'] = auth()->user()->role === 'admin';

        return view('home', $data);
    }
}