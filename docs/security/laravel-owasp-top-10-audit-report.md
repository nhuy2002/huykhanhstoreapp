# Laravel OWASP Top 10 Security Audit Report

**Dự án:** HuyKhanhStore  
**Ngày audit:** 2026-07-10  
**Auditor:** Antigravity Security Audit Agent  
**Trạng thái:** Chỉ audit — chưa sửa code

---

## 1. Executive Summary

### Thông tin hệ thống

| Thông tin | Giá trị |
|---|---|
| Laravel | ^12.0 |
| PHP | ^8.2 |
| Database | MySQL (DB: `huykhanhstore`) |
| Frontend | Blade + Bootstrap 5.3.2 + Vanilla JS + SweetAlert2 |
| Authentication | Session-based (Laravel built-in, guard `web`) |
| Authorization | Custom middleware `CheckAdminRole` + inline `role` check |
| API | Không có (`routes/api.php` không tồn tại) |
| Queue | Database driver |
| Cache | Database driver |

### Phạm vi đã kiểm tra

- 7 Controllers, 1 Middleware, 4 Models, 1 Mail class
- `routes/web.php` (58 dòng, ~20 route)
- 15 Blade views (layouts, partials, auth, products, orders, users, emails, errors)
- 7 Migration files
- Config: `app.php`, `auth.php`, `session.php`, `filesystems.php`, `database.php`, `logging.php`, `mail.php`
- `.env`, `.env.example`, `.gitignore`
- `bootstrap/app.php`, `composer.json`, `package.json`

### Tổng số finding theo severity

| Severity | Số lượng |
|---|---|
| **Critical** | 2 |
| **High** | 4 |
| **Medium** | 5 |
| **Low** | 2 |
| **Informational** | 1 |
| **Tổng** | **14** |

### Tổng số finding theo confidence

| Confidence | Số lượng |
|---|---|
| **High** | 10 |
| **Medium** | 3 |
| **Low** | 1 |

### Top rủi ro cần xử lý

1. **SEC-001** (Critical): Mass assignment cho phép user thường inject trường không mong muốn qua `$request->all()` trong `ProductController`
2. **SEC-002** (Critical): Thiếu authorization trên route quản lý sản phẩm — user thường có toàn quyền CRUD sản phẩm
3. **SEC-003** (High): Dashboard trang chủ hiển thị dữ liệu doanh thu, đơn hàng cho tất cả user kể cả user thường
4. **SEC-004** (High): Sử dụng `env()` trực tiếp trong controller thay vì `config()`, sẽ trả về `null` khi cache config

### Giới hạn của quá trình audit

- Không có quyền truy cập runtime/production để kiểm tra rate limiting thực tế
- Không thể kiểm tra cấu hình web server (Nginx/Apache) thực tế
- Không thể kiểm tra network-level security (firewall, TLS)
- Không chạy `composer audit` / `npm audit` (cần runtime)
- Không có access log để phân tích hành vi bất thường

### Kết luận sơ bộ

> **Repository chưa đủ điều kiện triển khai production.** Có 2 lỗ hổng Critical (Mass Assignment + Missing Authorization) cho phép bất kỳ user đã đăng nhập nào chiếm toàn quyền quản trị sản phẩm. Cần khắc phục ít nhất tất cả finding Critical và High trước khi triển khai.

---

## 2. Repository Security Map

### Entry Points

- **Web routes:** 20 routes qua `routes/web.php`
- **API routes:** Không có
- **Console:** 1 command mặc định (`inspire`)
- **Health check:** `/up` (Laravel built-in)

### Authentication Mechanism

- Session-based qua Laravel guard `web`
- Đăng nhập bằng `phone` + `password`
- Tài khoản mới phải được Admin duyệt (`status: waiting → reviewed`)
- Hỗ trợ "Remember Me" qua checkbox
- Session regeneration sau đăng nhập ✅
- Session invalidation khi logout ✅

### Authorization Mechanism

- Middleware `admin`: kiểm tra `auth()->user()->role !== 'admin'`
- Inline role check trong controller (UserController, OrderController)
- **Không sử dụng** Gate, Policy, hoặc Form Request
- **Không có** kiểm tra ownership trên bất kỳ resource nào

### Bảng route quan trọng

| Method | URI | Controller/Action | Middleware | Auth Required | Authorization | Risk Note |
|---|---|---|---|---|---|---|
| GET | `/` | HomeController@index | `auth` | ✅ | ❌ Không | **Hiển thị doanh thu cho mọi user** |
| POST | `/login` | AuthController@login | `guest` | ❌ | N/A | Không có rate limiting |
| POST | `/register` | AuthController@register | `guest` | ❌ | N/A | Không có rate limiting |
| GET | `/products` | ProductController@index | `auth` | ✅ | ❌ Không | User thường truy cập được |
| GET | `/products/create` | ProductController@create | `auth` | ✅ | ❌ Không | **User thường tạo sản phẩm** |
| POST | `/products` | ProductController@store | `auth` | ✅ | ❌ Không | **Mass assignment + no admin check** |
| GET | `/products/{id}/edit` | ProductController@edit | `auth` | ✅ | ❌ Không | **User thường sửa sản phẩm** |
| PUT | `/products/{id}` | ProductController@update | `auth` | ✅ | ❌ Không | **Mass assignment + no admin check** |
| DELETE | `/products/{id}` | ProductController@destroy | `auth` | ✅ | ❌ Không | **User thường xóa sản phẩm** |
| POST | `/orders/checkout` | OrderController@checkout | `auth` | ✅ | ❌ Không | Client-controlled pricing data |
| POST | `/orders` | OrderController@store | `auth` | ✅ | ❌ Không | Dữ liệu giá được server re-fetch ✅ |
| GET | `/orders` | OrderController@index | `auth`, `admin` | ✅ | ✅ Middleware + inline | Redundant double-check |
| PUT | `/orders/{id}` | OrderController@update | `auth`, `admin` | ✅ | ✅ Middleware | OK |
| DELETE | `/orders/{id}` | OrderController@destroy | `auth`, `admin` | ✅ | ✅ Middleware | OK |
| GET | `/users` | UserController@index | `auth`, `admin` | ✅ | ✅ Middleware + inline | Redundant double-check |
| PATCH | `/users/{id}/change-role` | UserController@changeRole | `auth`, `admin` | ✅ | ✅ Middleware + inline | Admin có thể tự hạ quyền |
| DELETE | `/users/{id}` | UserController@destroy | `auth`, `admin` | ✅ | ✅ Middleware + inline | Chặn self-delete ✅ |

---

## 3. OWASP Top 10 Assessment

### A01:2021 — Broken Access Control

- **Trạng thái:** `Confirmed`
- **Thành phần đã kiểm tra:** Tất cả controllers, middleware, routes, models
- **Phát hiện:** SEC-001, SEC-002, SEC-003
- **Bằng chứng chính:** Toàn bộ route `/products/*` (CRUD) chỉ yêu cầu `auth` middleware, không yêu cầu quyền admin. User thường có thể tạo/sửa/xóa sản phẩm. Mass assignment qua `$request->all()` trong ProductController.
- **Khuyến nghị:** Thêm middleware `admin` cho tất cả route quản lý sản phẩm. Sử dụng `$request->only()` thay vì `$request->all()`.

### A02:2021 — Cryptographic Failures

- **Trạng thái:** `Not Found`
- **Thành phần đã kiểm tra:** Password hashing, APP_KEY, session encryption
- **Ghi chú:** Password sử dụng `Hash::make()` với `bcrypt_rounds=12` ✅. APP_KEY được thiết lập ✅. Session encryption bị tắt (`SESSION_ENCRYPT=false`) nhưng đây là cấu hình mặc định.

### A03:2021 — Injection

- **Trạng thái:** `Not Found` (SQL Injection), `Suspected` (XSS)
- **Thành phần đã kiểm tra:** Tất cả query, Blade templates, JavaScript
- **Ghi chú:** Không tìm thấy `DB::raw()` hoặc raw query. XSS tiềm ẩn qua `innerHTML` trong JavaScript — xem SEC-013.

### A04:2021 — Insecure Design

- **Trạng thái:** `Confirmed`
- **Phát hiện:** SEC-003, SEC-005
- **Bằng chứng:** Dashboard hiển thị dữ liệu nhạy cảm cho mọi user. Không có middleware kiểm tra trạng thái tài khoản.

### A05:2021 — Security Misconfiguration

- **Trạng thái:** `Confirmed`
- **Phát hiện:** SEC-006, SEC-007, SEC-008
- **Bằng chứng:** `APP_DEBUG=true` trong `.env`, `env()` trực tiếp trong controller, database password trống.

### A06:2021 — Vulnerable and Outdated Components

- **Trạng thái:** `Unable to Verify`
- **Ghi chú:** Cần chạy `composer audit` và `npm audit` trong runtime.

### A07:2021 — Identification and Authentication Failures

- **Trạng thái:** `Confirmed`
- **Phát hiện:** SEC-009, SEC-010, SEC-012
- **Bằng chứng:** Không có rate limiting cho login/register. Chính sách mật khẩu yếu và không nhất quán.

### A08:2021 — Software and Data Integrity Failures

- **Trạng thái:** `Not Found`

### A09:2021 — Security Logging and Monitoring Failures

- **Trạng thái:** `Confirmed`
- **Phát hiện:** SEC-011

### A10:2021 — Server-Side Request Forgery (SSRF)

- **Trạng thái:** `Not Found`

---

## 4. Detailed Findings

### SEC-001: Mass Assignment qua `$request->all()` trong ProductController

- **OWASP category:** A01:2021 — Broken Access Control
- **Severity:** Critical
- **Confidence:** High
- **Status:** Confirmed
- **Affected component:** ProductController
- **Affected route/API:** `POST /products`, `PUT /products/{id}`
- **Affected role:** Bất kỳ user đã đăng nhập
- **File path:** `app/Http/Controllers/ProductController.php`
- **Line number:** 48, 85
- **Related class/method:** `ProductController::store()`, `ProductController::update()`
- **Evidence:**
  ```php
  // Line 48 (store method)
  $data = $request->all();
  // Line 85 (update method)
  $data = $request->all();
  ```
  `$request->all()` lấy toàn bộ dữ liệu từ request bao gồm cả trường không được validate. `$data` sẽ chứa bất kỳ trường nào client gửi lên bao gồm `slug`, `sold`, `description`, hoặc bất kỳ trường nào trong `$fillable` của Product model. Attacker có thể inject `sold=99999` hoặc `description=<script>...</script>`.
- **Root cause:** Sử dụng `$request->all()` thay vì `$request->only()` hoặc `$request->validated()`
- **Attack precondition:** User đã đăng nhập (status: reviewed)
- **Security impact:** Thay đổi dữ liệu sản phẩm không đúng ý nghiệp vụ (giả mạo số lượng đã bán, inject mô tả)
- **Business impact:** Dữ liệu sản phẩm bị sai lệch, mất tính toàn vẹn
- **Recommended remediation:** Thay `$request->all()` bằng `$request->only(['name', 'price', 'quantity', 'status', 'image', 'gallery'])` hoặc sử dụng Form Request
- **Alternative remediation:** Tạo `StoreProductRequest` và `UpdateProductRequest` Form Request class
- **Backward compatibility considerations:** Không ảnh hưởng
- **Validation steps:** Gửi POST request với trường `sold=99999`, kiểm tra giá trị có được lưu vào database hay không
- **Required tests:** Unit test kiểm tra chỉ các trường được phép mới được lưu
- **Estimated effort:** S (Small)
- **Related task IDs:** SEC-TASK-001

---

### SEC-002: Thiếu authorization trên toàn bộ route quản lý sản phẩm

- **OWASP category:** A01:2021 — Broken Access Control
- **Severity:** Critical
- **Confidence:** High
- **Status:** Confirmed
- **Affected component:** Routes, ProductController
- **Affected route/API:** `GET /products/create`, `POST /products`, `GET /products/{id}/edit`, `PUT /products/{id}`, `DELETE /products/{id}`
- **Affected role:** Mọi user đã đăng nhập (role: user)
- **File path:** `routes/web.php`
- **Line number:** 25-31
- **Related class/method:** Tất cả method trong `ProductController`
- **Evidence:**
  ```php
  // routes/web.php, dòng 19-31
  Route::middleware(['auth'])->group(function () {
      Route::get('/products/create', ...);
      Route::post('/products', ...);
      Route::get('/products/{id}/edit', ...);
      Route::put('/products/{id}', ...);
      Route::delete('/products/{id}', ...);
  });
  ```
  Các route CRUD sản phẩm chỉ yêu cầu middleware `auth`, không yêu cầu `admin`. ProductController cũng không có inline role check.
- **Root cause:** Route sản phẩm được đặt trong nhóm `auth` thay vì `auth + admin`
- **Attack precondition:** User đã đăng nhập và được duyệt
- **Security impact:** User thường chiếm quyền quản trị sản phẩm — tạo/sửa giá/xóa sản phẩm
- **Business impact:** Mất toàn vẹn dữ liệu sản phẩm, ảnh hưởng nghiêm trọng đến hoạt động kinh doanh
- **Recommended remediation:** Di chuyển các route write (create/store/edit/update/destroy) vào nhóm `Route::middleware(['auth', 'admin'])`
- **Alternative remediation:** Thêm inline role check trong mỗi method của ProductController
- **Backward compatibility considerations:** Không ảnh hưởng nếu chỉ admin mới được phép quản lý sản phẩm
- **Validation steps:** Đăng nhập user thường, truy cập `/products/create`
- **Required tests:** Feature test kiểm tra user thường bị từ chối
- **Estimated effort:** S (Small)
- **Related task IDs:** SEC-TASK-002

---

### SEC-003: Dashboard hiển thị dữ liệu nhạy cảm cho tất cả user

- **OWASP category:** A01:2021 — Broken Access Control
- **Severity:** High
- **Confidence:** High
- **Status:** Confirmed
- **Affected component:** HomeController, home.blade.php
- **Affected route/API:** `GET /`
- **Affected role:** Tất cả user đã đăng nhập
- **File path:** `app/Http/Controllers/HomeController.php`
- **Line number:** 13-37
- **Related class/method:** `HomeController::index()`
- **Evidence:** Dữ liệu doanh thu tổng, tổng đơn hàng, tổng user, và 3 đơn hàng gần nhất được hiển thị cho tất cả user đã đăng nhập.
- **Root cause:** Controller không phân biệt role khi trả về dữ liệu
- **Attack precondition:** User đã đăng nhập
- **Security impact:** Lộ thông tin kinh doanh nhạy cảm
- **Business impact:** Rò rỉ dữ liệu kinh doanh cho nhân viên không có thẩm quyền
- **Recommended remediation:** Kiểm tra role trong `HomeController::index()` và hiển thị dữ liệu phù hợp
- **Estimated effort:** M (Medium)
- **Related task IDs:** SEC-TASK-003

---

### SEC-004: Sử dụng `env()` trực tiếp trong controller

- **OWASP category:** A05:2021 — Security Misconfiguration
- **Severity:** High
- **Confidence:** High
- **Status:** Confirmed
- **Affected component:** OrderController
- **Affected route/API:** `POST /orders/checkout`, `POST /orders`
- **File path:** `app/Http/Controllers/OrderController.php`
- **Line number:** 81-83, 165
- **Related class/method:** `OrderController::checkout()`, `OrderController::store()`
- **Evidence:**
  ```php
  $bankId = env('VIETQR_BANK_ID');       // Line 81
  $accountNo = env('VIETQR_ACCOUNT_NO'); // Line 82
  $accountName = env('VIETQR_ACCOUNT_NAME'); // Line 83
  $adminEmail = env('ADMIN_EMAIL');       // Line 165
  ```
  Khi chạy `php artisan config:cache`, `env()` trả về `null` ngoài config files.
- **Root cause:** Sử dụng `env()` trực tiếp thay vì `config()`
- **Security impact:** Mất khả năng giám sát đơn hàng trong production
- **Business impact:** QR thanh toán không hiển thị, email admin không gửi
- **Recommended remediation:** Tạo `config/vietqr.php` và `config/custom.php`, sử dụng `config()` trong controller
- **Estimated effort:** S (Small)
- **Related task IDs:** SEC-TASK-004

---

### SEC-005: Thiếu kiểm tra trạng thái user sau khi đăng nhập

- **OWASP category:** A01:2021 — Broken Access Control
- **Severity:** High
- **Confidence:** High
- **Status:** Confirmed
- **Affected component:** Middleware pipeline
- **Affected route/API:** Tất cả route `auth`
- **File path:** `bootstrap/app.php` (thiếu middleware)
- **Evidence:** Không có middleware kiểm tra `status` trên mỗi request. User bị Admin vô hiệu hóa vẫn giữ session active tối đa 120 phút.
- **Root cause:** Thiếu middleware kiểm tra trạng thái tài khoản
- **Security impact:** Không thể thu hồi quyền truy cập ngay lập tức
- **Recommended remediation:** Tạo middleware `CheckUserStatus` kiểm tra `auth()->user()->status === 'reviewed'`
- **Estimated effort:** S (Small)
- **Related task IDs:** SEC-TASK-005

---

### SEC-006: `APP_DEBUG=true` trong file `.env`

- **OWASP category:** A05:2021 — Security Misconfiguration
- **Severity:** Medium
- **Confidence:** High
- **Status:** Confirmed
- **File path:** `.env`
- **Line number:** 4
- **Evidence:** `APP_DEBUG=true` — Stack trace đầy đủ khi xảy ra lỗi
- **Recommended remediation:** `APP_DEBUG=false` trên production
- **Estimated effort:** S (Small)
- **Related task IDs:** SEC-TASK-006

---

### SEC-007: Database password trống cho user root

- **OWASP category:** A05:2021 — Security Misconfiguration
- **Severity:** Medium
- **Confidence:** High
- **Status:** Confirmed
- **File path:** `.env`
- **Line number:** 28
- **Evidence:** `DB_USERNAME=root`, `DB_PASSWORD=` (trống)
- **Recommended remediation:** Tạo dedicated database user với password mạnh
- **Estimated effort:** S (Small)
- **Related task IDs:** SEC-TASK-007

---

### SEC-008: Credentials nhạy cảm có nguy cơ bị commit vào Git

- **OWASP category:** A05:2021 — Security Misconfiguration
- **Severity:** Medium
- **Confidence:** Medium
- **Status:** Suspected
- **File path:** `.env`, `.gitignore`
- **Evidence:** `.env` chứa APP_KEY, MAIL_PASSWORD (Gmail app password), VIETQR_ACCOUNT_NO. `.gitignore` đã exclude `.env` ✅ nhưng cần kiểm tra Git history.
- **Recommended remediation:** Kiểm tra Git history. Nếu đã commit, rotate toàn bộ secret.
- **Estimated effort:** M (Medium)
- **Related task IDs:** SEC-TASK-008

---

### SEC-009: Không có rate limiting cho login/register

- **OWASP category:** A07:2021 — Identification and Authentication Failures
- **Severity:** Medium
- **Confidence:** High
- **Status:** Confirmed
- **File path:** `routes/web.php`
- **Line number:** 11-17
- **Evidence:** Không tìm thấy `throttle` middleware hoặc `RateLimiter` cho route đăng nhập/đăng ký.
- **Recommended remediation:** Thêm `throttle:5,1` middleware cho `POST /login`
- **Estimated effort:** S (Small)
- **Related task IDs:** SEC-TASK-009

---

### SEC-010: Chính sách mật khẩu yếu và không nhất quán

- **OWASP category:** A07:2021 — Identification and Authentication Failures
- **Severity:** Medium
- **Confidence:** High
- **Status:** Confirmed
- **File path:** `app/Http/Controllers/AuthController.php`
- **Line number:** 17-24 (register), 81-98 (changePassword)
- **Evidence:** Register chỉ yêu cầu `min:8`. Change password **cấm** ký tự đặc biệt (`regex:/^[a-zA-Z0-9]+$/`). Hai endpoint có policy khác nhau.
- **Recommended remediation:** Đồng bộ policy, sử dụng `Password::min(8)->letters()->numbers()`
- **Estimated effort:** S (Small)
- **Related task IDs:** SEC-TASK-010

---

### SEC-011: Thiếu audit logging cho hành động quản trị

- **OWASP category:** A09:2021 — Security Logging and Monitoring Failures
- **Severity:** Low
- **Confidence:** High
- **Status:** Confirmed
- **File path:** Tất cả controller
- **Evidence:** Không tìm thấy `Log::info()` hoặc audit trail cho hành động: phê duyệt tài khoản, thay đổi role, xóa user, xóa đơn hàng.
- **Recommended remediation:** Thêm `Log::info()` cho tất cả hành động quản trị
- **Estimated effort:** M (Medium)
- **Related task IDs:** SEC-TASK-011

---

### SEC-012: Không invalidate session khi đổi mật khẩu

- **OWASP category:** A07:2021 — Identification and Authentication Failures
- **Severity:** Low
- **Confidence:** High
- **Status:** Confirmed
- **File path:** `app/Http/Controllers/AuthController.php`
- **Line number:** 77-116
- **Evidence:** Sau `$user->update(['password' => ...])`, không có session regeneration hoặc `logoutOtherDevices()`. Attacker giữ session cũ vẫn active.
- **Recommended remediation:** Thêm `Auth::logoutOtherDevices()` và `$request->session()->regenerate()`
- **Estimated effort:** S (Small)
- **Related task IDs:** SEC-TASK-012

---

### SEC-013: DOM-based XSS risk qua innerHTML trong JavaScript

- **OWASP category:** A03:2021 — Injection (XSS)
- **Severity:** Medium
- **Confidence:** Medium
- **Status:** Suspected
- **File path:** `resources/views/orders/create.blade.php`
- **Line number:** 50, 71, 76
- **Evidence:** Dữ liệu sản phẩm được truyền qua `@json()` rồi render bằng `innerHTML`. Nếu tên sản phẩm chứa HTML/JavaScript, nó có thể được thực thi. `@json()` escape `JSON_HEX_TAG` ✅ nhưng khi gán vào `innerHTML`, escape JSON không đủ ngăn XSS.
- **Recommended remediation:** Sử dụng `textContent` thay `innerHTML` cho dữ liệu text, hoặc escape HTML trước khi gán
- **Estimated effort:** M (Medium)
- **Related task IDs:** SEC-TASK-013

---

### SEC-014: Reflected XSS tiềm ẩn qua session flash trong SweetAlert

- **OWASP category:** A03:2021 — Injection (XSS)
- **Severity:** Informational
- **Confidence:** Low
- **Status:** Suspected
- **File path:** `resources/views/home.blade.php`
- **Line number:** 164
- **Evidence:** `text: '{{ session("order_success") }}'` — giá trị hiện tại là hardcoded string nên không có rủi ro thực tế. Blade `{{ }}` escape HTML ✅. Chỉ ghi nhận vì pattern có tiềm ẩn rủi ro nếu data source thay đổi trong tương lai.
- **Recommended remediation:** Sử dụng `@json(session('order_success'))` cho JavaScript context
- **Estimated effort:** S (Small)
- **Related task IDs:** N/A

---

## 5. Remediation Backlog

### SEC-TASK-001: Thay thế `$request->all()` bằng `$request->only()`

- **Task ID:** SEC-TASK-001
- **Related finding:** SEC-001
- **Title:** Fix Mass Assignment trong ProductController
- **Objective:** Chỉ cho phép các trường cụ thể khi tạo/cập nhật sản phẩm
- **Priority:** P0
- **Complexity:** S
- **Affected files:** `app/Http/Controllers/ProductController.php`
- **Affected routes:** `POST /products`, `PUT /products/{id}`
- **Implementation steps:**
  1. Trong `store()` (line 48): thay `$data = $request->all()` bằng `$data = $request->only(['name', 'price', 'quantity', 'status'])`
  2. Trong `update()` (line 85): thay tương tự
  3. Xóa `$data['sold'] = 0` và đặt default trong model event hoặc migration
- **Constraints:** Không thay đổi logic upload file hiện tại
- **Acceptance criteria:** Gửi request với `sold=99999` → giá trị không được lưu
- **Unit tests:** Test store/update với extra fields, assert chỉ allowed fields được lưu
- **Feature tests:** POST `/products` với `sold` trong payload → assert `sold` = 0
- **Authorization tests:** N/A
- **Regression tests:** Sản phẩm vẫn tạo/cập nhật bình thường
- **Dependency:** Không
- **Rollback plan:** Revert thành `$request->all()`
- **Definition of Done:** Mass assignment test pass, CRUD hoạt động bình thường

---

### SEC-TASK-002: Di chuyển route sản phẩm vào nhóm admin middleware

- **Task ID:** SEC-TASK-002
- **Related finding:** SEC-002
- **Title:** Thêm authorization cho route quản lý sản phẩm
- **Objective:** Chỉ admin mới có thể tạo/sửa/xóa sản phẩm
- **Priority:** P0
- **Complexity:** S
- **Affected files:** `routes/web.php`
- **Affected routes:** `GET /products/create`, `POST /products`, `GET /products/{id}/edit`, `PUT /products/{id}`, `DELETE /products/{id}`
- **Implementation steps:**
  1. Giữ `GET /products` và `GET /products/{id}` trong nhóm `auth`
  2. Di chuyển các route write vào nhóm `Route::middleware(['auth', 'admin'])`
- **Constraints:** Route xem sản phẩm vẫn cho phép user thường
- **Acceptance criteria:** User thường bị redirect khi truy cập `/products/create`
- **Unit tests:** N/A
- **Feature tests:** User role `user` bị 302 khi truy cập route admin-only
- **Authorization tests:** Admin truy cập được tất cả route sản phẩm
- **Regression tests:** User thường vẫn xem được danh sách sản phẩm
- **Dependency:** Không
- **Rollback plan:** Di chuyển route lại nhóm `auth`
- **Definition of Done:** User thường không thể tạo/sửa/xóa sản phẩm

---

### SEC-TASK-003: Phân quyền dữ liệu Dashboard theo role

- **Task ID:** SEC-TASK-003
- **Related finding:** SEC-003
- **Title:** Ẩn dữ liệu nhạy cảm trên Dashboard cho user thường
- **Objective:** Chỉ admin xem được doanh thu, tổng đơn, tổng user
- **Priority:** P1
- **Complexity:** M
- **Affected files:** `app/Http/Controllers/HomeController.php`, `resources/views/home.blade.php`
- **Affected routes:** `GET /`
- **Implementation steps:**
  1. Kiểm tra `auth()->user()->role === 'admin'` trong `HomeController::index()`
  2. Nếu admin: trả về đầy đủ dữ liệu
  3. Nếu user: chỉ trả về shortcut (tạo đơn, xem sản phẩm)
  4. Dùng `@if(auth()->user()->role === 'admin')` trong view
- **Acceptance criteria:** User thường không thấy widget doanh thu
- **Dependency:** Không
- **Rollback plan:** Xóa `@if` condition
- **Definition of Done:** Dashboard hiển thị khác nhau theo role

---

### SEC-TASK-004: Di chuyển `env()` sang config files

- **Task ID:** SEC-TASK-004
- **Related finding:** SEC-004
- **Title:** Tạo config file cho VietQR và admin settings
- **Objective:** App hoạt động đúng sau `config:cache`
- **Priority:** P1
- **Complexity:** S
- **Affected files:** `config/vietqr.php` (mới), `config/custom.php` (mới), `app/Http/Controllers/OrderController.php`
- **Affected routes:** `POST /orders/checkout`, `POST /orders`
- **Implementation steps:**
  1. Tạo `config/vietqr.php` với các key VietQR
  2. Tạo `config/custom.php` với `admin_email`
  3. Thay `env()` bằng `config()` trong OrderController
- **Acceptance criteria:** `config:cache` → checkout/email vẫn hoạt động
- **Dependency:** Không
- **Rollback plan:** Revert về `env()` calls
- **Definition of Done:** `config:cache` không break app

---

### SEC-TASK-005: Tạo middleware CheckUserStatus

- **Task ID:** SEC-TASK-005
- **Related finding:** SEC-005
- **Title:** Auto-logout user bị vô hiệu hóa
- **Objective:** Kiểm tra trạng thái tài khoản trên mỗi request
- **Priority:** P1
- **Complexity:** S
- **Affected files:** `app/Http/Middleware/CheckUserStatus.php` (mới), `bootstrap/app.php`
- **Implementation steps:**
  1. Tạo middleware kiểm tra `auth()->user()->status !== 'reviewed'` → logout + redirect
  2. Đăng ký middleware trong `bootstrap/app.php`
- **Acceptance criteria:** Admin chuyển status → user bị redirect ngay request tiếp theo
- **Dependency:** Không
- **Definition of Done:** User bị disable bị đá ra ngay lập tức

---

### SEC-TASK-006: Thiết lập production config

- **Task ID:** SEC-TASK-006
- **Related finding:** SEC-006
- **Title:** `APP_DEBUG=false` và `APP_ENV=production`
- **Priority:** P1
- **Complexity:** S
- **Affected files:** `.env` (production)
- **Acceptance criteria:** Error page không hiển thị stack trace
- **Definition of Done:** Stack trace ẩn trên production

---

### SEC-TASK-007: Thiết lập database credential an toàn

- **Task ID:** SEC-TASK-007
- **Related finding:** SEC-007
- **Title:** Tạo dedicated DB user với password
- **Priority:** P2
- **Complexity:** S
- **Affected files:** `.env` (production), MySQL configuration
- **Definition of Done:** App chạy với dedicated user, root access bị hạn chế

---

### SEC-TASK-008: Kiểm tra và rotate secrets trong Git history

- **Task ID:** SEC-TASK-008
- **Related finding:** SEC-008
- **Title:** Kiểm tra leaked secrets trong Git history
- **Priority:** P2
- **Complexity:** M
- **Implementation steps:**
  1. Chạy `git log --all --full-history -- .env`
  2. Nếu bị commit: rotate tất cả secret, clean history
- **Definition of Done:** Không có secret trong Git history

---

### SEC-TASK-009: Thêm rate limiting cho login/register

- **Task ID:** SEC-TASK-009
- **Related finding:** SEC-009
- **Title:** Áp dụng throttle middleware cho auth routes
- **Priority:** P1
- **Complexity:** S
- **Affected files:** `routes/web.php`
- **Implementation steps:** Thêm `throttle:5,1` cho POST `/login`, `throttle:3,5` cho POST `/register`
- **Acceptance criteria:** Request thứ 6/phút trả về 429
- **Definition of Done:** Brute-force bị chặn

---

### SEC-TASK-010: Đồng bộ chính sách mật khẩu

- **Task ID:** SEC-TASK-010
- **Related finding:** SEC-010
- **Title:** Thống nhất password policy
- **Priority:** P2
- **Complexity:** S
- **Affected files:** `app/Http/Controllers/AuthController.php`
- **Definition of Done:** Password policy nhất quán giữa register và change-password

---

### SEC-TASK-011: Thêm audit logging

- **Task ID:** SEC-TASK-011
- **Related finding:** SEC-011
- **Title:** Log hành động admin
- **Priority:** P2
- **Complexity:** M
- **Definition of Done:** Tất cả hành động admin được log

---

### SEC-TASK-012: Invalidate session sau đổi mật khẩu

- **Task ID:** SEC-TASK-012
- **Related finding:** SEC-012
- **Title:** Đăng xuất session khác khi đổi password
- **Priority:** P2
- **Complexity:** S
- **Definition of Done:** Session khác bị hủy sau đổi mật khẩu

---

### SEC-TASK-013: Fix DOM XSS trong JavaScript

- **Task ID:** SEC-TASK-013
- **Related finding:** SEC-013
- **Title:** Thay innerHTML bằng textContent/DOM API
- **Priority:** P1
- **Complexity:** M
- **Affected files:** `resources/views/orders/create.blade.php`, `resources/views/orders/checkout.blade.php`
- **Dependency:** SEC-TASK-002 (fix authorization trước)
- **Definition of Done:** Không có XSS execution với bất kỳ product name nào

---

### SEC-TASK-014: Bật SESSION_SECURE_COOKIE cho production

- **Task ID:** SEC-TASK-014
- **Related finding:** SEC-006
- **Title:** Bật Secure cookie flag
- **Priority:** P2
- **Complexity:** S
- **Dependency:** HTTPS deployment
- **Definition of Done:** Cookie có flag `Secure`

---

## 6. Quick Wins

| # | Thay đổi | File | Effort | Impact |
|---|---|---|---|---|
| 1 | Thay `$request->all()` → `$request->only([...])` | ProductController.php | 5 phút | Chặn mass assignment |
| 2 | Di chuyển product CRUD routes vào nhóm `admin` | routes/web.php | 5 phút | Chặn unauthorized access |
| 3 | Thêm `throttle:5,1` cho POST /login | routes/web.php | 2 phút | Chặn brute-force |
| 4 | Đặt `APP_DEBUG=false` trong production | .env | 1 phút | Ẩn stack trace |
| 5 | Thay `env()` → `config()` trong OrderController | OrderController.php + config/ | 15 phút | Fix config:cache |

---

## 7. Security Test Plan

### PHPUnit / Pest Feature Tests

| Test | Mô tả |
|---|---|
| `test_user_cannot_create_product` | User role `user` POST `/products` → 302/403 |
| `test_user_cannot_delete_product` | User role `user` DELETE `/products/1` → 302/403 |
| `test_admin_can_create_product` | Admin POST `/products` → 302 success |
| `test_mass_assignment_blocked` | POST `/products` với `sold=999` → `sold` = 0 |
| `test_login_throttle` | 6 requests → 429 response |
| `test_user_dashboard_hides_revenue` | User GET `/` → response không chứa `totalRevenue` |
| `test_disabled_user_redirected` | User `status=waiting` GET `/` → redirect login |
| `test_password_change_invalidates_sessions` | Đổi password → session khác bị hủy |

### Authorization Tests

| Test | Mô tả |
|---|---|
| `test_user_cannot_access_orders_index` | User GET `/orders` → redirect |
| `test_user_cannot_access_users_index` | User GET `/users` → redirect |
| `test_user_can_create_order` | User POST `/orders` → success |
| `test_unauthenticated_redirected` | Guest GET `/` → redirect login |

### Manual/Tool-based Tests

| Công cụ | Mục đích |
|---|---|
| `composer audit` | Kiểm tra dependency vulnerabilities |
| `npm audit` | Kiểm tra frontend dependency vulnerabilities |
| `php artisan route:list` | Verify middleware assignments |
| Browser DevTools | Kiểm tra cookie flags |

---

## 8. Production Readiness Checklist

- [x] Authentication: Session-based, login/logout, password hashing
- [ ] Authorization: Product routes thiếu admin middleware ❌
- [ ] Validation: `$request->all()` thay vì `$request->only()` ❌
- [x] Session: Database driver, HttpOnly=true, SameSite=lax
- [x] CSRF: `@csrf` trên tất cả form
- [x] CORS: Không có API routes — Not Applicable
- [ ] Cookie: `SESSION_SECURE_COOKIE` chưa bật ❌
- [ ] Rate limit: Không có cho login/register ❌
- [ ] Secret: Cần kiểm tra Git history ⚠️
- [ ] Debug mode: `APP_DEBUG=true` ❌
- [ ] Error handling: Stack trace lộ khi debug=true ❌
- [ ] Logging: Thiếu audit log ❌
- [x] File storage: Storage link đúng, upload validation có
- [ ] Database: Root user không password ❌
- [ ] Dependency: Chưa audit ⚠️
- [x] Queue: Database driver, OK
- [x] Scheduler: Không có — Not Applicable

---

## 9. Residual Risks

| Rủi ro | Lý do | Đề xuất |
|---|---|---|
| Dependency vulnerabilities | Cần runtime | Chạy `composer audit` / `npm audit` |
| TLS/HTTPS configuration | Phụ thuộc hạ tầng | Kiểm tra web server config |
| Database backup strategy | Phụ thuộc hạ tầng | Thiết lập `mysqldump` cron |
| Network-level security | Phụ thuộc firewall | Review security group |

---

## 10. Recommended Execution Order

### Top 5 task cần thực hiện đầu tiên

| Thứ tự | Task ID | Lý do |
|---|---|---|
| 1 | SEC-TASK-002 | **Critical** — Chặn user thường chiếm quyền quản lý sản phẩm |
| 2 | SEC-TASK-001 | **Critical** — Chặn mass assignment |
| 3 | SEC-TASK-005 | **High** — Thu hồi quyền truy cập ngay lập tức |
| 4 | SEC-TASK-009 | **High** — Chặn brute-force trước khi deploy |
| 5 | SEC-TASK-004 | **High** — Fix env() trước khi config:cache |

### Task có dependency

| Task | Phụ thuộc |
|---|---|
| SEC-TASK-013 (fix XSS) | SEC-TASK-002 (authorization) |
| SEC-TASK-014 (secure cookie) | HTTPS deployment |

### Điều kiện bắt buộc trước khi triển khai production

1. Fix SEC-TASK-001 + SEC-TASK-002 (Critical authorization)
2. Fix SEC-TASK-006 (`APP_DEBUG=false`)
3. Fix SEC-TASK-007 (database credential)
4. Fix SEC-TASK-004 (`env()` → `config()`)
5. Fix SEC-TASK-009 (rate limiting)
6. Chạy `composer audit` và `npm audit`
7. Thiết lập HTTPS
8. Thiết lập backup strategy
