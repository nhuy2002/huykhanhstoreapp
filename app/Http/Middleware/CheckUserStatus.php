<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * [SEC-TASK-005] Kiểm tra trạng thái tài khoản trên mỗi request.
 * Nếu Admin chuyển status về 'waiting' hoặc xóa tài khoản,
 * user sẽ bị đăng xuất ngay lập tức.
 */
class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->status !== 'reviewed') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('account_waiting', true);
        }

        return $next($request);
    }
}
