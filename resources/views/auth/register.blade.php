<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký thành viên</title>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/auth-custom.css">
</head>
<body>

<div class="kh-auth-wrapper">
    <div class="kh-auth-card">
        <h2 class="kh-auth-title">Tạo tài khoản mới</h2>
        <p class="kh-auth-subtitle">Đăng ký xét duyệt nhân viên Huy Khánh</p>

        <form action="{{ route('register') }}" method="POST" id="register-form">
        @csrf

            <div class="kh-form-group">
                <label for="name" class="kh-form-label">Họ và tên</label>
                <div class="kh-input-wrapper">
                    <input type="text" id="name" name="name"
                           class="kh-form-input"
                           placeholder="Hoàng Nghĩa Như Ý"
                           value="">
                </div>
                <div id="name-error" class="kh-error-message" style="display:none">

                </div>
            </div>

            <div class="kh-form-group">
                <label for="phone" class="kh-form-label">Số điện thoại</label>
                <div class="kh-input-wrapper">
                    <input type="tel" id="phone" name="phone"
                           class="kh-form-input"
                           placeholder="Nhập số điện thoại" maxlength="10"
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

            <div class="kh-form-group">
                <label for="password_confirmation" class="kh-form-label">Xác nhận mật khẩu</label>
                <div class="kh-input-wrapper">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="kh-form-input" placeholder="Nhập lại mật khẩu">
                </div>
                <div id="password_confirmation-error" class="kh-error-message" style="display:none">

                </div>
            </div>

            <button type="submit" class="kh-btn-submit">Tạo tài khoản</button>
        </form>

        <div class="kh-auth-footer">
            <p>Đã có tài khoản? <a href="/login" class="kh-link">Đăng nhập</a></p>
        </div>
    </div>
</div>
@include('partials.footer')

<script>
    let flags = {
        name: false,
        phone: false,
        password: false,
        password_confirmation: false
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
        const allValid = Object.values(flags).every(value => value === true);

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

    function checkName(showError = true) {
        const val = document.getElementById('name').value.trim();
        const regex = /^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵýỷỹ\s]{1,80}$/;
        let isValid = true;
        let msg = "";

        if (val.length === 0) {
            isValid = false;
            msg = "Vui lòng nhập họ và tên";
            if(!showError) msg = "";
        } else if (val.length > 80) {
            isValid = false;
            msg = "Họ tên không được quá 80 ký tự";
        } else if (!regex.test(val)) {
            isValid = false;
            msg = "Họ tên chỉ được chứa chữ cái";
        }
        flags.name = isValid;
        if(showError) showFieldErr('name', msg, isValid);
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
            msg = "Số điện thoại phải bắt đầu bằng 0 và có đúng 10 chữ số";
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
            msg = "Mật khẩu 8-16 ký tự, chỉ gồm chữ và số";
        }
        flags.password = isValid;
        if(showError) showFieldErr('password', msg, isValid);
        if (document.getElementById('password_confirmation').value.length > 0) {
            checkConfirmPassword(true);
        }
    }

    function checkConfirmPassword(showError = true) {
        const pass = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;
        let isValid = true;
        let msg = "";

        if (confirm.length === 0) {
            isValid = false;
            msg = "Vui lòng xác nhận mật khẩu";
            if(!showError) msg = "";
        } else if (confirm !== pass) {
            isValid = false;
            msg = "Mật khẩu xác nhận không khớp";
        }
        flags.password_confirmation = isValid;
        if(showError) showFieldErr('password_confirmation', msg, isValid);
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('name').addEventListener('input', () => { checkName(true); updateSubmitButton(); });
        document.getElementById('phone').addEventListener('input', () => { checkPhone(true); updateSubmitButton(); });
        document.getElementById('password').addEventListener('input', () => { checkPassword(true); updateSubmitButton(); });
        document.getElementById('password_confirmation').addEventListener('input', () => { checkConfirmPassword(true); updateSubmitButton(); });


        checkName(false);
        checkPhone(false);
        checkPassword(false);
        checkConfirmPassword(false);
    });
</script>
</body>
</html>
