

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/product-custom.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/order-custom.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/checkout-custom.css')); ?>">

<div class="kh-container">
    <div class="kh-page-header">
        <a href="<?php echo e(route('orders.index')); ?>" class="kh-btn-icon" title="Quay lại">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="kh-page-title">Chỉnh sửa đơn hàng: <?php echo e($order->code); ?></h1>
    </div>

    <form action="<?php echo e(route('orders.update', $order->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="kh-checkout-wrapper">
            <div class="kh-checkout-left">
                <div class="kh-card">
                    <h3 class="kh-card-header">Thông tin khách hàng</h3>
                    <div class="kh-form-group">
                        <label class="kh-form-label">Tên khách hàng <span style="color:red">*</span></label>
                        <input type="text" name="customer_name" class="kh-form-input"
                               value="<?php echo e(old('customer_name', $order->customer_name)); ?>" required>
                        <?php $__errorArgs = ['customer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="kh-form-group">
                        <label class="kh-form-label">Số điện thoại <span style="color:red">*</span></label>
                        <input type="tel" name="customer_phone" class="kh-form-input"
                               value="<?php echo e(old('customer_phone', $order->customer_phone)); ?>" required>
                        <?php $__errorArgs = ['customer_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="kh-form-group">
                        <label class="kh-form-label">Ghi chú đơn hàng</label>
                        <textarea name="note" class="kh-form-input" rows="3"><?php echo e(old('note', $order->note)); ?></textarea>
                        <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="kh-card">
                    <h3 class="kh-card-header">Trạng thái đơn hàng</h3>
                    <div class="kh-form-group">
                        <label class="kh-form-label">Trạng thái <span style="color:red">*</span></label>
                        <select name="status" class="kh-form-input" required>
                            <option value="pending" <?php echo e(old('status', $order->status) == 'pending' ? 'selected' : ''); ?>>Chờ xử lý</option>
                            <option value="completed" <?php echo e(old('status', $order->status) == 'completed' ? 'selected' : ''); ?>>Hoàn thành</option>
                            <option value="shipping" <?php echo e(old('status', $order->status) == 'shipping' ? 'selected' : ''); ?>>Đang giao</option>
                            <option value="cancelled" <?php echo e(old('status', $order->status) == 'cancelled' ? 'selected' : ''); ?>>Đã hủy</option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <div class="kh-checkout-right">
                <div class="kh-summary-header">Chi tiết đơn hàng</div>

                <div class="kh-summary-body">
                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="kh-item-row">
                            <?php if($item['product_image']): ?>
                                <img src="<?php echo e(asset('storage/' . $item['product_image'])); ?>" class="kh-item-img">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/50" class="kh-item-img">
                            <?php endif; ?>

                            <div class="kh-item-info">
                                <div class="kh-item-name"><?php echo e($item['product_name']); ?></div>
                                <div class="kh-item-meta">
                                    SL: <?php echo e($item['quantity']); ?> |
                                    Giá: <?php echo e(number_format($item['price'], 0, ',', '.')); ?> đ
                                </div>
                            </div>

                            <div class="kh-item-price">
                                <?php echo e(number_format($item['subtotal'], 0, ',', '.')); ?> đ
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="kh-summary-footer">
                    <div class="kh-total-row">
                        <span class="kh-total-label">Tổng cộng:</span>
                        <span class="kh-total-value"><?php echo e(number_format($order->total_amount, 0, ',', '.')); ?> đ</span>
                    </div>
                    <div class="kh-buttons-row">
                        <a href="<?php echo e(route('orders.index')); ?>" class="kh-btn-back">Hủy</a>
                        <button type="submit" class="kh-btn-confirm">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    <?php if(session('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '<?php echo e(session("success")); ?>',
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\NHUY\Desktop\huykhanhstore\huykhanhstoreapp\resources\views/orders/edit.blade.php ENDPATH**/ ?>