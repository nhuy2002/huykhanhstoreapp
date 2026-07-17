

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/product-custom.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/auth-custom.css')); ?>">

<div class="kh-container" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    
    <div class="kh-page-header">
        <h1 class="kh-page-title">Cập nhật sản phẩm</h1>
        <a href="<?php echo e(route('products.index')); ?>" class="kh-btn-icon" title="Quay lại danh sách">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
    </div>

    <form action="<?php echo e(route('products.update', $product->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?> 
        
        <div class="kh-form-layout">
            <div class="kh-col-left">
                <div class="kh-card">
                    <h3 style="margin-top:0; margin-bottom:15px;">Thông tin chung</h3>
                    
                    <div class="kh-form-group">
                        <label class="kh-form-label">Tên sản phẩm</label>
                        <div class="kh-input-wrapper">
                            <input type="text" name="name" 
                                   class="kh-form-input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('name', $product->name)); ?>" 
                                   placeholder="Nhập tên sản phẩm">
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
                            <label class="kh-form-label">Giá bán (VNĐ)</label>
                            <div class="kh-input-wrapper">
                                <input type="number" name="price" 
                                       class="kh-form-input <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       value="<?php echo e(old('price', $product->price)); ?>">
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
                            <label class="kh-form-label">Kho</label>
                            <div class="kh-input-wrapper">
                                <input type="number" name="quantity" 
                                       class="kh-form-input <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       value="<?php echo e(old('quantity', $product->quantity)); ?>">
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
                            
                            <textarea name="description" rows="5" class="kh-form-input" style="height:auto;"><?php echo e(old('description', $product->description)); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="kh-col-right">
                <div class="kh-card">
                    <h3 style="margin-top:0; margin-bottom:15px;">Ảnh đại diện</h3>
                    <div class="kh-image-upload-box" onclick="document.getElementById('thumb-input').click()">
                        <?php
                            $imageSrc = $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/150';
                            // Nếu đang có lỗi validate và user đã chọn ảnh trước đó nhưng sai, có thể xử lý phức tạp hơn, 
                            // nhưng cơ bản hiển thị ảnh cũ là đủ.
                        ?>
                        
                        <img id="thumb-preview" class="kh-preview-img" src="<?php echo e($imageSrc); ?>" alt="Preview" style="display: block;">
                        
                        <span id="upload-text" style="color:#64748b; display: <?php echo e($product->image ? 'none' : 'block'); ?>;">
                            Tải ảnh mới
                        </span>
                        
                        <input type="file" id="thumb-input" name="image" style="display: none;" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div style="color:red; font-size:0.85rem; margin-top:5px"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="kh-card">
                    <h3 style="margin-top:0; margin-bottom:15px;">Thiết lập</h3>
                    <div class="kh-form-group">
                        <label class="kh-form-label">Trạng thái</label>
                        <div class="kh-input-wrapper">
                            <select name="status" class="kh-form-input">
                                <option value="active" <?php echo e(old('status', $product->status) == 'active' ? 'selected' : ''); ?>>Hiển thị</option>
                                <option value="inactive" <?php echo e(old('status', $product->status) == 'inactive' ? 'selected' : ''); ?>>Ẩn</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="kh-btn-edit-product" style="width: 100%;">Cập nhật thay đổi</button>
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
                // Ẩn chữ hướng dẫn khi đã chọn ảnh
                const textEl = document.getElementById('upload-text');
                if(textEl) textEl.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\NHUY\Desktop\huykhanhstore\huykhanhstoreapp\resources\views/products/edit.blade.php ENDPATH**/ ?>