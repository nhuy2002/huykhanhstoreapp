<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\OrderPlacedMail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Không có quyền truy cập');
        }

        $query = Order::with('items');

        if ($request->has('search') && $request->filled('search')) {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('code', 'like', "%{$keyword}%")
                  ->orWhere('customer_name', 'like', "%{$keyword}%")
                  ->orWhere('customer_phone', 'like', "%{$keyword}%");
            });
        }

        $orders = $query->latest()->paginate(10);

        if ($request->has('search')) {
            $orders->appends(['search' => $request->search]);
        }

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::where('status', 'active')->where('quantity', '>', 0)->get();
        return view('orders.create', compact('products'));
    }

    public function checkout(Request $request)
    {
        $request->validate(['items' => 'required|array|min:1']);
        
        $itemIds = collect($request->items)->pluck('id');
        $products = Product::whereIn('id', $itemIds)->get();
        $productsKeyed = $products->keyBy('id');

        $cartItems = [];
        $totalAmount = 0;

        foreach ($request->items as $item) {
            if (isset($productsKeyed[$item['id']])) {
                $product = $productsKeyed[$item['id']];
                $qty = $item['quantity'];
                $subtotal = $product->price * $qty;
                
                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->image,
                    'price' => $product->price,
                    'quantity' => $qty,
                    'subtotal' => $subtotal
                ];
                $totalAmount += $subtotal;
            }
        }

        $qrUrl = null;
        $qrContent = "";

        if ($totalAmount > 0) {
            $bankId = config('vietqr.bank_id');
            $accountNo = config('vietqr.account_no');
            $accountName = config('vietqr.account_name');
            
            $qrContent = "HKC " . substr(strval(time()), -6); 

            $baseUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-compact.png";
            $params = http_build_query([
                'amount' => $totalAmount,
                'addInfo' => $qrContent,
                'accountName' => $accountName,
            ]);
            
            $qrUrl = $baseUrl . "?" . $params;
        }

        return view('orders.checkout', compact('cartItems', 'totalAmount', 'qrUrl', 'qrContent', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'items' => 'required|array|min:1',
            'payment_proof' => 'nullable|image|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $orderItemsData = [];
            
            $itemIds = collect($request->items)->pluck('id');
            $products = Product::whereIn('id', $itemIds)->lockForUpdate()->get()->keyBy('id');

            foreach ($request->items as $item) {
                $product = $products[$item['id']] ?? null;
                
                if (!$product || $product->quantity < $item['quantity']) {
                    throw new \Exception("Sản phẩm {$product->name} không đủ hàng!");
                }

                $product->decrement('quantity', $item['quantity']);
                $product->increment('sold', $item['quantity']);

                $lineTotal = $product->price * $item['quantity'];
                $subtotal += $lineTotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_image' => $product->image,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $lineTotal,
                ];
            }

            // Xử lý lưu ảnh
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            // Tạo Order
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->note,
                'note' => $request->note,
                'payment_method' => $request->payment_method ?? 'cash',
                'subtotal' => $subtotal,
                'total_amount' => $subtotal,
                'status' => 'completed',
                'payment_proof' => $proofPath,
            ]);

            foreach ($orderItemsData as $data) {
                $order->items()->create($data);
            }

            // Gửi mail
            $adminEmail = config('custom.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new OrderPlacedMail($order));
            }

            DB::commit();

            return redirect()->route('home')->with('order_success', 'Đơn hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'status' => 'required|in:pending,completed,cancelled,shipping',
            'note' => 'nullable|string',
        ]);

        try {
            $order = Order::findOrFail($id);

            $order->update([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'status' => $request->status,
                'note' => $request->note,
            ]);

            return redirect()->back()->with('success', 'Cập nhật đơn hàng thành công!');

        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);

            // Hoàn lại kho
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('quantity', $item->quantity);
                    $product->decrement('sold', $item->quantity);
                }
            }

            $order->items()->delete();
            $order->delete();

            return redirect()->route('orders.index')->with('success', 'Xóa đơn hàng thành công!');

        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}