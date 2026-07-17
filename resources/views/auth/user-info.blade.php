@extends('layouts.app')

@section('content')
<div class="kh-auth-wrapper">
    <div class="kh-auth-card kh-profile-card">
        <div class="kh-profile-header">
            <div class="kh-avatar-circle">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h2 class="kh-auth-title">{{ $user->name }}</h2>
            <p class="kh-auth-subtitle">Thông tin tài khoản thành viên</p>
        </div>

        <div class="kh-profile-content">
            <div class="kh-info-row">
                <span class="kh-info-label">Số điện thoại:</span>
                <span class="kh-info-value">{{ $user->phone }}</span>
            </div>

            <div class="kh-info-row">
                <span class="kh-info-label">Trạng thái:</span>
                <div class="kh-info-value">
                    @if($user->status === 'reviewed')
                        <span class="kh-badge kh-badge-success">Đã xác minh</span>
                    @else
                        <span class="kh-badge kh-badge-warning">Đang chờ duyệt</span>
                    @endif
                </div>
            </div>

            <div class="kh-info-row">
                <span class="kh-info-label">Loại tài khoản:</span>
                <div class="kh-info-value">
                    @if($user->role === 'admin')
                        <span class="kh-badge kh-badge-admin">Quản trị viên</span>
                    @else
                        <span class="kh-badge kh-badge-user">Thành viên</span>
                    @endif
                </div>
            </div>

            <div class="kh-info-row">
                <span class="kh-info-label">Ngày tham gia:</span>
                <span class="kh-info-value">{{ $user->created_at->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="kh-profile-actions">
            <a href="{{ route('password.change') }}" class="kh-btn-outline">Đổi mật khẩu</a>
        </div>
    </div>
</div>
@endsection