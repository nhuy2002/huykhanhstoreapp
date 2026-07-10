@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pos-custom.css') }}">

<form action="{{ route('orders.checkout') }}" method="POST" id="pos-form">
    @csrf
    
    <div class="kh-pos-wrapper">
        
        <div class="kh-pos-left">
            <div class="kh-pos-search-box">
                <input type="text" id="search-input" class="kh-pos-search-input" placeholder="Tìm kiếm sản phẩm (Tên, Mã)..." onkeyup="filterProducts()">
            </div>

            <div class="kh-pos-grid" id="product-list">
                </div>
        </div>

        <div class="kh-pos-right">
            
            <div class="kh-cart-header">
                <span>Giỏ hàng</span>
                <span class="kh-badge" id="cart-count-badge" style="background:#e0e7ff; color:#4f46e5; padding:2px 8px; border-radius:4px;">0 món</span>
            </div>

            <div class="kh-cart-items" id="cart-list">
                <div style="margin: auto; color: #94a3b8; font-size: 0.9rem;">Giỏ hàng trống</div>
            </div>

            <div class="kh-cart-footer">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="font-weight: 600; color: #64748b;">Tổng cộng:</span>
                    <span id="cart-total" class="kh-total-price">0 ₫</span>
                </div>

                <div id="hidden-cart-inputs"></div>

                <button type="button" onclick="submitToCheckout()" class="kh-btn-primary kh-btn-checkout">
                    Thanh toán ngay &rarr;
                </button>
            </div>
        </div>

    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const allProducts = @json($products);
    let cart = [];

    // 1. Render Danh sách sản phẩm (Bên trái)
    function renderProducts(products) {
        const container = document.getElementById('product-list');
        container.innerHTML = '';
        
        if(products.length === 0) {
            container.innerHTML = '<p style="grid-column:1/-1; text-align:center; color:#64748b;">Không tìm thấy sản phẩm</p>';
            return;
        }

        products.forEach(p => {
            const imgSrc = p.image ? `/storage/${p.image}` : 'https://via.placeholder.com/150';
            const price = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(p.price);
            
            const html = `
                <div class="kh-pos-item" onclick="addToCart(${p.id})">
                    <img src="${imgSrc}" class="kh-pos-img" loading="lazy">
                    <div class="kh-pos-info">
                        <div class="kh-pos-name">${p.name}</div>
                        <div class="kh-pos-price">${price}</div>
                        <div class="kh-pos-stock">Kho: ${p.quantity}</div>
                    </div>
                </div>`;
            container.innerHTML += html;
        });
    }

    // 2. Thêm vào giỏ
    function addToCart(id) {
        const product = allProducts.find(p => p.id === id);
        const item = cart.find(i => i.id === id);

        if ((item ? item.qty : 0) + 1 > product.quantity) {
            Swal.fire({ icon: 'warning', title: 'Hết hàng', timer: 1000, showConfirmButton: false });
            return;
        }

        if (item) item.qty++;
        else cart.push({ id: product.id, name: product.name, price: product.price, qty: 1, image: product.image });
        
        updateCartUI();
    }

    // 3. Render Giỏ hàng (Cập nhật giao diện bên phải)
    function updateCartUI() {
        const container = document.getElementById('cart-list');
        const hiddenInputs = document.getElementById('hidden-cart-inputs');
        
        container.innerHTML = '';
        hiddenInputs.innerHTML = '';
        let total = 0;
        let count = 0;

        if (cart.length === 0) {
            container.innerHTML = '<div style="margin: auto; color: #94a3b8;">Giỏ hàng trống</div>';
            document.getElementById('cart-total').innerText = '0 ₫';
            return;
        }

        cart.forEach((item, index) => {
            total += item.price * item.qty;
            count += item.qty;
            
            const subtotal = new Intl.NumberFormat('vi-VN').format(item.price * item.qty);
            const imgSrc = item.image ? `/storage/${item.image}` : 'https://via.placeholder.com/50';

            // HTML Giỏ hàng (Tự động đổi kiểu hiển thị nhờ CSS Mobile/Desktop)
            container.innerHTML += `
                <div class="kh-cart-row">
                    <img src="${imgSrc}" style="width:30px; height:30px; object-fit:cover; border-radius:4px; display:block; margin-bottom:5px;">
                    
                    <div class="kh-cart-info-box" style="flex:1;">
                        <div style="font-weight:600; font-size:0.85rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${item.name}</div>
                        <div style="font-size:0.75rem; color:#ef4444; font-weight:700;">${subtotal} đ</div>
                    </div>

                    <div class="kh-cart-qty-control" style="margin-top:5px;">
                        <button type="button" class="kh-qty-btn" onclick="changeQty(${item.id}, -1)">-</button>
                        <span class="kh-qty-val">${item.qty}</span>
                        <button type="button" class="kh-qty-btn" onclick="changeQty(${item.id}, 1)">+</button>
                    </div>
                    
                    <button type="button" onclick="removeItem(${item.id})" style="position:absolute; top:2px; right:2px; border:none; background:none; color:#ef4444; font-size:1.2rem; line-height:1;">&times;</button>
                </div>
            `;

            // Input ẩn gửi sang Checkout
            hiddenInputs.innerHTML += `
                <input type="hidden" name="items[${index}][id]" value="${item.id}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.qty}">
            `;
        });

        const totalFmt = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(total);
        document.getElementById('cart-total').innerText = totalFmt;
        
        // Cập nhật số lượng trên header PC
        const badge = document.getElementById('cart-count-badge');
        if(badge) badge.innerText = count + ' món';
    }

    function changeQty(id, delta) {
        const item = cart.find(i => i.id === id);
        const product = allProducts.find(p => p.id === id);
        if(!item) return;

        const newQty = item.qty + delta;
        if (newQty > product.quantity) {
             Swal.fire({ icon: 'warning', title: 'Giới hạn kho', timer: 1000, showConfirmButton: false }); return;
        }
        if (newQty <= 0) cart = cart.filter(i => i.id !== id);
        else item.qty = newQty;
        updateCartUI();
    }

    function removeItem(id) {
        cart = cart.filter(i => i.id !== id);
        updateCartUI();
    }

    function filterProducts() {
        const keyword = document.getElementById('search-input').value.toLowerCase();
        const filtered = allProducts.filter(p => p.name.toLowerCase().includes(keyword));
        renderProducts(filtered);
    }

    // Nút chuyển trang thanh toán
    function submitToCheckout() {
        if (cart.length === 0) {
            Swal.fire({ icon: 'info', title: 'Giỏ hàng trống', text: 'Vui lòng chọn sản phẩm trước!' });
            return;
        }
        document.getElementById('pos-form').submit();
    }

    // Init
    renderProducts(allProducts);
</script>
@endsection
