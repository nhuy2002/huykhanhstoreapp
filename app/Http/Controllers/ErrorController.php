<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    /**
     * Hiển thị trang lỗi truy cập bị từ chối (403)
     */
    public function accessDenied()
    {
        return view('errors.access-denied');
    }
}
