<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;

// Route cho khách (chưa đăng nhập)
// [SEC-TASK-009] Rate limiting: 5 lần/phút cho login, 3 lần/5 phút cho register
Route::middleware('guest')->group(function () {
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::get('/register', function () { return view('auth.register'); })->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,5');
});

Route::middleware(['auth', 'admin'])->group(function () {
    // [SEC-TASK-002] Quản lý sản phẩm (chỉ Admin)
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Quản lý đơn hàng (chỉ Admin xem/sửa/xóa)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Quản lý người dùng (chỉ Admin)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{id}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::patch('/users/{id}/change-role', [UserController::class, 'changeRole'])->name('users.change-role');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user-info', [AuthController::class, 'showProfile'])->name('profile.show');

    // [SEC-TASK-002] Sản phẩm: User thường chỉ được xem danh sách và chi tiết
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

    // Routes cho user thường
    // 1. POS (Chọn món) - Tất cả user đều có thể tạo đơn
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    // 2. CHECKOUT (Trang thanh toán riêng biệt)
    Route::post('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    // 3. LƯU ĐƠN (Xử lý cuối cùng)
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // Trang lỗi truy cập bị từ chối
    Route::get('/access-denied', [App\Http\Controllers\ErrorController::class, 'accessDenied'])->name('error.access-denied');
});


