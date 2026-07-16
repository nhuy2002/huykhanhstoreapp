<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng mới #{{ $order->code }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Fallback cho Outlook */
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        /* Thiết lập Font */
        body, table, td, a, h1, h2, h3, p {
            font-family: 'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
        }

        /* Reset CSS */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        
        /* Màu nền tổng thể - Xám xanh nhạt sang trọng */
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f4f6f8; color: #333333; }
        
        /* Utility Classes */
        .wrapper { width: 100%; table-layout: fixed; background-color: #f4f6f8; padding-bottom: 60px; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 640px; border-spacing: 0; color: #333333; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        
        /* Typography */
        .header-title { color: #ffffff; font-size: 18px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; }
        .section-title { color: #004a99; font-size: 14px; font-weight: 700; text-transform: uppercase; border-bottom: 2px solid #f0f0f0; padding-bottom: 8px; margin-bottom: 15px; }
        .label { color: #64748b; font-size: 12px; font-weight: 500; text-transform: uppercase; margin-bottom: 4px; }
        .value { color: #1e293b; font-size: 15px; font-weight: 600; line-height: 1.4; }
        
        /* Mobile Responsive */
        @media screen and (max-width: 600px) {
            .main { width: 100% !important; border-radius: 0 !important; }
            .mobile-padding { padding-left: 20px !important; padding-right: 20px !important; }
            .mobile-block { display: block !important; width: 100% !important; margin-bottom: 25px !important; }
            .mobile-hide { display: none !important; }
        }
    </style>
</head>
<body>

    <center class="wrapper">
        <table class="main" width="100%">
            
            <tr>
                <td style="background-color: #004a99; padding: 25px 40px; text-align: center;">
                    <table width="100%">
                        <tr>
                            <td align="left">
                                <span class="header-title">HUY KHANH COMPUTER</span>
                            </td>
                            <td align="right">
                                <span style="background: rgba(255,255,255,0.2); color: #fff; padding: 4px 10px; border-radius: 4px; font-size: 12px;">Admin Notification</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="padding: 30px 40px 10px 40px;" class="mobile-padding">
                    <p style="margin: 0 0 20px 0; font-size: 15px; color: #555;">
                        Xin chào Admin, hệ thống vừa ghi nhận một đơn hàng mới.
                    </p>

                    <table width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 20px;">
                        <tr>
                            <td style="padding: 20px; text-align: center; border-right: 1px solid #e2e8f0;" width="50%">
                                <div class="label">Mã đơn hàng</div>
                                <div style="font-size: 18px; font-weight: 700; color: #004a99;">#{{ $order->code }}</div>
                            </td>
                            <td style="padding: 20px; text-align: center;">
                                <div class="label">Tổng giá trị</div>
                                <div style="font-size: 18px; font-weight: 700; color: #ef4444;">{{ number_format($order->total_amount, 0, ',', '.') }} đ</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="padding: 10px 40px;" class="mobile-padding">
                    <table width="100%">
                        <tr>
                            <td width="50%" valign="top" class="mobile-block" style="padding-right: 20px;">
                                <div class="section-title">Khách hàng</div>
                                <div style="margin-bottom: 15px;">
                                    <div class="label">Họ và tên</div>
                                    <div class="value">{{ $order->customer_name }}</div>
                                </div>
                                <div style="margin-bottom: 15px;">
                                    <div class="label">Số điện thoại</div>
                                    <div class="value">{{ $order->customer_phone }}</div>
                                </div>
                                <div>
                                    <div class="label">Địa chỉ / Ghi chú</div>
                                    <div class="value" style="font-size: 14px; color: #555;">
                                        {{ $order->note ? $order->note : 'Không có ghi chú' }}
                                    </div>
                                </div>
                            </td>

                            <td width="50%" valign="top" class="mobile-block">
                                <div class="section-title">Thanh toán</div>
                                <div style="margin-bottom: 15px;">
                                    <div class="label">Hình thức</div>
                                    <div class="value">
                                        @if($order->payment_method == 'transfer')
                                            <span style="color: #004a99;">● Chuyển khoản (VietQR)</span>
                                        @else
                                            <span style="color: #333;">● Tiền mặt</span>
                                        @endif
                                    </div>
                                </div>
                                <div style="margin-bottom: 15px;">
                                    <div class="label">Thời gian tạo</div>
                                    <div class="value" style="font-weight: 500;">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                                </div>

                                @if($order->payment_proof)
                                    <div style="margin-top: 15px; border: 1px dashed #004a99; padding: 10px; background: #f0f7ff; border-radius: 6px;">
                                        <div class="label" style="color: #004a99; margin-bottom: 8px;">Ảnh xác thực:</div>
                                        
                                        {{-- LOGIC EMBED ẢNH (QUAN TRỌNG) --}}
                                        <img src="{{ $message->embed(storage_path('app/public/' . $order->payment_proof)) }}" 
                                             alt="Payment Proof" 
                                             style="width: 100%; height: auto; border-radius: 4px; display: block; border: 1px solid #dbeafe;">
                                    </div>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="padding: 30px 40px;" class="mobile-padding">
                    <div class="section-title">Chi tiết đơn hàng</div>
                    <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden;">
                        <thead style="background-color: #f1f5f9;">
                            <tr>
                                <th align="left" style="padding: 12px 15px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Sản phẩm</th>
                                <th align="center" style="padding: 12px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">SL</th>
                                <th align="right" style="padding: 12px 15px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr style="border-top: 1px solid #e2e8f0;">
                                <td style="padding: 12px 15px; font-size: 14px; color: #334155; line-height: 1.4;">
                                    {{ $item->product_name }}
                                </td>
                                <td align="center" style="padding: 12px; font-size: 14px; font-weight: 600; color: #334155;">{{ $item->quantity }}</td>
                                <td align="right" style="padding: 12px 15px; font-size: 14px; font-weight: 700; color: #1e293b;">
                                    {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background-color: #f8fafc;">
                            <tr>
                                <td colspan="2" align="right" style="padding: 15px; font-size: 13px; color: #64748b; font-weight: 600;">TỔNG THANH TOÁN</td>
                                <td align="right" style="padding: 15px; font-size: 16px; font-weight: 700; color: #004a99;">
                                    {{ number_format($order->total_amount, 0, ',', '.') }} đ
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="padding: 10px 40px 40px 40px; text-align: center;" class="mobile-padding">
                    <a href="{{ route('orders.index') }}" style="background-color: #004a99; color: #ffffff; padding: 14px 30px; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-block; border-radius: 6px; box-shadow: 0 4px 6px rgba(0, 74, 153, 0.2);">
                        Truy cập hệ thống quản trị
                    </a>
                </td>
            </tr>

            <tr>
                <td style="background-color: #1e293b; color: #94a3b8; padding: 25px 40px; font-size: 12px; text-align: center; line-height: 1.6;">
                    <strong>Huy Khanh Computer System</strong><br>
                    Email này được gửi tự động. Vui lòng không trả lời.<br>
                    &copy; {{ date('Y') }} All rights reserved.
                </td>
            </tr>

        </table>
    </center>
</body>
</html>