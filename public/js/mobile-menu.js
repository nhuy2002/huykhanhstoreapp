/* mobile-menu.js */

document.addEventListener("DOMContentLoaded", function() {
    console.log('Mobile menu script loaded');

    // Highlight active nav item
    const currentPath = window.location.pathname;
    const navItems = document.querySelectorAll('.mtb-item');
    navItems.forEach(item => {
        if (item.tagName === 'A') {
            const href = item.getAttribute('href');
            if (currentPath === href) {
                item.classList.add('active');
            }
        }
    });

    // 1. Khai báo các biến (Lấy phần tử HTML)
    const toggleButton = document.getElementById('mtb-toggle-sidebar'); // Nút Danh mục ở dưới
    const sidebar = document.getElementById('mb-sidebar');             // Thanh menu trượt
    const overlay = document.getElementById('mb-overlay');             // Màn đen mờ
    const closeButton = document.getElementById('mb-close-btn');       // Nút X đóng
    const body = document.body;

    console.log('Toggle button:', toggleButton);
    console.log('Sidebar:', sidebar);

    // 2. Hàm mở Menu
    function openSidebar() {
        sidebar.classList.add('mb-menu-active');
        overlay.classList.add('mb-menu-active');
        body.style.overflow = 'hidden'; // Khóa không cho cuộn trang web

        // (Tùy chọn) Highlight nút danh mục khi mở
        if(toggleButton) toggleButton.classList.add('active');
    }

    // 3. Hàm đóng Menu
    function closeSidebar() {
        sidebar.classList.remove('mb-menu-active');
        overlay.classList.remove('mb-menu-active');
        body.style.overflow = ''; // Cho phép cuộn lại bình thường

        // Bỏ highlight nút danh mục
        if(toggleButton) toggleButton.classList.remove('active');
    }

    // 4. Gán sự kiện Click
    
    // Khi bấm nút Danh mục ở Taskbar
    if (toggleButton) {
        console.log('Adding click event to toggleButton');
        toggleButton.addEventListener('click', function(e) {
            console.log('Toggle button clicked');
            e.preventDefault(); // Ngăn chặn chuyển trang (nếu thẻ a có href)

            // Nếu đang mở thì đóng, đang đóng thì mở
            if (sidebar.classList.contains('mb-menu-active')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    } else {
        console.log('Toggle button not found');
    }

    // Khi bấm nút X
    if (closeButton) {
        closeButton.addEventListener('click', closeSidebar);
    }

    // Khi bấm ra ngoài vùng đen (Overlay)
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    // Function to toggle mobile installation dropdown
    window.toggleMobileInstallDropdown = function(event) {
        if (event) event.stopPropagation();

        const menu = document.getElementById('mobile-install-dropdown-menu');
        const dropdown = event.target.closest('.sidebar-menu-dropdown');
        if (!menu || !dropdown) return;

        const isActive = menu.classList.contains('active');
        if (isActive) {
            menu.classList.remove('active');
            dropdown.classList.remove('open');
        } else {
            menu.classList.add('active');
            dropdown.classList.add('open');
        }
    };

    // Function to toggle mobile sales dropdown
    window.toggleMobileSalesDropdown = function(event) {
        if (event) event.stopPropagation();

        const menu = document.getElementById('mobile-sales-dropdown-menu');
        const dropdown = event.target.closest('.sidebar-menu-dropdown');
        if (!menu || !dropdown) return;

        const isActive = menu.classList.contains('active');
        if (isActive) {
            menu.classList.remove('active');
            dropdown.classList.remove('open');
        } else {
            menu.classList.add('active');
            dropdown.classList.add('open');
        }
    };

    // Handle clicks on disabled menu items
    document.addEventListener('click', function(event) {
        const target = event.target;
        const linkElement = target.closest('a');

        // Check if clicked element or its parent link has disabled classes
        if (target.classList.contains('sidebar-menu-link-disabled') ||
            target.classList.contains('sidebar-dropdown-item-disabled') ||
            (linkElement && (linkElement.classList.contains('sidebar-menu-link-disabled') ||
                           linkElement.classList.contains('sidebar-dropdown-item-disabled')))) {
            event.preventDefault();
            // Close mobile menu first
            closeSidebar();
            // Show SweetAlert after a short delay to ensure menu is closed
            setTimeout(() => {
                Swal.fire({
                    title: 'Truy cập bị hạn chế',
                    text: 'Chỉ có tài khoản admin mới có thể truy cập được chức năng này',
                    icon: 'warning',
                    confirmButtonText: 'Đã hiểu',
                    customClass: {
                        popup: 'swal-mobile-menu'
                    }
                });
            }, 300);
            return false;
        }
    });
});
