<?php $__env->startSection('content'); ?>
<div class="kh-auth-wrapper">
    <div class="kh-auth-card">
        <h2 class="kh-auth-title">Đổi mật khẩu</h2>
        <p class="kh-auth-subtitle">Cập nhật mật khẩu mới cho tài khoản của bạn</p>

        <?php if(session('success')): ?>
            <div style="color: green; text-align: center; margin-bottom: 15px; font-weight: 500;">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('password.change.submit')); ?>" method="POST" id="change-password-form">
            <?php echo csrf_field(); ?>

            
            <div class="kh-form-group">
                <label class="kh-form-label">Mật khẩu hiện tại</label>
                <div class="kh-input-wrapper">
                    <input type="password" id="current_password" name="current_password" class="kh-form-input <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Nhập mật khẩu cũ">
                </div>
                <div id="current_password-error" class="kh-error-message" style="<?php echo e($errors->has('current_password') ? 'display:block' : 'display:none'); ?>">
                    <?php echo e($errors->first('current_password')); ?>

                </div>
            </div>

            
            <div class="kh-form-group">
                <label class="kh-form-label">Mật khẩu mới</label>
                <div class="kh-input-wrapper">
                    <input type="password" id="password" name="password" class="kh-form-input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Nhập mật khẩu mới">
                </div>
                <div id="password-error" class="kh-error-message" style="<?php echo e($errors->has('password') ? 'display:block' : 'display:none'); ?>">
                    <?php echo e($errors->first('password')); ?>

                </div>
            </div>

            
            <div class="kh-form-group">
                <label class="kh-form-label">Xác nhận mật khẩu mới</label>
                <div class="kh-input-wrapper">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="kh-form-input" placeholder="Nhập lại mật khẩu mới">
                </div>
                <div id="password_confirmation-error" class="kh-error-message" style="display:none;"></div>
            </div>

            <button type="submit" class="kh-btn-submit">Cập nhật mật khẩu</button>
        </form>

        <div class="kh-auth-footer">
            <p><a href="/" class="kh-link">Quay về trang chủ</a></p>
        </div>
    </div>
</div>
<?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
    let flags = {
        current_password: false,
        password: false,
        password_confirmation: false
    };

    function updateSubmitButton() {
        const submitBtn = document.querySelector('.kh-btn-submit');
        const allValid = Object.values(flags).every(v => v === true);

        submitBtn.disabled = !allValid;
        if (!allValid) {
            submitBtn.style.opacity = '0.7';
            submitBtn.style.cursor = 'not-allowed';
        } else {
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
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

    function checkCurrentPassword(showError = true) {
        const val = document.getElementById('current_password').value;
        const regex = /^[a-zA-Z0-9]{8,16}$/;
        let isValid = true;
        let msg = "";

        if (val.length === 0) {
            isValid = false;
            msg = "Vui lòng nhập mật khẩu hiện tại";
            if(!showError) msg = "";
        } else if (!regex.test(val)) {
            isValid = false;
            msg = "Mật khẩu 8-16 ký tự, chỉ chứa chữ và số, không ký tự đặc biệt";
        }
        flags.current_password = isValid;
        if(showError) showFieldErr('current_password', msg, isValid);
    }

    function checkPassword(showError = true) {
        const val = document.getElementById('password').value;
        const regex = /^[a-zA-Z0-9]{8,16}$/;
        let isValid = true;
        let msg = "";

        if (val.length === 0) {
            isValid = false;
            msg = "Vui lòng nhập mật khẩu mới";
            if(!showError) msg = "";
        } else if (!regex.test(val)) {
            isValid = false;
            msg = "Mật khẩu 8-16 ký tự, chỉ chứa chữ và số, không ký tự đặc biệt";
        }
        flags.password = isValid;
        if(showError) showFieldErr('password', msg, isValid);
    }

    function checkPasswordConfirmation(showError = true) {
        const passwordVal = document.getElementById('password').value;
        const confirmVal = document.getElementById('password_confirmation').value;
        let isValid = true;
        let msg = "";

        if (confirmVal.length === 0) {
            isValid = false;
            msg = "Vui lòng xác nhận mật khẩu mới";
            if(!showError) msg = "";
        } else if (confirmVal !== passwordVal) {
            isValid = false;
            msg = "Mật khẩu xác nhận không khớp";
        } else if (!/^[a-zA-Z0-9]{8,16}$/.test(confirmVal)) {
            isValid = false;
            msg = "Mật khẩu 8-16 ký tự, chỉ chứa chữ và số, không ký tự đặc biệt";
        }
        flags.password_confirmation = isValid;
        if(showError) showFieldErr('password_confirmation', msg, isValid);
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('current_password').addEventListener('input', () => { checkCurrentPassword(true); updateSubmitButton(); });
        document.getElementById('password').addEventListener('input', () => {
            checkPassword(true);
            checkPasswordConfirmation(true); // Re-check confirmation when password changes
            updateSubmitButton();
        });
        document.getElementById('password_confirmation').addEventListener('input', () => { checkPasswordConfirmation(true); updateSubmitButton(); });

        // Allow submit, but check all on input - server will handle final validation

        // Logic xử lý khi có lỗi Server trả về (Giữ nguyên viền đỏ)
        <?php if($errors->has('current_password')): ?>
            document.getElementById('current_password').style.borderColor = '#ef4444';
        <?php endif; ?>
        <?php if($errors->has('password')): ?>
            document.getElementById('password').style.borderColor = '#ef4444';
        <?php endif; ?>

        checkCurrentPassword(false);
        checkPassword(false);
        checkPasswordConfirmation(false);
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\NHUY\Desktop\huykhanhstore\huykhanhstoreapp\resources\views/auth/change.blade.php ENDPATH**/ ?>