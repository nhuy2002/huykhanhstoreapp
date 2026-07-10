<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // 1. Danh sách người dùng
    public function index(Request $request)
    {
        // Bảo mật: Chỉ Admin mới có thể truy cập
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('error.access-denied');
        }

        $query = User::query();

        // Tìm kiếm theo tên hoặc sđt
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo trạng thái (nếu cần)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(10);
        
        // Giữ lại tham số tìm kiếm khi chuyển trang
        $users->appends($request->all());

        return view('users.index', compact('users'));
    }

    // 2. Duyệt thành viên (Chuyển waiting -> reviewed)
    public function approve($id)
    {
        // Bảo mật: Chỉ Admin mới có thể thực hiện
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('error.access-denied');
        }

        $user = User::findOrFail($id);
        $user->update(['status' => 'reviewed']);

        Log::info('Admin action', [
            'actor' => auth()->id(),
            'action' => 'approve_user',
            'target' => $user->id,
            'details' => ['status' => 'reviewed']
        ]);

        return back()->with('success', "Đã phê duyệt tài khoản: {$user->name}");
    }

    // 3. Thay đổi vai trò
    public function changeRole($id, Request $request)
    {
        // Bảo mật: Chỉ Admin mới có thể thực hiện
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('error.access-denied');
        }

        $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        $user = User::findOrFail($id);
        $oldRole = $user->role;
        $user->update(['role' => $request->role]);

        Log::info('Admin action', [
            'actor' => auth()->id(),
            'action' => 'change_role',
            'target' => $user->id,
            'details' => ['old_role' => $oldRole, 'new_role' => $request->role]
        ]);

        $roleText = ($request->role === 'admin') ? 'Admin' : 'User';
        return back()->with('success', 'Đã thay đổi vai trò của ' . $user->name . ' thành ' . $roleText);
    }

    // 4. Xóa thành viên
    public function destroy($id)
    {
        // Bảo mật: Chỉ Admin mới có thể thực hiện
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('error.access-denied');
        }

        $user = User::findOrFail($id);

        // Chặn xóa chính mình (nếu đang login)
        if (auth()->id() == $id) {
            return back()->with('error', 'Không thể xóa tài khoản đang đăng nhập!');
        }

        $user->delete();

        Log::info('Admin action', [
            'actor' => auth()->id(),
            'action' => 'delete_user',
            'target' => $id,
        ]);

        return back()->with('success', 'Đã xóa tài khoản thành công!');
    }
}
