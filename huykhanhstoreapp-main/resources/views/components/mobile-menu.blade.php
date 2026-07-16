<!-- Styles and scripts are included in the main layout -->

<div class="mobile-menu-container">
    
    <div id="mb-overlay" class="mobile-overlay"></div>

    <div id="mb-sidebar" class="mobile-sidebar">
        <div class="sidebar-header">
            <h3 class="sidebar-title">Danh mục</h3>
            <button id="mb-close-btn" class="sidebar-close-btn">&times;</button>
        </div>

        <div class="sidebar-content">
            <ul class="sidebar-menu-list">
                
                <li class="sidebar-menu-item">
                    <a href="/" class="sidebar-menu-link">Trang chủ</a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('profile.show') }}" class="sidebar-menu-link">Thông tin cá nhân</a>
                </li>

                <li class="sidebar-menu-item sidebar-menu-dropdown">
                    <a href="#" class="sidebar-menu-link sidebar-dropdown-toggle" onclick="event.preventDefault(); toggleMobileInstallDropdown(event);">
                        <span class="sidebar-menu-text">Sản phẩm</span>
                        <span class="sidebar-dropdown-arrow">▾</span>
                    </a>
                    <ul id="mobile-install-dropdown-menu" class="sidebar-dropdown-menu" aria-label="Yêu cầu lắp đặt">
                        <li><a href="" class="sidebar-dropdown-item">Xem sản phẩm</a></li>
                        <li><a href="" class="sidebar-dropdown-item">Quản lý sản phẩm</a></li>
                    </ul>
                </li>

                <li class="sidebar-menu-item sidebar-menu-dropdown">
                    <a href="#" class="sidebar-menu-link sidebar-dropdown-toggle" onclick="event.preventDefault(); toggleMobileSalesDropdown(event);">
                        <span class="sidebar-menu-text">Bán hàng</span>
                        <span class="sidebar-dropdown-arrow">▾</span>
                    </a>
                    <ul id="mobile-sales-dropdown-menu" class="sidebar-dropdown-menu" aria-label="Bán hàng">
                        <li><a href="{{ route('orders.create') }}" class="sidebar-dropdown-item">Tạo đơn hàng</a></li>
                        <li><a href="{{ route('orders.index') }}" class="sidebar-dropdown-item @if(Auth::user()->role != 'admin') sidebar-dropdown-item-disabled @endif">Quản lý đơn hàng</a></li>
                    </ul>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('users.index') }}" class="sidebar-menu-link @if(Auth::user()->role != 'admin') sidebar-menu-link-disabled @endif">Quản lý tài khoản</a>
                </li>
            </ul>
        </div>
    </div>

    <nav class="mtb-navbar">
        
        <a href="/" class="mtb-item">
            <div class="mtb-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            </div>
            <span>Trang chủ</span>
        </a>

        <div id="mtb-toggle-sidebar" class="mtb-item">
            <div class="mtb-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            </div>
            <span>Danh mục</span>
        </div>

        <a href="/products" class="mtb-item">
            <div class="mtb-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12.89 1.45l8 4A2 2 0 0 1 22 7v10a2 2 0 0 1-1.11 1.79l-8 4a2 2 0 0 1-1.79 0l-8-4a2 2 0 0 1-1.1-1.79V7a2 2 0 0 1 1.11-1.79l8-4a2 2 0 0 1 1.78 0z"></path><polyline points="2.32 6.16 12 11 21.68 6.16"></polyline><line x1="12" y1="22.76" x2="12" y2="11"></line></svg>
            </div>
            <span>Sản phẩm</span>
        </a>
        <a href="{{ route('orders.create') }}" class="mtb-item">
                <div class="mtb-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><polyline points="5 6 19 6"></polyline><path d="M9 10v4"></path><path d="M15 10v4"></path></svg>
                </div>
                <span>Bán hàng</span>
        </a>
        <a href="{{ route('profile.show') }}" class="mtb-item">
            <div class="mtb-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
            <span>Tài khoản</span>
        </a>
    </nav>
</div>
