@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/product-custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/auth-custom.css') }}">

<div class="kh-container" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    
    <div class="kh-page-header">
        <h1 class="kh-page-title">Cập nhật sản phẩm</h1>
        <a href="{{ route('products.index') }}" class="kh-btn-icon" title="Quay lại danh sách">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
    </div>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') 
        
        <div class="kh-form-layout">
            <div class="kh-col-left">
                <div class="kh-card">
                    <h3 style="margin-top:0; margin-bottom:15px;">Thông tin chung</h3>
                    
                    <div class="kh-form-group">
                        <label class="kh-form-label">Tên sản phẩm</label>
                        <div class="kh-input-wrapper">
                            <input type="text" name="name" 
                                   class="kh-form-input @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $product->name) }}" 
                                   placeholder="Nhập tên sản phẩm">
                        </div>
                        @error('name') <span style="color:red; font-size:0.85rem">{{ $message }}</span> @enderror
                    </div>

                    <div class="kh-form-row">
                        <div class="kh-form-group">
                            <label class="kh-form-label">Giá bán (VNĐ)</label>
                            <div class="kh-input-wrapper">
                                <input type="number" name="price" 
                                       class="kh-form-input @error('price') is-invalid @enderror" 
                                       value="{{ old('price', $product->price) }}">
                            </div>
                            @error('price') <span style="color:red; font-size:0.85rem">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="kh-form-group">
                            <label class="kh-form-label">Kho</label>
                            <div class="kh-input-wrapper">
                                <input type="number" name="quantity" 
                                       class="kh-form-input @error('quantity') is-invalid @enderror" 
                                       value="{{ old('quantity', $product->quantity) }}">
                            </div>
                            @error('quantity') <span style="color:red; font-size:0.85rem">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="kh-form-group">
                        <label class="kh-form-label">Mô tả</label>
                        <div class="kh-input-wrapper">
                            {{-- Lưu ý: Textarea giá trị nằm giữa 2 thẻ, không phải thuộc tính value --}}
                            <textarea name="description" rows="5" class="kh-form-input" style="height:auto;">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="kh-col-right">
                <div class="kh-card">
                    <h3 style="margin-top:0; margin-bottom:15px;">Ảnh đại diện</h3>
                    <div class="kh-image-upload-box" onclick="document.getElementById('thumb-input').click()">
                        @php
                            $imageSrc = $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/150';
                            // Nếu đang có lỗi validate và user đã chọn ảnh trước đó nhưng sai, có thể xử lý phức tạp hơn, 
                            // nhưng cơ bản hiển thị ảnh cũ là đủ.
                        @endphp
                        
                        <img id="thumb-preview" class="kh-preview-img" src="{{ $imageSrc }}" alt="Preview" style="display: block;">
                        
                        <span id="upload-text" style="color:#64748b; display: {{ $product->image ? 'none' : 'block' }};">
                            Tải ảnh mới
                        </span>
                        
                        <input type="file" id="thumb-input" name="image" style="display: none;" accept="image/*" onchange="previewImage(this)">
                    </div>
                    @error('image') <div style="color:red; font-size:0.85rem; margin-top:5px">{{ $message }}</div> @enderror
                </div>

                <div class="kh-card">
                    <h3 style="margin-top:0; margin-bottom:15px;">Thiết lập</h3>
                    <div class="kh-form-group">
                        <label class="kh-form-label">Trạng thái</label>
                        <div class="kh-input-wrapper">
                            <select name="status" class="kh-form-input">
                                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Hiển thị</option>
                                <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Ẩn</option>
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
@endsection