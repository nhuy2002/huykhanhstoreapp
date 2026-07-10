<?php

/**
 * [SEC-TASK-004] Cấu hình VietQR cho thanh toán chuyển khoản.
 * Sử dụng config('vietqr.bank_id') thay vì env('VIETQR_BANK_ID')
 * để đảm bảo hoạt động đúng sau khi chạy php artisan config:cache.
 */
return [
    'bank_id' => env('VIETQR_BANK_ID', 'MB'),
    'account_no' => env('VIETQR_ACCOUNT_NO'),
    'account_name' => env('VIETQR_ACCOUNT_NAME'),
    'template' => env('VIETQR_TEMPLATE', 'compact'),
];
