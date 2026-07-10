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
        // 1. Tổng doanh thu (Chỉ tính các đơn đã hoàn thành)
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        // 2. Tổng số đơn hàng
        $totalOrders = Order::count();

        // 3. Đơn hàng trong ngày hôm nay
        $ordersToday = Order::whereDate('created_at', Carbon::today())->count();

        // 4. Tổng số tài khoản (User)
        $totalUsers = User::count();

        // 5. Lấy 3 đơn hàng mới nhất (Kèm thông tin items để đếm số lượng món)
        $recentOrders = Order::withCount('items')->latest()->take(3)->get();

        return view('home', compact(
            'totalRevenue', 
            'totalOrders', 
            'ordersToday', 
            'totalUsers', 
            'recentOrders'
        ));
    }
}