<!-- Announcement Popup Component -->
<div id="announcement-popup" class="announcement-overlay" style="display: none;">
    <div class="announcement-modal">
        <div class="announcement-header">
            <div class="announcement-icon">
                <i class="fas fa-bullhorn"></i>
            </div>
            <h3 class="announcement-title">Thông báo quan trọng</h3>
            <button class="announcement-close" onclick="closeAnnouncementPopup()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="announcement-content">
            <div class="announcement-section">
                <h4><i class="fas fa-info-circle"></i> Giới thiệu phần mềm</h4>
                <p>Chào mừng bạn đến với <strong>HKC Store Management</strong> - Hệ thống quản lý cửa hàng chuyên nghiệp dành cho nhân sự công ty.</p>
                <p>Phần mềm giúp bạn quản lý sản phẩm, đơn hàng, tài khoản một cách hiệu quả và thuận tiện.</p>
            </div>

            <div class="announcement-section">
                <h4><i class="fas fa-star"></i> Tính năng nổi bật</h4>
                <ul>
                    <li>🏪 Quản lý sản phẩm và kho hàng</li>
                    <li>📦 Xử lý đơn hàng chuyên nghiệp</li>
                    <li>👥 Quản lý tài khoản nhân viện</li>
                    <li>📊 Báo cáo và thống kê chi tiết</li>
                    <li>📱 Giao diện thân thiện trên mọi thiết bị</li>
                </ul>
            </div>

            <div class="announcement-section">
                <h4><i class="fas fa-code"></i> Phát triển phần mềm</h4>
                <p>Nếu bạn cần hỗ trợ, vui lòng liên hệ:</p>
                <p><strong>Lập trình:</strong> Như Ý</p>
                <p><strong>Bản quyền:</strong>Huy Khánh Computer - Version V1.0</p>
            </div>
        </div>

        <div class="announcement-footer">
            <button class="btn-primary" onclick="closeAnnouncementPopup()">
                <i class="fas fa-check"></i> Đã hiểu
            </button>
        </div>
    </div>
</div>

<script>
// Announcement Popup JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Check if popup should be shown (once per 30 minutes)
    const lastShown = localStorage.getItem('announcement_last_shown');
    const now = Date.now();
    const thirtyMinutes = 1 * 60 * 1000; // 30 minutes in milliseconds

    if (!lastShown || (now - parseInt(lastShown)) > thirtyMinutes) {
        // Show popup after a short delay for better UX
        setTimeout(() => {
            showAnnouncementPopup();
        }, 1500);
    }
});

function showAnnouncementPopup() {
    const popup = document.getElementById('announcement-popup');
    if (popup) {
        popup.style.display = 'flex';

        // Prevent background scrolling
        document.body.style.overflow = 'hidden';

        // Add fade-in animation
        setTimeout(() => {
            popup.classList.add('active');
        }, 10);
    }
}

function closeAnnouncementPopup() {
    const popup = document.getElementById('announcement-popup');
    if (popup) {
        popup.classList.remove('active');

        // Allow background scrolling
        document.body.style.overflow = '';

        // Hide popup after animation
        setTimeout(() => {
            popup.style.display = 'none';
        }, 300);

        // Store timestamp in localStorage
        localStorage.setItem('announcement_last_shown', Date.now().toString());
    }
}

// Close popup when clicking outside
document.addEventListener('click', function(event) {
    const popup = document.getElementById('announcement-popup');
    const modal = popup ? popup.querySelector('.announcement-modal') : null;

    if (popup && modal && event.target === popup) {
        closeAnnouncementPopup();
    }
});

// Close popup with ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAnnouncementPopup();
    }
});
</script>
