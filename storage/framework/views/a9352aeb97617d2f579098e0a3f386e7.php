

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/product-custom.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/auth-custom.css')); ?>">

<div class="kh-container" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    
    <div class="kh-page-header">
        <h1 class="kh-page-title">Thêm sản phẩm mới</h1>
        <a href="<?php echo e(route('products.index')); ?>" class="kh-btn-icon" title="Quay lại danh sách">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
    </div>

    <?php if($errors->any()): ?>
        <div style="background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('products.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="kh-form-layout">
            <div class="kh-col-left">
                <div class="kh-card">
                    <h3 style="margin-top:0; margin-bottom:15px;">Thông tin chung</h3>
                    <div class="kh-form-group">
                        <label class="kh-form-label">Tên sản phẩm <span style="color:red">*</span></label>
                        <div class="kh-input-wrapper">
                            <input type="text" name="name" class="kh-form-input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name')); ?>" placeholder="Ví dụ: Laptop Dell XPS 13">
                        </div>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color:red; font-size:0.85rem"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="kh-form-row">
                        <div class="kh-form-group">
                            <label class="kh-form-label">Giá bán <span style="color:red">*</span></label>
                            <div class="kh-input-wrapper">
                                <input type="number" name="price" class="kh-form-input <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('price')); ?>">
                            </div>
                            <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color:red; font-size:0.85rem"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="kh-form-group">
                            <label class="kh-form-label">Kho <span style="color:red">*</span></label>
                            <div class="kh-input-wrapper">
                                <input type="number" name="quantity" class="kh-form-input <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('quantity')); ?>">
                            </div>
                            <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color:red; font-size:0.85rem"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="kh-form-group">
                        <label class="kh-form-label">Mô tả</label>
                        <div class="kh-input-wrapper">
                            <textarea name="description" rows="5" class="kh-form-input" style="height:auto;"><?php echo e(old('description')); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="kh-card">
                    <h3 style="margin-top:0; margin-bottom:15px;">Thư viện ảnh (Chi tiết)</h3>
                    <div class="kh-input-wrapper">
                         <input type="file" name="gallery[]" multiple class="kh-form-input" accept="image/*">
                    </div>
                    <small style="color: #64748b;">Giữ Ctrl để chọn nhiều ảnh.</small>
                </div>
            </div>

            <div class="kh-col-right">
                <div class="kh-card">
                    <h3 style="margin-top:0; margin-bottom:15px;">Ảnh đại diện</h3>
                    <div class="kh-image-upload-box" onclick="document.getElementById('thumb-input').click()">
                        <img id="thumb-preview" class="kh-preview-img" src="#" alt="Preview">
                        <span id="upload-text" style="color:#64748b;">Tải ảnh lên</span>
                        <input type="file" id="thumb-input" name="image" style="display: none;" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color:red; font-size:0.85rem"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="kh-card">
                    <h3 style="margin-top:0; margin-bottom:15px;">Thiết lập</h3>
                    
                    <div class="kh-form-group">
                        <label class="kh-form-label">Trạng thái <span style="color:red">*</span></label>
                        <div class="kh-input-wrapper">
                            <select name="status" class="kh-form-input">
                                <option value="active" <?php echo e(old('status') == 'active' ? 'selected' : ''); ?>>Hiển thị</option>
                                <option value="inactive" <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>>Ẩn</option>
                            </select>
                        </div>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color:red; font-size:0.85rem"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <button type="submit" class="kh-btn-primary" style="width: 100%;">Lưu sản phẩm</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('thumb-preview').src = e.target.result;
                document.getElementById('thumb-preview').style.display = 'block';
                document.getElementById('upload-text').style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\NHUY\Desktop\huykhanhstore\huykhanhstoreapp\resources\views/products/create.blade.php ENDPATH**/ ?>