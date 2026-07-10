<?php

// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Xử lý Đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:80',
            'phone' => 'required|string|size:10|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'phone.unique' => 'Số điện thoại này đã được đăng ký.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => 'waiting', // Mặc định chờ duyệt
            'role' => 'user',      // Mặc định là user
        ]);

        // Đăng ký xong chuyển hướng về login với thông báo
        return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng chờ Admin xét duyệt.');
    }

    // Xử lý Đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // 1. Kiểm tra trạng thái
            if ($user->status !== 'reviewed') {
                Auth::logout(); // Đăng xuất ngay lập tức
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Trả về kèm session flash để kích hoạt SweetAlert
                return back()->with('account_waiting', true);
            }

            // 2. Đã được duyệt -> Điều hướng về trang chủ (Admin cũng dùng home page)
            $request->session()->regenerate();

            return redirect()->intended('/'); // Trang Home cho cả Admin và User
        }

        return back()->withErrors([
            'phone' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('phone');
    }

    public function showChangePasswordForm()
    {
        return view('auth.change');
    }

    public function changePassword(Request $request)
    {
        // Validate dữ liệu đầu vào
        // Lưu ý: regex phải khớp với JS bên client bạn đã viết (chỉ chữ và số, 8-16 ký tự)
        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:16',
                'regex:/^[a-zA-Z0-9]+$/', // Chỉ cho phép chữ và số
                'confirmed' // Tự động check khớp với password_confirmation
            ],
        ], [
            // Tùy chỉnh thông báo lỗi tiếng Việt
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu phải từ 8 ký tự trở lên.',
            'password.max' => 'Mật khẩu không quá 16 ký tự.',
            'password.regex' => 'Mật khẩu chỉ bao gồm chữ và số.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại có đúng không
        if (!Hash::check($request->current_password, $user->password)) {
            // Trả về lỗi gán vào trường current_password để hiện đỏ input
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        // Cập nhật mật khẩu mới
        /** @var \App\Models\User $user */
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    public function showProfile()
    {
        // Lấy thông tin user đang đăng nhập
        $user = Auth::user();
        
        return view('auth.user-info', compact('user'));
    }

    // Xử lý Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
