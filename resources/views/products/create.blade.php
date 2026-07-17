@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/product-custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/auth-custom.css') }}">

<div class="kh-container" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    
    <div class="kh-page-header">
        <h1 class="kh-page-title">Thêm sản phẩm mới</h1>
        <a href="{{ route('products.index') }}" class="kh-btn-icon" title="Quay lại danh sách">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
    </div>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="kh-form-layout">
            <div class="kh-col-left">
                <div class="kh-card">
                    <h3 style="margin-top:0; margin-bottom:15px;">Thông tin chung</h3>
                    <div class="kh-form-group">
                        <label class="kh-form-label">Tên sản phẩm <span style="color:red">*</span></label>
                        <div class="kh-input-wrapper">
                            <input type="text" name="name" class="kh-form-input @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ví dụ: Laptop Dell XPS 13">
                        </div>
                        @error('name') <span style="color:red; font-size:0.85rem">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="kh-form-row">
                        <div class="kh-form-group">
                            <label class="kh-form-label">Giá bán <span style="color:red">*</span></label>
                            <div class="kh-input-wrapper">
                                <input type="number" name="price" class="kh-form-input @error('price') is-invalid @enderror" value="{{ old('price') }}">
                            </div>
                            @error('price') <span style="color:red; font-size:0.85rem">{{ $message }}</span> @enderror
                        </div>
                        <div class="kh-form-group">
                            <label class="kh-form-label">Kho <span style="color:red">*</span></label>
                            <div class="kh-input-wrapper">
                                <input type="number" name="quantity" class="kh-form-input @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}">
                            </div>
                            @error('quantity') <span style="color:red; font-size:0.85rem">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="kh-form-group">
                        <label class="kh-form-label">Mô tả</label>
                        <div class="kh-input-wrapper">
                            <textarea name="description" rows="5" class="kh-form-input" style="height:auto;">{{ old('description') }}</textarea>
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
                    @error('image') <span style="color:red; font-size:0.85rem">{{ $message }}</span> @enderror
                </div>

                <div class="kh-card">
                    <h3 style="margin-top:0; margin-bottom:15px;">Thiết lập</h3>
                    
                    <div class="kh-form-group">
                        <label class="kh-form-label">Trạng thái <span style="color:red">*</span></label>
                        <div class="kh-input-wrapper">
                            <select name="status" class="kh-form-input">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hiển thị</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>
                        @error('status') <span style="color:red; font-size:0.85rem">{{ $message }}</span> @enderror
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
@endsection