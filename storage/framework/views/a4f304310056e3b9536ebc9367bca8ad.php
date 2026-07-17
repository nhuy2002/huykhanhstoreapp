

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/checkout-custom.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/product-custom.css')); ?>">

<style>
    /* CSS cho phần Camera */
    .kh-proof-area { margin-top: 20px; border-top: 1px dashed #e2e8f0; padding-top: 15px; }
    .kh-proof-box {
        border: 2px dashed #cbd5e1; background: #f8fafc; border-radius: 8px;
        padding: 15px; text-align: center; position: relative; transition: 0.2s;
    }
    .kh-proof-box:hover { border-color: #4f46e5; background: #f1f5f9; }
    .kh-proof-preview {
        width: 100%; height: 200px; object-fit: contain; display: none; 
        border-radius: 6px; margin-bottom: 10px; border: 1px solid #e2e8f0; background: #fff;
    }
    .kh-btn-cam {
        background: #334155; color: white; border: none; padding: 10px 20px;
        border-radius: 6px; cursor: pointer; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px; font-weight: 600;
    }
    .kh-btn-cam:hover { background: #1e293b; }
    
    /* Modal Camera Desktop */
    #cam-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999; align-items: center; justify-content: center; }
    #cam-modal.active { display: flex; }
    .cam-content { background: #000; padding: 10px; border-radius: 12px; position: relative; width: 100%; max-width: 640px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
    video { width: 100%; border-radius: 8px; transform: scaleX(-1); } /* Lật gương cho giống soi gương */
    .cam-controls { display: flex; justify-content: center; gap: 20px; margin-top: 15px; padding-bottom: 10px; }
    .btn-capture { width: 60px; height: 60px; border-radius: 50%; background: #ef4444; border: 4px solid #fff; cursor: pointer; transition: 0.2s; }
    .btn-capture:active { transform: scale(0.9); }
    .btn-close-cam { background: transparent; border: 1px solid #666; color: #ccc; padding: 0 20px; border-radius: 20px; cursor: pointer; }
</style>

<div class="kh-container">
    <div class="ck-page-header">
        <a href="<?php echo e(route('orders.create')); ?>" class="kh-btn-icon" title="Quay lại chọn món">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <h1 class="kh-page-title" style="font-size: 1.3rem; margin:0;">Xác nhận đơn hàng</h1>
    </div>

    <form action="<?php echo e(route('orders.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="kh-checkout-wrapper">
            
            <div class="kh-checkout-left">
                <div class="kh-card">
                    <h3 class="kh-card-header">Thông tin khách hàng</h3>
                    <div class="kh-form-group">
                        <label class="kh-form-label">Tên khách hàng <span style="color:red">*</span></label>
                        <input type="text" name="customer_name" class="kh-form-input" placeholder="Nhập tên khách..." required>
                    </div>
                    <div class="kh-form-group">
                        <label class="kh-form-label">Số điện thoại <span style="color:red">*</span></label>
                        <input type="tel" name="customer_phone" class="kh-form-input" placeholder="038..." required>
                    </div>
                    <div class="kh-form-group">
                        <label class="kh-form-label">Ghi chú đơn hàng</label>
                        <input type="text" name="note" class="kh-form-input" placeholder="Giao giờ hành chính...">
                    </div>
                </div>

                <div class="kh-card">
                    <h3 class="kh-card-header">Thanh toán & Xác thực</h3>
                    <div class="kh-form-group">
                        <label class="kh-form-label">Phương thức thanh toán</label>
                        <select name="payment_method" id="payment_method" class="kh-form-input" onchange="togglePaymentMethod()">
                            <option value="cash">Tiền mặt</option>
                            <option value="transfer">Chuyển khoản (VietQR)</option>
                        </select>
                    </div>

                    <div id="qr-container" class="kh-qr-box" style="display: none;">
                        <div style="font-weight:700; color:#15803d; margin-bottom:10px;">Quét mã QR để thanh toán</div>
                        <img id="vietqr-img" src="<?php echo e($qrUrl ?? ''); ?>" style="width:200px; height:200px; object-fit:contain; background:white;">
                        <div id="qr-note" style="margin-top:10px; font-size:0.9rem; color:#166534; font-weight: 600;">Nội dung: <?php echo e($qrContent ?? '...'); ?></div>
                        <div style="font-size: 0.8rem; color: #64748b; margin-top: 5px;">(Vui lòng không sửa nội dung)</div>
                    </div>

                    <div class="kh-proof-area">
                        <label class="kh-form-label" id="proof-label" style="display:block; margin-bottom:8px; color: #4f46e5; font-weight: 700;">
                            Hình ảnh nhận tiền mặt
                        </label>
                        
                        <div class="kh-proof-box">
                            <img id="proof-preview" class="kh-proof-preview">
                            <input type="file" name="payment_proof" id="proof-input" accept="image/*" style="display: none;" onchange="previewFile()">

                            <div id="proof-actions" style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                                <button type="button" class="kh-btn-cam" onclick="startCamera()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    Chụp ảnh ngay
                                </button>
                                
                                <span style="font-size: 0.85rem; color: #64748b;">hoặc <a href="#" onclick="document.getElementById('proof-input').click(); return false;" style="color: #4f46e5;">tải ảnh lên từ thư viện</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="kh-checkout-right">
                <div class="kh-summary-header">Đơn hàng (<?php echo e(count($cartItems)); ?> món)</div>
                <div class="kh-summary-body">
                    <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="kh-item-row" onclick="openProductModal(<?php echo e($item['id']); ?>)" style="cursor: pointer;">
                            <img src="<?php echo e($item['image'] ? asset('storage/' . $item['image']) : 'https://via.placeholder.com/50'); ?>" class="kh-item-img">
                            <div class="kh-item-info">
                                <div class="kh-item-name"><?php echo e($item['name']); ?></div>
                                <div class="kh-item-meta">SL: <?php echo e($item['quantity']); ?></div>
                            </div>
                            <div class="kh-item-price"><?php echo e(number_format($item['subtotal'], 0, ',', '.')); ?> đ</div>
                            <input type="hidden" name="items[<?php echo e($index); ?>][id]" value="<?php echo e($item['id']); ?>">
                            <input type="hidden" name="items[<?php echo e($index); ?>][quantity]" value="<?php echo e($item['quantity']); ?>">
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="kh-summary-footer">
                    <div class="kh-total-row">
                        <span class="kh-total-label">Tổng cộng:</span>
                        <span class="kh-total-value"><?php echo e(number_format($totalAmount, 0, ',', '.')); ?> đ</span>
                    </div>
                    <div class="kh-buttons-row">
                        <a href="<?php echo e(route('orders.create')); ?>" class="kh-btn-back">Trở về</a>
                        <button type="submit" class="kh-btn-confirm">Hoàn tất đơn hàng</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="product-modal" class="kh-modal-overlay">
    <div class="kh-modal-container">
        <div class="kh-modal-header"><h3 class="kh-page-title">Chi tiết sản phẩm</h3><button type="button" class="kh-modal-close" onclick="closeProductModal()">&times;</button></div>
        <div class="kh-modal-body">
            <div class="kh-modal-left"><img id="modal-img" src="" class="kh-detail-img"></div>
            <div class="kh-modal-right">
                <div class="kh-detail-label">Tên sản phẩm</div><div id="modal-name" class="kh-detail-value">--</div>
                <div class="kh-detail-label">Giá bán</div><div id="modal-price" class="kh-detail-value">--</div>
                <div class="kh-detail-label">Mô tả</div><div id="modal-desc" class="kh-detail-value">--</div>
            </div>
        </div>
    </div>
</div>

<div id="cam-modal">
    <div class="cam-content">
        <div style="position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.5); color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">Camera trực tiếp</div>
        <video id="webcam" autoplay playsinline></video>
        <canvas id="canvas" style="display:none;"></canvas>
        <div class="cam-controls">
            <button type="button" class="btn-close-cam" onclick="closeCamera()">Đóng</button>
            <div class="btn-capture" onclick="takeSnapshot()"></div>
            <div style="width: 70px;"></div> </div>
    </div>
</div>

<script>
    // 1. Logic Thay đổi Label theo Phương thức thanh toán
    function togglePaymentMethod() {
        const method = document.getElementById('payment_method').value;
        const qrContainer = document.getElementById('qr-container');
        const proofLabel = document.getElementById('proof-label');
        
        const hasQr = "<?php echo e($qrUrl ? 'yes' : 'no'); ?>";
        
        if (method === 'transfer' && hasQr === 'yes') {
            qrContainer.style.display = 'block';
            proofLabel.innerText = "Bằng chứng chuyển khoản (Ảnh Bill)";
        } else {
            qrContainer.style.display = 'none';
            proofLabel.innerText = "Hình ảnh nhận tiền mặt";
        }
    }

    // 2. Xem trước ảnh sau khi chọn/chụp
    function previewFile() {
        const fileInput = document.getElementById('proof-input');
        const file = fileInput.files[0];
        const preview = document.getElementById('proof-preview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }

    // --- 3. LOGIC CAMERA THÔNG MINH ---
    let stream = null;

    function startCamera() {
        // KIỂM TRA 1: Nếu là Điện thoại -> Gọi Camera gốc (Native)
        // Cách này ổn định nhất trên iOS và Android
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        
        if (isMobile) {
            const input = document.getElementById('proof-input');
            // capture="environment" nghĩa là ưu tiên camera sau
            input.setAttribute('capture', 'environment'); 
            input.click(); 
            return; 
        }

        // KIỂM TRA 2: Nếu là Máy tính (PC/Laptop) -> Mở Webcam Modal
        // Yêu cầu: Phải chạy trên localhost hoặc HTTPS
        if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
            alert("Lỗi Bảo Mật: Camera chỉ hoạt động trên HTTPS hoặc Localhost.\n\nNếu bạn đang test trên điện thoại qua IP LAN, hãy dùng Ngrok để có link HTTPS.");
            return;
        }

        const modal = document.getElementById('cam-modal');
        const video = document.getElementById('webcam');

        // Hiển thị modal trước
        modal.classList.add('active');

        // Xin quyền truy cập Camera
        navigator.mediaDevices.getUserMedia({ video: { width: 1280, height: 720 } })
            .then(mediaStream => {
                stream = mediaStream;
                video.srcObject = stream;
            })
            .catch(err => {
                console.error("Camera Error:", err);
                modal.classList.remove('active');
                
                if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                    alert("Bạn đã chặn quyền truy cập Camera. Vui lòng bấm vào biểu tượng ổ khóa trên thanh địa chỉ để mở lại.");
                } else if (err.name === 'NotFoundError') {
                    alert("Không tìm thấy Camera trên thiết bị này.");
                } else {
                    alert("Không thể mở Camera: " + err.message);
                }
            });
    }

    // Tắt Camera
    function closeCamera() {
        document.getElementById('cam-modal').classList.remove('active');
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    }

    // Chụp ảnh từ Webcam (PC)
    function takeSnapshot() {
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');

        if (!video.videoWidth) return; // Chưa load xong video

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        // Vẽ ảnh (Lật ngược lại vì webcam bị ngược gương)
        context.translate(canvas.width, 0);
        context.scale(-1, 1);
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Chuyển thành File object
        canvas.toBlob(function(blob) {
            const file = new File([blob], "img_captured_" + Date.now() + ".jpg", { type: "image/jpeg" });
            
            // Gán vào input file ẩn
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            document.getElementById('proof-input').files = dataTransfer.files;

            // Hiện preview và tắt cam
            previewFile();
            closeCamera();
        }, 'image/jpeg', 0.9);
    }

    // Modal Sản phẩm (Giữ nguyên logic cũ)
    const productsData = <?php echo json_encode($products->keyBy('id'), 15, 512) ?>; 
    function openProductModal(id) {
        const data = productsData[id];
        if(!data) return;
        document.getElementById('modal-img').src = data.image ? "<?php echo e(asset('storage/')); ?>/" + data.image : 'https://via.placeholder.com/400x300';
        document.getElementById('modal-name').textContent = data.name;
        document.getElementById('modal-price').textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(data.price);
        document.getElementById('modal-desc').textContent = data.description || 'Chưa có mô tả';
        document.getElementById('product-modal').classList.add('active');
    }
    function closeProductModal() { document.getElementById('product-modal').classList.remove('active'); }

    // Init
    document.addEventListener("DOMContentLoaded", function() { togglePaymentMethod(); });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Project\huykhanhstoreapp\resources\views/orders/checkout.blade.php ENDPATH**/ ?>