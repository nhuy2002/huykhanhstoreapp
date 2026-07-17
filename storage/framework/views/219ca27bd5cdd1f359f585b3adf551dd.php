<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truy cập bị từ chối - <?php echo e(config('app.name')); ?></title>
    <link rel="icon" href="<?php echo e(asset('images/logokuchen.png')); ?>" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'Google Sans Flex', sans-serif;
        }

        body {
            background-color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            padding: 3rem 2rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            max-width: 500px;
            width: 90%;
        }

        .error-icon {
            font-size: 5rem;
            color: #dc3545;
            margin-bottom: 1.5rem;
        }

        .error-title {
            font-size: 2rem;
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 1rem;
        }

        .error-message {
            font-size: 1.1rem;
            color: #718096;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .error-details {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: left;
        }

        .error-details h5 {
            color: #495057;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .error-details p {
            color: #6c757d;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .btn-custom {
            color: red;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            border: 1px solid red;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .back-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            margin-top: 1rem;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #764ba2;
        }

        @media (max-width: 576px) {
            .error-container {
                padding: 2rem 1.5rem;
            }

            .error-title {
                font-size: 1.8rem;
            }

            .error-icon {
                font-size: 4rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-shield-alt"></i>
        </div>

        <h1 class="error-title">Truy cập bị từ chối</h1>

        <p class="error-message">
            Bạn không có quyền truy cập vào trang này. Chỉ tài khoản Admin mới có thể sử dụng chức năng này.
        </p>

        <div class="error-details">
            <h5><i class="fas fa-info-circle me-2"></i>Thông tin chi tiết:</h5>
            <p><strong>Lý do:</strong> Quyền truy cập không đủ</p>
            <p><strong>Yêu cầu:</strong> Tài khoản phải có vai trò Admin</p>
            <p><strong>Thời gian:</strong> <?php echo e(now()->format('d/m/Y H:i:s')); ?></p>
        </div>

        <div>
            <a href="<?php echo e(route('home')); ?>" class="btn-custom">
                <i class="fas fa-home me-2"></i>Về trang chủ
            </a>
        </div>

        <div>
            <a href="javascript:history.back()" class="back-link">
                <i class="fas fa-arrow-left me-2"></i>Quay lại trang trước
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH E:\Project\huykhanhstoreapp\resources\views/errors/access-denied.blade.php ENDPATH**/ ?>