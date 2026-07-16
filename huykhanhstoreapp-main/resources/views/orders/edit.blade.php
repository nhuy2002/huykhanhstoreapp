@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/product-custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/order-custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/checkout-custom.css') }}">

<div class="kh-container">
    <div class="kh-page-header">
        <a href="{{ route('orders.index') }}" class="kh-btn-icon" title="Quay lại">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="kh-page-title">Chỉnh sửa đơn hàng: {{ $order->code }}</h1>
    </div>

    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="kh-checkout-wrapper">
            <div class="kh-checkout-left">
                <div class="kh-card">
                    <h3 class="kh-card-header">Thông tin khách hàng</h3>
                    <div class="kh-form-group">
                        <label class="kh-form-label">Tên khách hàng <span style="color:red">*</span></label>
                        <input type="text" name="customer_name" class="kh-form-input"
                               value="{{ old('customer_name', $order->customer_name) }}" required>
                        @error('customer_name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="kh-form-group">
                        <label class="kh-form-label">Số điện thoại <span style="color:red">*</span></label>
                        <input type="tel" name="customer_phone" class="kh-form-input"
                               value="{{ old('customer_phone', $order->customer_phone) }}" required>
                        @error('customer_phone')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="kh-form-group">
                        <label class="kh-form-label">Ghi chú đơn hàng</label>
                        <textarea name="note" class="kh-form-input" rows="3">{{ old('note', $order->note) }}</textarea>
                        @error('note')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="kh-card">
                    <h3 class="kh-card-header">Trạng thái đơn hàng</h3>
                    <div class="kh-form-group">
                        <label class="kh-form-label">Trạng thái <span style="color:red">*</span></label>
                        <select name="status" class="kh-form-input" required>
                            <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="shipping" {{ old('status', $order->status) == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                            <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                        @error('status')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="kh-checkout-right">
                <div class="kh-summary-header">Chi tiết đơn hàng</div>

                <div class="kh-summary-body">
                    @foreach($order->items as $item)
                        <div class="kh-item-row">
                            @if($item['product_image'])
                                <img src="{{ asset('storage/' . $item['product_image']) }}" class="kh-item-img">
                            @else
                                <img src="https://via.placeholder.com/50" class="kh-item-img">
                            @endif

                            <div class="kh-item-info">
                                <div class="kh-item-name">{{ $item['product_name'] }}</div>
                                <div class="kh-item-meta">
                                    SL: {{ $item['quantity'] }} |
                                    Giá: {{ number_format($item['price'], 0, ',', '.') }} đ
                                </div>
                            </div>

                            <div class="kh-item-price">
                                {{ number_format($item['subtotal'], 0, ',', '.') }} đ
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="kh-summary-footer">
                    <div class="kh-total-row">
                        <span class="kh-total-label">Tổng cộng:</span>
                        <span class="kh-total-value">{{ number_format($order->total_amount, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="kh-buttons-row">
                        <a href="{{ route('orders.index') }}" class="kh-btn-back">Hủy</a>
                        <button type="submit" class="kh-btn-confirm">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{{ session("success") }}',
            confirmButtonText: 'OK'
        });
    @endif
</script>
@endsection
