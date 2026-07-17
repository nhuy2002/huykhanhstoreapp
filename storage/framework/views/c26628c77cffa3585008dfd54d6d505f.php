<header class="kh-header-wrapper">
    <div class="kh-header-container">
        <div class="kh-header-logo">
            <a href="<?php echo e(url('/')); ?>">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="HKC Logo">
            </a>
        </div>

        <nav class="kh-header-nav">
            <ul class="kh-menu-list">
                <li><a href="/" class="kh-menu-link">Trang chủ</a></li>
                <li><a href="<?php echo e(route('profile.show')); ?>" class="kh-menu-link">Thông tin cá nhân</a></li>
                <li class="kh-menu-dropdown">
                    <button type="button" class="kh-menu-link kh-dropdown-toggle" onclick="toggleInstallDropdown(event)">
                        Sản phẩm
                        <span class="kh-dropdown-arrow" aria-hidden="true">▾</span>
                    </button>
                    <ul id="install-dropdown-menu" class="kh-dropdown-menu" aria-label="Sản phẩm">
                        <li><a href="/products" class="kh-dropdown-item">Xem sản phẩm</a></li>
                        <li><a href="<?php echo e(route('products.index')); ?>" class="kh-dropdown-item">Quản lý sản phẩm</a></li>
                    </ul>
                </li>
                <li class="kh-menu-dropdown">
                    <button type="button" class="kh-menu-link kh-dropdown-toggle" onclick="toggleSalesDropdown(event)">
                        Bán hàng
                        <span class="kh-dropdown-arrow" aria-hidden="true">▾</span>
                    </button>
                    <ul id="sales-dropdown-menu" class="kh-dropdown-menu" aria-label="Bán hàng">
                        <li><a href="<?php echo e(route('orders.create')); ?>" class="kh-dropdown-item">Tạo đơn hàng</a></li>
                        <li><a href="<?php echo e(route('orders.index')); ?>" class="kh-dropdown-item <?php if(Auth::user()->role != 'admin'): ?> kh-dropdown-item-disabled <?php endif; ?>">Quản lý đơn hàng</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo e(route('users.index')); ?>" class="kh-menu-link <?php if(Auth::user()->role != 'admin'): ?> kh-menu-link-disabled <?php endif; ?>">Quản lý tài khoản</a></li>
            </ul>
        </nav>

        <div class="kh-header-auth">
            <?php if(auth()->guard()->check()): ?>
                <div class="kh-user-profile">
                    <div class="kh-user-icon" onclick="toggleUserDropdown()">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div id="user-dropdown-menu" class="user-dropdown-menu">
                        <li><a href="<?php echo e(route('password.change')); ?>" class="kh-dropdown-item">Đổi mật khẩu</a></li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a>
                    </div>
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                    </form>
                </div>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="kh-auth-btn kh-btn-login">Đăng nhập</a>
                <a href="<?php echo e(route('register')); ?>" class="kh-auth-btn kh-btn-register">Đăng ký</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<?php /**PATH E:\Project\huykhanhstoreapp\resources\views/partials/header.blade.php ENDPATH**/ ?>