@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/product-custom.css') }}"> <link rel="stylesheet" href="{{ asset('css/user-custom.css') }}">    <link rel="stylesheet" href="{{ asset('css/pagination.css') }}">

<div class="kh-container" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    
    <div class="kh-page-header">
        <h1 class="kh-page-title">Quản lý thành viên</h1>

        <form action="{{ route('users.index') }}" method="GET" class="kh-search-form">
            <input type="text" name="search" class="kh-search-input" 
                   placeholder="Tìm tên, số điện thoại..." 
                   value="{{ request('search') }}">
            <button type="submit" class="kh-search-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </button>
        </form>
        
        <div style="display:flex; gap:5px;">
             <a href="{{ route('users.index', ['status' => 'waiting']) }}" class="kh-btn-icon" title="Lọc chờ duyệt" style="border: 1px solid #fcd34d; color: #d97706;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
             </a>
             <a href="{{ route('users.index') }}" class="kh-btn-icon" title="Tất cả">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
             </a>
        </div>
    </div>

    <div class="kh-table-wrapper">
        <table class="kh-table">
            <thead>
                <tr>
                    <th>Thành viên</th>
                    <th>Số điện thoại</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Ngày tham gia</th>
                    <th style="text-align: right;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr onclick="openUserModal({{ $user->id }})" style="cursor: pointer;">
                    <td>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" class="kh-table-avatar">
                        <span style="font-weight: 600;">{{ $user->name }}</span>
                    </td>
                    <td>{{ $user->phone }}</td>
                    <td>
                        @if($user->role == 'admin')
                            <span class="kh-badge kh-role-admin">Admin</span>
                        @else
                            <span class="kh-badge kh-role-user">User</span>
                        @endif
                    </td>
                    <td>
                        @if($user->status == 'reviewed')
                            <span class="kh-badge kh-badge-reviewed">Đã duyệt</span>
                        @else
                            <span class="kh-badge kh-badge-waiting">Chờ duyệt</span>
                        @endif
                    </td>
                    <td style="color: #64748b; font-size: 0.9rem;">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                    <td style="text-align: right;" onclick="event.stopPropagation()">
                        <div class="kh-action-group">
                            @if($user->status == 'waiting')
                                <form action="{{ route('users.approve', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="kh-action-btn" style="background:#dcfce7; color:#15803d; border:none; cursor:pointer;">Duyệt</button>
                                </form>
                            @endif

                            @if(auth()->id() !== $user->id)
                                <select class="kh-role-select" onchange="changeUserRole(event, {{ $user->id }}, '{{ $user->name }}', this.value)">
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>

                                <button class="kh-action-btn kh-btn-delete" onclick="deleteUser(event, {{ $user->id }})" style="border:none; cursor:pointer;">Xóa</button>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
                                    @csrf @method('DELETE')
                                </form>
                            @else
                                <span style="font-size: 0.85rem; color: #94a3b8; font-style: italic;">Tài khoản của bạn</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #64748b;">Không tìm thấy thành viên nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="kh-mobile-list">
        @foreach($users as $user)
        <div class="kh-user-mobile-card" onclick="openUserModal({{ $user->id }})">
            <div style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" style="width: 50px; height: 50px; border-radius: 50%;">
                <div>
                    <div style="font-weight: 700; font-size: 1rem;">{{ $user->name }}</div>
                    <div style="font-size: 0.85rem; color: #64748b;">{{ $user->phone }}</div>
                </div>
            </div>

            <div class="kh-usr-mob-row">
                <span class="kh-usr-mob-label">Vai trò:</span>
                @if($user->role == 'admin')
                    <span class="kh-badge kh-role-admin">Admin</span>
                @else
                    <span class="kh-badge kh-role-user">User</span>
                @endif
            </div>

            <div class="kh-usr-mob-row">
                <span class="kh-usr-mob-label">Trạng thái:</span>
                @if($user->status == 'reviewed')
                    <span class="kh-badge kh-badge-reviewed">Đã duyệt</span>
                @else
                    <span class="kh-badge kh-badge-waiting">Chờ duyệt</span>
                @endif
            </div>

            <div class="kh-usr-mob-actions" onclick="event.stopPropagation()">
                @if($user->status == 'waiting')
                    <form action="{{ route('users.approve', $user->id) }}" method="POST" style="display:inline;">
                        @csrf @method('PATCH')
                        <button type="submit" class="kh-action-btn" style="background:#dcfce7; color:#15803d; border:none;">Duyệt ngay</button>
                    </form>
                @endif
                @if(auth()->id() !== $user->id)
                    <select class="kh-role-select" onchange="changeUserRole(event, {{ $user->id }}, '{{ $user->name }}', this.value)" style="margin-right: 8px;">
                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <button class="kh-action-btn kh-btn-delete" onclick="deleteUser(event, {{ $user->id }})" style="border:none;">Xóa</button>
                @else
                    <span style="font-size: 0.85rem; color: #94a3b8; font-style: italic; margin-left: auto;">Tài khoản của bạn</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div style="margin-top: 20px;">
        {{ $users->links('components.pagination') }}
    </div>
</div>

<div id="user-modal" class="kh-modal-overlay">
    <div class="kh-modal-container" style="max-width: 500px;">
        <div class="kh-modal-header">
            <h3 class="kh-page-title" style="font-size: 1.2rem;">Thông tin thành viên</h3>
            <button type="button" class="kh-modal-close" onclick="closeUserModal()">&times;</button>
        </div>
        
        <div class="kh-modal-body" style="grid-template-columns: 1fr;"> <div style="text-align: center; margin-bottom: 10px;">
                <img id="modal-avatar" src="" style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid #e2e8f0;">
                <h3 id="modal-name" style="margin: 10px 0 5px 0; color: #1e293b;">--</h3>
                <span id="modal-role-badge" class="kh-badge">--</span>
            </div>

            <div class="kh-modal-user-info">
                <div class="kh-modal-row">
                    <span class="kh-modal-label">ID Thành viên</span>
                    <span class="kh-modal-value" id="modal-id">--</span>
                </div>
                <div class="kh-modal-row">
                    <span class="kh-modal-label">Số điện thoại</span>
                    <span class="kh-modal-value" id="modal-phone">--</span>
                </div>
                <div class="kh-modal-row">
                    <span class="kh-modal-label">Trạng thái</span>
                    <span class="kh-modal-value" id="modal-status">--</span>
                </div>
                <div class="kh-modal-row">
                    <span class="kh-modal-label">Ngày đăng ký</span>
                    <span class="kh-modal-value" id="modal-date">--</span>
                </div>
            </div>

            <div style="text-align: center; margin-top: 10px;" id="modal-actions">
                </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Data Users
    const usersData = @json($users->items());
    const usersMap = {};
    usersData.forEach(u => usersMap[u.id] = u);

    function openUserModal(id) {
        const user = usersMap[id];
        if(!user) return;

        // Fill info
        document.getElementById('modal-avatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=random`;
        document.getElementById('modal-name').innerText = user.name;
        document.getElementById('modal-id').innerText = '#' + user.id;
        document.getElementById('modal-phone').innerText = user.phone;
        
        // Date
        const date = new Date(user.created_at);
        document.getElementById('modal-date').innerText = date.toLocaleDateString('vi-VN');

        // Role Badge
        const roleBadge = document.getElementById('modal-role-badge');
        if(user.role === 'admin') {
            roleBadge.className = 'kh-badge kh-role-admin';
            roleBadge.innerText = 'Admin';
        } else {
            roleBadge.className = 'kh-badge kh-role-user';
            roleBadge.innerText = 'User';
        }

        // Status Text
        const statusEl = document.getElementById('modal-status');
        if(user.status === 'reviewed') {
            statusEl.innerText = 'Đã duyệt';
            statusEl.style.color = '#15803d';
        } else {
            statusEl.innerText = 'Chờ duyệt';
            statusEl.style.color = '#d97706';
        }

        document.getElementById('user-modal').classList.add('active');
    }

    function closeUserModal() {
        document.getElementById('user-modal').classList.remove('active');
    }

    // Đóng khi bấm ngoài
    document.getElementById('user-modal').addEventListener('click', function(e) {
        if(e.target === this) closeUserModal();
    });

    // Hàm thay đổi vai trò
    function changeUserRole(event, id, name, newRole) {
        event.stopPropagation(); // Chặn mở modal

        const currentRole = usersMap[id]?.role;
        const userStatus = usersMap[id]?.status;

        if (currentRole === newRole) return; // Không thay đổi gì

        // Kiểm tra trạng thái tài khoản - chỉ cho phép đổi vai trò nếu đã được phê duyệt
        if (userStatus !== 'reviewed') {
            Swal.fire({
                title: 'Không thể thay đổi vai trò',
                text: 'Tài khoản phải được phê duyệt trước khi có thể cấp vai trò!',
                icon: 'warning',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Đã hiểu'
            });
            // Reset dropdown về giá trị cũ
            event.target.value = currentRole;
            return;
        }

        const roleText = newRole === 'admin' ? 'Admin' : 'User';
        const actionText = newRole === 'admin' ? 'cấp quyền Admin' : 'thu hồi quyền Admin';

        Swal.fire({
            title: `Thay đổi vai trò?`,
            text: `Bạn có chắc chắn muốn ${actionText} cho "${name}" không?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Huỷ'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tạo form ẩn và submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/users/${id}/change-role`;

                // CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken.getAttribute('content');
                    form.appendChild(csrfInput);
                }

                // Role input
                const roleInput = document.createElement('input');
                roleInput.type = 'hidden';
                roleInput.name = 'role';
                roleInput.value = newRole;
                form.appendChild(roleInput);

                // Method PATCH
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PATCH';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            } else {
                // Reset dropdown về giá trị cũ nếu hủy
                event.target.value = currentRole;
            }
        });
    }

    // Hàm xóa
    function deleteUser(event, id) {
        event.stopPropagation(); // Chặn mở modal
        Swal.fire({
            title: 'Xóa tài khoản này?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa ngay',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection
