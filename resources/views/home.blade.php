@extends('layouts.app')

@section('content')
@include('components.announcement-popup')

    <div class="k-app-wrapper">
        
        <div class="k-app-header">
            <div class="k-user-info">
                <p id="app-greeting">Xin chào</p>
                {{-- Hiển thị tên user đang đăng nhập --}}
                <h1>{{ Auth::user()->name }}</h1>
            </div>
            <div class="k-user-avatar">
                <div class="k-clock-time" id="full-clock-time">00:00<span>:00</span></div>
                <div class="k-clock-date" id="full-clock-date">--/--/----</div>
            </div>
        </div>

        <div class="k-widgets-grid">
            
            {{-- WIDGET 1: DOANH THU (ĐỘNG) --}}
            <div class="k-widget-card">
                <div class="k-widget-bg-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M7 15h0M2 9.5h20"/></svg>
                </div>
                <div class="k-widget-icon bg-orange">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M7 15h0M2 9.5h20"/></svg>
                </div>
                <div>
                    <div class="k-widget-label">Tổng doanh thu</div>
                    <div class="k-widget-value">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</div> 
                </div>
            </div>

            {{-- WIDGET 2: TỔNG ĐƠN (ĐỘNG) --}}
            <div class="k-widget-card">
                <div class="k-widget-bg-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                </div>
                <div class="k-widget-icon bg-blue">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                </div>
                <div>
                    <div class="k-widget-label">Tổng đơn hàng</div>
                    <div class="k-widget-value">{{ $totalOrders }}</div>
                </div>
            </div>

            {{-- WIDGET 3: ĐƠN HÔM NAY (ĐỘNG) --}}
            <div class="k-widget-card">
                <div class="k-widget-bg-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="k-widget-icon bg-purple">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    <div class="k-widget-label">Đơn hàng hôm nay</div>
                    <div class="k-widget-value">{{ $ordersToday }}</div>
                </div>
            </div>

            {{-- WIDGET 4: SỐ USER (ĐỘNG) --}}
            <a href="{{ route('users.index') }}" class="k-widget-card" style="text-decoration: none; color: inherit;">
                <div class="k-widget-bg-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <div class="k-widget-icon bg-green">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <div>
                    <div class="k-widget-label">Số lượng tài khoản</div>
                    <div class="k-widget-value" style="color:#16a34a">{{ $totalUsers }}</div>
                </div>
            </a>

        </div>

        <div class="k-section-header">
            <h3 class="k-section-title">Đơn hàng mới nhất</h3>
            <a href="{{ route('orders.index') }}" class="k-section-more">Xem tất cả</a>
        </div>

        <div class="k-list-container">
            {{-- Vòng lặp hiển thị đơn hàng mới --}}
            @forelse($recentOrders as $order)
                <a href="{{ route('orders.index') }}" class="k-list-item">
                    <div class="k-item-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                    </div>
                    <div class="k-item-content">
                        <h4 class="k-item-title">{{ $order->customer_name }}</h4>
                        {{-- Hiển thị Mã đơn và số lượng sản phẩm --}}
                        <p class="k-item-sub">
                            Đơn #{{ $order->code }} • {{ $order->items_count }} sản phẩm • {{ $order->created_at->locale('vi')->diffForHumans() }}
                        </p>
                    </div>
                    
                    {{-- Badge trạng thái --}}
                    @php
                        $statusClass = match($order->status) {
                            'completed' => 'st-green',  // Cần định nghĩa class này trong CSS nếu chưa có
                            'cancelled' => 'st-red',
                            'shipping'  => 'st-blue',
                            default     => 'st-yellow',
                        };
                        
                        // Hardcode style trực tiếp cho nhanh nếu chưa có file CSS badge riêng
                        $statusStyle = match($order->status) {
                            'completed' => 'background:#dcfce7; color:#15803d;',
                            'cancelled' => 'background:#fee2e2; color:#ef4444;',
                            'shipping'  => 'background:#e0f2fe; color:#0284c7;',
                            default     => 'background:#fef3c7; color:#d97706;',
                        };

                        $statusLabel = match($order->status) {
                            'completed' => 'Hoàn thành',
                            'cancelled' => 'Đã hủy',
                            'shipping'  => 'Đang giao',
                            default     => 'Chờ xử lý',
                        };
                    @endphp

                    <div class="k-item-status" style="padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; {{ $statusStyle }}">
                        {{ $statusLabel }}
                    </div>
                </a>
            @empty
                <div style="text-align: center; padding: 20px; color: #999;">
                    Chưa có đơn hàng nào.
                </div>
            @endforelse

        </div>

        <div class="k-section-header">
            <h3 class="k-section-title">Bảng tin</h3>
        </div>

        <div class="k-news-scroll">
            {{-- Tin tức giữ nguyên (Tĩnh) --}}
            <div class="k-news-card">
                <span class="k-news-tag">Bảo trì</span>
                <p class="k-news-text">Hệ thống sẽ bảo trì định kỳ từ 00:00 - 02:00 ngày 21/12. Vui lòng lưu dữ liệu.</p>
            </div>
            <div class="k-news-card">
                <span class="k-news-tag" style="color:#0ea5e9; background:#f0f9ff">Thông báo</span>
                <p class="k-news-text">Đã cập nhật tính năng gửi gmail khi có đơn hàng tới ADMIN.</p>
            </div>
             <div class="k-news-card">
                <span class="k-news-tag" style="color:#f59e0b; background:#fffbeb">Bảo mật</span>
                <p class="k-news-text">Cập nhật tính năng bảo mật mới ngăn chặn truy cập vào các trang không thuộc thẩm quyền.</p>
            </div>
        </div>

    </div>

    <script>
        @if(session('order_success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: '{{ session("order_success") }}',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
@endsection
