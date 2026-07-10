/* header.js */

document.addEventListener("DOMContentLoaded", function() {
    console.log('Header script loaded');

    // Function to toggle user dropdown
    window.toggleUserDropdown = function() {
        const menu = document.getElementById('user-dropdown-menu');
        if (menu.classList.contains('active')) {
            menu.classList.remove('active');
        } else {
            menu.classList.add('active');
        }
    };

    // Function to toggle installation dropdown
    window.toggleInstallDropdown = function(event) {
        // prevent the click bubbling to the document listener (which closes menus)
        if (event) event.stopPropagation();

        const menu = document.getElementById('install-dropdown-menu');
        const dropdown = document.querySelector('.kh-menu-dropdown');
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

    // Function to toggle sales dropdown
    window.toggleSalesDropdown = function(event) {
        // prevent the click bubbling to the document listener (which closes menus)
        if (event) event.stopPropagation();

        const menu = document.getElementById('sales-dropdown-menu');
        const dropdown = event.target.closest('.kh-menu-dropdown');
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

    // Close dropdown(s) when clicking outside
    document.addEventListener('click', function(event) {
        // USER DROPDOWN
        const userMenu = document.getElementById('user-dropdown-menu');
        const userIcon = document.querySelector('.kh-user-icon');
        if (userMenu && userMenu.classList.contains('active')) {
            // if not logged in, userIcon can be null
            if (!userIcon || !userIcon.contains(event.target)) {
                userMenu.classList.remove('active');
            }
        }

        // INSTALLATION DROPDOWN
        const installMenu = document.getElementById('install-dropdown-menu');
        const installDropdown = document.querySelector('.kh-menu-dropdown');
        if (installMenu && installDropdown && installMenu.classList.contains('active')) {
            if (!installDropdown.contains(event.target)) {
                installMenu.classList.remove('active');
                installDropdown.classList.remove('open');
            }
        }

        // SALES DROPDOWN
        const salesMenu = document.getElementById('sales-dropdown-menu');
        const salesDropdown = event.target.closest('.kh-menu-dropdown');
        if (salesMenu && salesDropdown && salesMenu.classList.contains('active')) {
            if (!salesDropdown.contains(event.target)) {
                salesMenu.classList.remove('active');
                salesDropdown.classList.remove('open');
            }
        }

        // Handle clicks on disabled header menu items
        const target = event.target;
        const linkElement = target.closest('a');

        // Check if clicked element or its parent link has disabled classes
        if (target.classList.contains('kh-menu-link-disabled') ||
            target.classList.contains('kh-dropdown-item-disabled') ||
            (linkElement && (linkElement.classList.contains('kh-menu-link-disabled') ||
                           linkElement.classList.contains('kh-dropdown-item-disabled')))) {
            event.preventDefault();
            Swal.fire({
                title: 'Truy cập bị hạn chế',
                text: 'Chỉ có tài khoản admin mới có thể truy cập được chức năng này',
                icon: 'warning',
                confirmButtonText: 'Đã hiểu',
                customClass: {
                    popup: 'swal-header-menu'
                }
            });
            return false;
        }
    });
});
