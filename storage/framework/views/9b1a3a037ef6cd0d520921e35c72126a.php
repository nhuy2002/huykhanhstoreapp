<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Huy Khánh Store</title>
    <link rel="icon" href="<?php echo e(asset('images/icon.png')); ?>" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/header-custom.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/mobile-menu.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/announcement-popup.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/auth-custom.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/user-info.css')); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php echo $__env->yieldContent('head'); ?>

    <style>
    *{
        margin: 0;
        padding: 0;
        font-family: 'Google Sans Flex', sans-serif;
    }

    html, body {
        width: 100%;
        overflow-x: hidden;
        max-width: 100vw;
        }
    </style>
</head>

<body class="home">
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main style="min-height: calc(100vh - 150px);">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer style="background-color: #f1f5f9; padding: 15px 0; text-align: center; border-top: 1px solid #e2e8f0; color: #64748b; font-size: 0.85rem; width: 100%;">
        © 2026 Develop by VẠN SỰ NHƯ Ý | Ver 1.0.1
    </footer>

    <?php echo $__env->make('components.mobile-menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script src="<?php echo e(asset('js/mobile-menu.js')); ?>"></script>
    <script src="<?php echo e(asset('js/header.js')); ?>"></script>
    <script src="<?php echo e(asset('js/dashboard.js')); ?>"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
<?php /**PATH C:\Users\NHUY\Desktop\huykhanhstore\huykhanhstoreapp\resources\views/layouts/app.blade.php ENDPATH**/ ?>