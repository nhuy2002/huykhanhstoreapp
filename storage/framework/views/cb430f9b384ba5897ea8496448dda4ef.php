

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/product-custom.css')); ?>"> 
<link rel="stylesheet" href="<?php echo e(asset('css/order-custom.css')); ?>">   
<link rel="stylesheet" href="<?php echo e(asset('css/pagination.css')); ?>">

<div class="kh-container" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    
    <div class="kh-page-header">
        <h1 class="kh-page-title">Quản lý đơn hàng</h1>

        <form action="<?php echo e(route('orders.index')); ?>" method="GET" class="kh-search-form">
            <input type="text" name="search" class="kh-search-input" 
                   placeholder="Tìm mã đơn, tên khách, sđt..." 
                   value="<?php echo e(request('search')); ?>">
            <button type="submit" class="kh-search-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </button>
        </form>

        <a href="<?php echo e(route('orders.create')); ?>" class="kh-btn-icon primary" title="Tạo đơn mới">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        </a>
    </div>

    <div class="kh-table-wrapper">
        <table class="kh-table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th style="text-align: right;">Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr onclick="openOrderModal(<?php echo e($order->id); ?>)" style="cursor: pointer;">
                    <td class="kh-table-code"><?php echo e($order->code); ?></td>
                    <td>
                        <div style="font-weight: 500;"><?php echo e($order->customer_name); ?></div>
                        <div style="font-size: 0.8rem; color: #64748b;"><?php echo e($order->customer_phone); ?></div>
                    </td>
                    <td class="kh-table-money"><?php echo e(number_format($order->total_amount, 0, ',', '.')); ?> đ</td>
                    <td>
                        <?php
                            $statusClass = match($order->status) {
                                'completed' => 'kh-badge-completed',
                                'cancelled' => 'kh-badge-cancelled',
                                'shipping' => 'kh-badge-shipping',
                                default => 'kh-badge-pending',
                            };
                            $statusText = match($order->status) {
                                'completed' => 'Hoàn thành',
                                'cancelled' => 'Đã hủy',
                                'shipping' => 'Đang giao',
                                default => 'Chờ xử lý',
                            };
                        ?>
                        <span class="kh-badge <?php echo e($statusClass); ?>"><?php echo e($statusText); ?></span>
                    </td>
                    <td style="color: #64748b; font-size: 0.9rem;">
                        <?php echo e($order->created_at->format('d/m/Y H:i')); ?>

                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; gap: 5px; justify-content: flex-end;">
                            <a href="<?php echo e(route('orders.edit', $order->id)); ?>" class="kh-btn-icon" title="Chỉnh sửa" style="color: #f59e0b;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            <button class="kh-btn-icon" onclick="openOrderModal(<?php echo e($order->id); ?>)" title="Chi tiết">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                            <button class="kh-btn-icon" onclick="confirmDelete(<?php echo e($order->id); ?>, '<?php echo e($order->code); ?>')" title="Xóa" style="color: #ef4444;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #64748b;">Không tìm thấy đơn hàng nào.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="kh-mobile-list">
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="kh-order-mobile-card">
            <div class="kh-ord-mob-header">
                <span class="kh-ord-mob-code"><?php echo e($order->code); ?></span>
                <span class="kh-ord-mob-date"><?php echo e($order->created_at->format('d/m/Y H:i')); ?></span>
            </div>

            <div class="kh-ord-mob-row">
                <span class="kh-ord-mob-label">Khách hàng:</span>
                <span class="kh-ord-mob-val"><?php echo e($order->customer_name); ?></span>
            </div>

            <div class="kh-ord-mob-row">
                <span class="kh-ord-mob-label">Tổng tiền:</span>
                <span class="kh-ord-mob-val" style="color: #ef4444;"><?php echo e(number_format($order->total_amount, 0, ',', '.')); ?> đ</span>
            </div>

            <div class="kh-ord-mob-row" style="margin-top: 8px;">
                <?php
                    $statusClass = match($order->status) {
                        'completed' => 'kh-badge-completed', 'cancelled' => 'kh-badge-cancelled',
                        'shipping' => 'kh-badge-shipping', default => 'kh-badge-pending',
                    };
                    $statusText = match($order->status) {
                        'completed' => 'Hoàn thành', 'cancelled' => 'Đã hủy',
                        'shipping' => 'Đang giao', default => 'Chờ xử lý',
                    };
                ?>
                <span class="kh-badge <?php echo e($statusClass); ?>"><?php echo e($statusText); ?></span>
            </div>

            <div class="kh-ord-mob-actions" style="margin-top: 15px; display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                <button class="kh-btn-mobile" onclick="openOrderModal(<?php echo e($order->id); ?>)" style="background: #3b82f6; color: white; padding: 8px 12px; border-radius: 6px; border: none; font-size: 12px; font-weight: 500; display: flex; align-items: center; gap: 5px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    Xem
                </button>
                <a href="<?php echo e(route('orders.edit', $order->id)); ?>" class="kh-btn-mobile" style="background: #f59e0b; color: white; padding: 8px 12px; border-radius: 6px; border: none; font-size: 12px; font-weight: 500; display: flex; align-items: center; gap: 5px; text-decoration: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    Sửa
                </a>
                <button class="kh-btn-mobile" onclick="confirmDelete(<?php echo e($order->id); ?>, '<?php echo e($order->code); ?>')" style="background: #ef4444; color: white; padding: 8px 12px; border-radius: 6px; border: none; font-size: 12px; font-weight: 500; display: flex; align-items: center; gap: 5px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Xóa
                </button>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div style="margin-top: 20px;">
        <?php echo e($orders->links('components.pagination')); ?>

    </div>
</div>

<div id="order-modal" class="kh-modal-overlay">
    <div class="kh-modal-container" style="max-width: 900px;">
        <div class="kh-modal-header">
            <h3 class="kh-page-title" style="font-size: 1.2rem;">Chi tiết đơn hàng <span id="modal-code" style="color: #4f46e5;"></span></h3>
            <button type="button" class="kh-modal-close" onclick="closeOrderModal()">&times;</button>
        </div>
        
        <div class="kh-order-modal-body">
            <div class="kh-order-left">
                <div class="kh-info-section">
                    <div class="kh-info-title">Thông tin khách hàng</div>
                    <div class="kh-info-row">
                        <span class="kh-info-label">Họ tên:</span>
                        <span class="kh-info-content" id="modal-cust-name">--</span>
                    </div>
                    <div class="kh-info-row">
                        <span class="kh-info-label">Điện thoại:</span>
                        <span class="kh-info-content" id="modal-cust-phone">--</span>
                    </div>
                    <div class="kh-info-row">
                        <span class="kh-info-label">Ngày tạo:</span>
                        <span class="kh-info-content" id="modal-date">--</span>
                    </div>
                    <div class="kh-info-row">
                        <span class="kh-info-label">Thanh toán:</span>
                        <span class="kh-info-content" id="modal-payment">--</span>
                    </div>
                    <div class="kh-info-row" style="margin-top: 10px;">
                        <span class="kh-info-label">Ghi chú:</span>
                        <span class="kh-info-content" id="modal-note" style="font-style: italic; color: #64748b;">--</span>
                    </div>
                </div>

                <div class="kh-info-section" style="margin-top: 15px; border-top: 1px dashed #e2e8f0; padding-top: 15px;">
                    <div class="kh-info-title" style="margin-bottom: 10px;">Bằng chứng thanh toán</div>
                    
                    <div id="modal-proof-container" style="display: none; text-align: center;">
                        <a id="modal-proof-link" href="#" target="_blank">
                            <img id="modal-proof-img" src="" style="max-width: 100%; max-height: 250px; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        </a>
                        <div style="font-size: 0.8rem; color: #64748b; margin-top: 5px;">(Bấm vào ảnh để xem kích thước gốc)</div>
                    </div>

                    <div id="modal-no-proof" style="display: none; color: #94a3b8; font-style: italic; font-size: 0.9rem; text-align: center;">
                        Không có hình ảnh xác thực
                    </div>
                </div>
            </div>

            <div class="kh-order-right">
                <div class="kh-info-section" style="background: #fff; height: 100%;">
                    <div class="kh-info-title">Sản phẩm mua</div>
                    <table class="kh-modal-items-table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th style="text-align: center;">SL</th>
                                <th style="text-align: right;">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody id="modal-items-list">
                            </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="text-align: right; padding-top: 15px; font-weight: 600;">Tổng cộng:</td>
                                <td style="text-align: right; padding-top: 15px; font-weight: 800; color: #ef4444; font-size: 1.1rem;" id="modal-total">0 đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const ordersData = <?php echo json_encode($orders->items(), 15, 512) ?>; 
    const ordersMap = {};
    ordersData.forEach(order => { ordersMap[order.id] = order; });

    function openOrderModal(id) {
        const order = ordersMap[id];
        if(!order) return;

        // 1. Điền thông tin chung
        document.getElementById('modal-code').innerText = order.code;
        document.getElementById('modal-cust-name').innerText = order.customer_name;
        document.getElementById('modal-cust-phone').innerText = order.customer_phone;
        document.getElementById('modal-note').innerText = order.note || 'Không có ghi chú';
        
        const date = new Date(order.created_at);
        document.getElementById('modal-date').innerText = date.toLocaleDateString('vi-VN') + ' ' + date.toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'});
        
        const paymentMap = { 'cash': 'Tiền mặt', 'transfer': 'Chuyển khoản' };
        document.getElementById('modal-payment').innerText = paymentMap[order.payment_method] || order.payment_method;

        // --- 2. XỬ LÝ ẢNH BẰNG CHỨNG ---
        const proofContainer = document.getElementById('modal-proof-container');
        const noProof = document.getElementById('modal-no-proof');
        const proofImg = document.getElementById('modal-proof-img');
        const proofLink = document.getElementById('modal-proof-link');
        const storageBase = "<?php echo e(asset('storage/')); ?>"; // Lấy URL gốc storage

        if (order.payment_proof) {
            const fullUrl = storageBase + '/' + order.payment_proof;
            proofImg.src = fullUrl;
            proofLink.href = fullUrl; // Cho phép bấm vào để mở tab mới
            proofContainer.style.display = 'block';
            noProof.style.display = 'none';
        } else {
            proofContainer.style.display = 'none';
            noProof.style.display = 'block';
        }

        // 3. Render danh sách sản phẩm
        const listContainer = document.getElementById('modal-items-list');
        listContainer.innerHTML = ''; 

        let total = 0;
        
        order.items.forEach(item => {
            const price = new Intl.NumberFormat('vi-VN').format(item.price);
            const subtotal = new Intl.NumberFormat('vi-VN').format(item.subtotal);
            const imgSrc = item.product_image ? (storageBase + '/' + item.product_image) : 'https://via.placeholder.com/40';

            const html = `
                <tr>
                    <td>
                        <img src="${imgSrc}" class="kh-item-thumb">
                        <span>${item.product_name}</span>
                        <div style="font-size: 0.75rem; color: #94a3b8;">${price} đ</div>
                    </td>
                    <td style="text-align: center; font-weight: 600;">${item.quantity}</td>
                    <td style="text-align: right; font-weight: 600;">${subtotal} đ</td>
                </tr>
            `;
            listContainer.innerHTML += html;
        });

        document.getElementById('modal-total').innerText = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(order.total_amount);

        document.getElementById('order-modal').classList.add('active');
    }

    function closeOrderModal() {
        document.getElementById('order-modal').classList.remove('active');
    }

    document.getElementById('order-modal').addEventListener('click', function(e) {
        if(e.target === this) closeOrderModal();
    });

    function confirmDelete(orderId, orderCode) {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: `Bạn có chắc muốn xóa đơn hàng "${orderCode}"? Hành động này không thể hoàn tác!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tạo form ẩn và submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/orders/${orderId}`;

                // CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                // Method spoofing for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

<!-- Hidden delete form template (for reference) -->
<form id="delete-form-template" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Project\huykhanhstoreapp\resources\views/orders/index.blade.php ENDPATH**/ ?>