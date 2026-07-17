<?php

/**
 * [SEC-TASK-004] Cấu hình tùy chỉnh cho ứng dụng.
 * Sử dụng config('custom.admin_email') thay vì env('ADMIN_EMAIL').
 */
return [
    'admin_email' => env('ADMIN_EMAIL'),
];
