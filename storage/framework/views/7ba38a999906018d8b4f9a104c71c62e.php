<?php $__env->startSection('content'); ?>
<div class="kh-auth-wrapper">
    <div class="kh-auth-card kh-profile-card">
        <div class="kh-profile-header">
            <div class="kh-avatar-circle">
                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

            </div>
            <h2 class="kh-auth-title"><?php echo e($user->name); ?></h2>
            <p class="kh-auth-subtitle">Thông tin tài khoản thành viên</p>
        </div>

        <div class="kh-profile-content">
            <div class="kh-info-row">
                <span class="kh-info-label">Số điện thoại:</span>
                <span class="kh-info-value"><?php echo e($user->phone); ?></span>
            </div>

            <div class="kh-info-row">
                <span class="kh-info-label">Trạng thái:</span>
                <div class="kh-info-value">
                    <?php if($user->status === 'reviewed'): ?>
                        <span class="kh-badge kh-badge-success">Đã xác minh</span>
                    <?php else: ?>
                        <span class="kh-badge kh-badge-warning">Đang chờ duyệt</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="kh-info-row">
                <span class="kh-info-label">Loại tài khoản:</span>
                <div class="kh-info-value">
                    <?php if($user->role === 'admin'): ?>
                        <span class="kh-badge kh-badge-admin">Quản trị viên</span>
                    <?php else: ?>
                        <span class="kh-badge kh-badge-user">Thành viên</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="kh-info-row">
                <span class="kh-info-label">Ngày tham gia:</span>
                <span class="kh-info-value"><?php echo e($user->created_at->format('d/m/Y')); ?></span>
            </div>
        </div>

        <div class="kh-profile-actions">
            <a href="<?php echo e(route('password.change')); ?>" class="kh-btn-outline">Đổi mật khẩu</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\NHUY\Desktop\huykhanhstore\huykhanhstoreapp\resources\views/auth/user-info.blade.php ENDPATH**/ ?>