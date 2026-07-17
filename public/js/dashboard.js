document.addEventListener('DOMContentLoaded', function() {
    
    function updateClock() {
        const now = new Date();
        const greetingEl = document.getElementById('app-greeting');
        
        // --- 1. XỬ LÝ ĐỒNG HỒ CHI TIẾT ---
        const timeEl = document.getElementById('full-clock-time');
        const dateEl = document.getElementById('full-clock-date');

        if(timeEl && dateEl) {
            // Lấy Giờ:Phút
            let hours = now.getHours().toString().padStart(2, '0');
            let minutes = now.getMinutes().toString().padStart(2, '0');
            let seconds = now.getSeconds().toString().padStart(2, '0');

            // HTML: Giờ:Phút<span>:Giây</span> (Để giây nhỏ hơn cho đẹp)
            timeEl.innerHTML = `${hours}:${minutes}<span>:${seconds}</span>`;

            // Lấy Thứ, Ngày/Tháng/Năm
            // style: "Thứ Tư, 10/12/2025"
            const dateOption = { 
                weekday: 'long', 
                day: '2-digit', 
                month: '2-digit', 
                year: 'numeric' 
            };
            // toLocaleDateString tự động dịch sang tiếng Việt nếu máy cài Tiếng Việt
            // Hoặc ép cứng 'vi-VN'
            dateEl.textContent = now.toLocaleDateString('vi-VN', dateOption);
        }

        // --- 2. LỜI CHÀO (Giữ nguyên) ---
        if(greetingEl) {
            const h = now.getHours();
            let text = "Xin chào";
            if (h >= 5 && h < 11) text = "Chào buổi sáng";
            else if (h >= 11 && h < 14) text = "Chào buổi trưa";
            else if (h >= 14 && h < 18) text = "Chào buổi chiều";
            else text = "Buổi tối vui vẻ";
            
            // Cập nhật text nếu chưa có hoặc thay đổi
            if(greetingEl.textContent !== text) {
                greetingEl.textContent = text;
            }
        }
    }

    // Chạy mỗi giây
    setInterval(updateClock, 1000);
    updateClock(); 
});