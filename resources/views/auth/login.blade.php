<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập hệ thống</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/auth-custom.css">
</head>
<body>

<div class="kh-auth-wrapper">
    <div class="kh-auth-card">
        <h2 class="kh-auth-title">Chào mừng trở lại</h2>
        <p class="kh-auth-subtitle">Vui lòng nhập thông tin để đăng nhập</p>

        <form action="{{ route('login') }}" method="POST" id="login-form">
        @csrf 
        @if ($errors->any())
            <div style="color: red; margin-bottom: 10px; text-align: center;">
                {{ $errors->first() }}
            </div>
        @endif          
            <div class="kh-form-group">
                <label for="phone" class="kh-form-label">Số điện thoại</label>
                <div class="kh-input-wrapper">
                    <input type="tel" id="phone" name="phone" 
                           class="kh-form-input" 
                           placeholder="Nhập số điện thoại" 
                           maxlength="10"
                           value=""> 
                </div>
                
                <div id="phone-error" class="kh-error-message" style="display:none">
                    
                </div>
            </div>

            <div class="kh-form-group">
                <label for="password" class="kh-form-label">Mật khẩu</label>
                <div class="kh-input-wrapper">
                    <input type="password" id="password" name="password" 
                           class="kh-form-input" 
                           placeholder="Nhập mật khẩu">
                    <span class="kh-password-toggle" onclick="togglePassword('password', this)">
                        <svg class="eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg class="eye-closed" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                    </span>
                </div>
                
                <div id="password-error" class="kh-error-message" style="display:none">
                    
                </div>
            </div>

            <div class="kh-remember-me">
                <label class="kh-checkbox-wrapper">
                    <input type="checkbox" id="remember" name="remember">
                    <span>Ghi nhớ tôi</span>
                </label>
                <a href="javascript:void(0)" onclick="showForgotPasswordModal()" class="kh-forgot-link">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="kh-btn-submit">Đăng nhập ngay</button>
        </form>

        <div class="kh-auth-footer">
            <p>Chưa có tài khoản? <a href="/register" class="kh-link">Đăng ký tài khoản</a></p>
        </div>
    </div>
</div>
    @include('partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Kiểm tra xem session có cờ 'account_waiting' không
        @if (session('account_waiting'))
            Swal.fire({
                icon: 'warning',
                title: 'Tài khoản chưa được kích hoạt',
                text: 'Tài khoản chưa được xét duyệt. Vui lòng liên hệ admin, Zalo 0386865717 để được hỗ trợ.',
                confirmButtonText: 'Đã hiểu',
                confirmButtonColor: '#3085d6'
            });
        @endif

        // Kiểm tra thông báo đăng ký thành công
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: "{{ session('success') }}",
            });
        @endif
    });
    let flags = {
        phone: false,
        password: false
    };

    function togglePassword(inputId, iconElement) {
        const input = document.getElementById(inputId);
        const eyeOpen = iconElement.querySelector('.eye-open');
        const eyeClosed = iconElement.querySelector('.eye-closed');
        if (input.type === "password") {
            input.type = "text";
            eyeOpen.style.display = "none";
            eyeClosed.style.display = "block";
        } else {
            input.type = "password";
            eyeOpen.style.display = "block";
            eyeClosed.style.display = "none";
        }
    }

    function updateSubmitButton() {
        const submitBtn = document.querySelector('.kh-btn-submit');
        const allValid = Object.values(flags).every(v => v === true);
        
        // Logic mới: Luôn cho phép bấm nút để gửi lên server check,
        // nhưng làm mờ nếu format client sai để user biết
        if (!allValid) {
            submitBtn.style.opacity = '0.7';
        } else {
            submitBtn.style.opacity = '1';
        }
    }

    function showFieldErr(id, message, isValid) {
        const errEl = document.getElementById(id + '-error');
        const inputEl = document.getElementById(id);
        if (!isValid && message) {
            errEl.textContent = message;
            errEl.style.display = 'block';
            inputEl.style.borderColor = '#ef4444';
        } else {
            errEl.style.display = 'none';
            inputEl.style.borderColor = '#e2e8f0';
        }
    }

    function checkPhone(showError = true) {
        const val = document.getElementById('phone').value.trim();
        const regex = /^0\d{9}$/;
        let isValid = true;
        let msg = "";

        if (val.length === 0) {
            isValid = false;
            msg = "Vui lòng nhập số điện thoại";
            if(!showError) msg = "";
        } else if (!regex.test(val)) {
            isValid = false;
            msg = "Số điện thoại không hợp lệ (Bắt đầu bằng 0, đủ 10 số)";
        }
        flags.phone = isValid;
        if(showError) showFieldErr('phone', msg, isValid);
    }

    function checkPassword(showError = true) {
        const val = document.getElementById('password').value;
        const regex = /^[a-zA-Z0-9]{8,16}$/;
        let isValid = true;
        let msg = "";

        if (val.length === 0) {
            isValid = false;
            msg = "Vui lòng nhập mật khẩu";
            if(!showError) msg = "";
        } else if (!regex.test(val)) {
            isValid = false;
            msg = "Mật khẩu 8-16 ký tự, không chứa ký tự đặc biệt";
        }
        flags.password = isValid;
        if(showError) showFieldErr('password', msg, isValid);
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('phone').addEventListener('input', () => { checkPhone(true); updateSubmitButton(); });
        document.getElementById('password').addEventListener('input', () => { checkPassword(true); updateSubmitButton(); });
        
        
        checkPhone(false);
        checkPassword(false);
    });

    function showForgotPasswordModal() {
        Swal.fire({
            title: 'Khôi phục mật khẩu',
            html: `
                <div style="text-align: left; margin-top: 15px;">
                    <p style="color: #475569; font-size: 0.95rem; line-height: 1.5; margin-bottom: 15px;">
                        Vì lý do bảo mật hệ thống, chức năng tự đặt lại mật khẩu đã bị vô hiệu hóa.
                    </p>
                    <p style="color: #1e293b; font-weight: 500; margin-bottom: 10px;">
                        Vui lòng liên hệ Super Admin:
                    </p>
                    <div style="background: #f8fafc; border: 1px dashed #cbd5e1; padding: 15px; border-radius: 8px; display: flex; align-items: center; gap: 15px;">
                        <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/01/Logo-Zalo-Arc.png" style="width: 45px; height: 45px;" alt="Zalo">
                        <div>
                            <div style="font-weight: 700; color: #0068ff; font-size: 1.2rem; letter-spacing: 0.5px;">0386 865 717</div>
                            <div style="font-size: 0.85rem; color: #64748b; margin-top: 2px;">Hỗ trợ Zalo 24/7</div>
                        </div>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#0068ff', // Zalo Blue
            cancelButtonColor: '#e2e8f0',
            confirmButtonText: '<b style="color:white">Nhắn tin Zalo ngay</b>',
            cancelButtonText: '<b style="color:#475569">Đóng</b>',
            width: '450px'
        }).then((result) => {
            if (result.isConfirmed) {
                window.open('https://zalo.me/0386865717', '_blank');
            }
        });
    }
</script>
</body>
</html>
