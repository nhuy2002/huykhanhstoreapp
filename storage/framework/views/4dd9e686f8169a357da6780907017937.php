

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/product-custom.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/pagination.css')); ?>">

<div class="kh-container" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    
    <div class="kh-page-header">
    <h1 class="kh-page-title">Danh sách sản phẩm</h1>

    <form action="<?php echo e(route('products.index')); ?>" method="GET" class="kh-search-form">
        <input type="text" 
               name="search" 
               class="kh-search-input" 
               placeholder="Tìm kiếm theo tên sản phẩm..." 
               value="<?php echo e(request('search')); ?>"> <button type="submit" class="kh-search-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    </form>

    <a href="<?php echo e(route('products.create')); ?>" class="kh-btn-icon primary" title="Thêm sản phẩm mới">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </a>
</div>

    <div class="kh-table-wrapper">
        <table class="kh-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá bán</th>
                    <th>Kho / Đã bán</th>
                    <th>Trạng thái</th>
                    <th style="text-align: right;">Hành động</th>
                </tr>
            </thead>
            <tbody>
    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr class="product-row" onclick="openProductModal(<?php echo e($product->id); ?>)">
        <td>
            <?php if($product->image): ?>
                <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" class="kh-product-thumb">
            <?php else: ?>
                <img src="https://via.placeholder.com/50" class="kh-product-thumb">
            <?php endif; ?>
        </td>
        <td>
            <strong><?php echo e($product->name); ?></strong><br>
            <small style="color: #94a3b8;">ID: #<?php echo e($product->id); ?></small>
        </td>
        <td style="color: #ef4444; font-weight: bold;"><?php echo e(number_format($product->price, 0, ',', '.')); ?> đ</td>
        <td>
            <div>Kho: <strong><?php echo e($product->quantity); ?></strong></div>
            <small>Bán: <?php echo e($product->sold); ?></small>
        </td>
        <td>
            <?php if($product->status == 'active'): ?>
                <span class="kh-badge kh-badge-success">Hiển thị</span>
            <?php else: ?>
                <span class="kh-badge kh-badge-warning">Ẩn</span>
            <?php endif; ?>
        </td>
        <td style="text-align: right;" onclick="event.stopPropagation()">
            <div class="kh-action-group">
                <a href="<?php echo e(route('products.edit', $product->id)); ?>" class="kh-action-btn kh-btn-edit">Sửa</a>
                
                <form action="<?php echo e(route('products.destroy', $product->id)); ?>" method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="button" class="kh-action-btn kh-btn-delete" onclick="deleteProduct(event, <?php echo e($product->id); ?>)">Xóa</button>
                </form>
            </div>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
        </table>
    </div>

<div class="kh-mobile-list">
    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="kh-product-mobile-card" onclick="openProductModal(<?php echo e($product->id); ?>)">

            
            <?php if($product->image): ?>
                <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" class="kh-product-thumb">
            <?php else: ?>
                <img src="https://via.placeholder.com/50" class="kh-product-thumb">
            <?php endif; ?>

            
            <div class="kh-mob-info">
                <span class="kh-mob-name"><?php echo e($product->name); ?></span>

                <div class="kh-mob-meta">
                    Giá: <span style="color:#ef4444; font-weight:500;">
                        <?php echo e(number_format($product->price, 0, ',', '.')); ?> đ
                    </span>
                </div>

                <div class="kh-mob-meta">
                    Kho: <?php echo e($product->quantity); ?> | Bán: <?php echo e($product->sold); ?>

                </div>

                <div style="margin-top: 5px;">
                     <?php if($product->status == 'active'): ?>
                        <span style="color: green; font-size: 0.8rem;">● Hiển thị</span>
                    <?php else: ?>
                        <span style="color: gray; font-size: 0.8rem;">● Ẩn</span>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="kh-action-group" onclick="event.stopPropagation()">
                <a href="<?php echo e(route('products.edit', $product->id)); ?>" class="kh-action-btn kh-btn-edit">Sửa</a>

                <form action="<?php echo e(route('products.destroy', $product->id)); ?>" method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="button" class="kh-action-btn kh-btn-delete" onclick="deleteProduct(event, <?php echo e($product->id); ?>)">Xóa</button>
                </form>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

</div>

<div id="product-modal" class="kh-modal-overlay">
    <div class="kh-modal-container">
        <div class="kh-modal-header">
            <h3 class="kh-page-title" style="font-size: 1.2rem;">Chi tiết sản phẩm</h3>
            <button class="kh-modal-close" onclick="closeProductModal()">&times;</button>
        </div>
        <div class="kh-modal-body">
            <div class="kh-modal-left">
                <img id="modal-img" src="" alt="Product Detail" class="kh-detail-img">
            </div>
            <div class="kh-modal-right">
                <div class="kh-detail-label">Tên sản phẩm</div>
                <div id="modal-name" class="kh-detail-value">--</div>

                <div class="kh-detail-label">Mã sản phẩm</div>
                <div id="modal-code" class="kh-detail-value">--</div>

                <div style="display: flex; gap: 20px;">
                    <div>
                        <div class="kh-detail-label">Giá bán</div>
                        <div id="modal-price" class="kh-detail-value" style="color: #ef4444;">--</div>
                    </div>
                    <div>
                        <div class="kh-detail-label">Tồn kho</div>
                        <div id="modal-stock" class="kh-detail-value">--</div>
                    </div>
                </div>

                <div class="kh-detail-label">Mô tả</div>
                <div id="modal-desc" class="kh-detail-value" style="font-weight: 400; font-size: 0.95rem; line-height: 1.5;">
                    --
                </div>

                <div style="margin-top: 20px;">
                    <a href="#" id="modal-btn-edit" class="kh-btn-primary" style="display: inline-block;">Chỉnh sửa ngay</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Chuyển dữ liệu PHP $products sang JSON JS
    const products = <?php echo json_encode($products->keyBy('id'), 15, 512) ?>; 

    // Hàm mở Modal
    function openProductModal(id) {
        const data = products[id];
        if(!data) return;

        // Xử lý đường dẫn ảnh
        const storagePath = "<?php echo e(asset('storage/')); ?>";
        const imgUrl = data.image ? (storagePath + '/' + data.image) : 'https://via.placeholder.com/400x300';

        document.getElementById('modal-img').src = imgUrl;
        document.getElementById('modal-name').textContent = data.name;
        document.getElementById('modal-code').textContent = "#" + data.id;
        
        // Format tiền tệ
        const priceFormatted = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(data.price);
        document.getElementById('modal-price').textContent = priceFormatted;
        
        document.getElementById('modal-stock').textContent = data.quantity;
        document.getElementById('modal-desc').textContent = data.description || 'Chưa có mô tả';
        
        // Link nút sửa
        const editUrl = "<?php echo e(route('products.edit', ':id')); ?>";
        document.getElementById('modal-btn-edit').href = editUrl.replace(':id', data.id);

        // Thêm class active để hiện modal
        document.getElementById('product-modal').classList.add('active');
    }
    
    // Hàm đóng Modal
    function closeProductModal() {
        document.getElementById('product-modal').classList.remove('active');
    }

    // Sự kiện đóng khi click ra ngoài vùng trắng (Overlay)
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('product-modal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeProductModal();
                }
            });
        }
    });

    // Hàm xóa sản phẩm với SweetAlert2
    function deleteProduct(event, productId) {
        event.preventDefault();
        Swal.fire({
            title: 'Bạn chắc chắn muốn xóa?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.closest('form').submit();
            }
        });
    }
</script>
<div style="margin-top: 20px;">
    <?php echo e($products->links('components.pagination')); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\NHUY\Desktop\huykhanhstore\huykhanhstoreapp\resources\views/products/index.blade.php ENDPATH**/ ?>