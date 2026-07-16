<header class="kh-header-wrapper">
    <div class="kh-header-container">
        <div class="kh-header-logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="HKC Logo">
            </a>
        </div>

        <nav class="kh-header-nav">
            <ul class="kh-menu-list">
                <li><a href="/" class="kh-menu-link">Trang chủ</a></li>
                <li><a href="{{ route('profile.show') }}" class="kh-menu-link">Thông tin cá nhân</a></li>
                <li class="kh-menu-dropdown">
                    <button type="button" class="kh-menu-link kh-dropdown-toggle" onclick="toggleInstallDropdown(event)">
                        Sản phẩm
                        <span class="kh-dropdown-arrow" aria-hidden="true">▾</span>
                    </button>
                    <ul id="install-dropdown-menu" class="kh-dropdown-menu" aria-label="Sản phẩm">
                        <li><a href="/products" class="kh-dropdown-item">Xem sản phẩm</a></li>
                        <li><a href="{{ route('products.index') }}" class="kh-dropdown-item">Quản lý sản phẩm</a></li>
                    </ul>
                </li>
                <li class="kh-menu-dropdown">
                    <button type="button" class="kh-menu-link kh-dropdown-toggle" onclick="toggleSalesDropdown(event)">
                        Bán hàng
                        <span class="kh-dropdown-arrow" aria-hidden="true">▾</span>
                    </button>
                    <ul id="sales-dropdown-menu" class="kh-dropdown-menu" aria-label="Bán hàng">
                        <li><a href="{{ route('orders.create') }}" class="kh-dropdown-item">Tạo đơn hàng</a></li>
                        <li><a href="{{ route('orders.index') }}" class="kh-dropdown-item @if(Auth::user()->role != 'admin') kh-dropdown-item-disabled @endif">Quản lý đơn hàng</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('users.index') }}" class="kh-menu-link @if(Auth::user()->role != 'admin') kh-menu-link-disabled @endif">Quản lý tài khoản</a></li>
            </ul>
        </nav>

        <div class="kh-header-auth">
            @auth
                <div class="kh-user-profile">
                    <div class="kh-user-icon" onclick="toggleUserDropdown()">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div id="user-dropdown-menu" class="user-dropdown-menu">
                        <li><a href="{{ route('password.change') }}" class="kh-dropdown-item">Đổi mật khẩu</a></li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="kh-auth-btn kh-btn-login">Đăng nhập</a>
                <a href="{{ route('register') }}" class="kh-auth-btn kh-btn-register">Đăng ký</a>
            @endauth
        </div>
    </div>
</header>
