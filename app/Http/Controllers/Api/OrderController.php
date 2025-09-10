<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderItems.product')->latest()->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'processed_by' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,qris',
            'notes' => 'nullable|string',
            'selected_member' => 'nullable|array',
            'points_earned' => 'nullable|integer|min:0',
        ]);
        $orderNumber = 'ORD-' . date('Ymd') . '-' . Str::random(6);

        $subtotal = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $itemSubtotal = $product->price * $item['quantity'];
            $subtotal += $itemSubtotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $itemSubtotal,
            ];
        }

        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $tax;

        $isDraft = (bool) $request->boolean('draft');
        $status = $isDraft ? 'pending' : ($request->payment_method === 'qris' ? 'pending' : 'completed');

        $processedBy = $request->processed_by
            ?? session('kasir_user_name')
            ?? session('admin_user_name')
            ?? (optional(\Auth::guard('kasir')->user())->name)
            ?? (optional(\Auth::guard('admin')->user())->name)
            ?? (optional(\Auth::user())->name);

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'processed_by' => $processedBy,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => 0,
            'total' => $total,
            'payment_method' => $request->payment_method,
            'status' => $status,
            'notes' => $request->notes,
        ]);
        // dd($order);
        foreach ($orderItems as $item) {
            $item['order_id'] = $order->id;
            OrderItem::create($item);
        }

        // For non-QRIS and not draft, decrease stock immediately
        if ($request->payment_method !== 'qris' && !$isDraft) {
            foreach ($request->items as $item) {
                Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
            }
        }

        // Add points to member only when completed immediately (not draft and not QRIS)
        if (!$isDraft && $request->payment_method !== 'qris') {
            if ($request->selected_member && isset($request->selected_member['id'])) {
                $member = Member::find($request->selected_member['id']);
                if ($member && $request->points_earned > 0) {
                    $member->addPoints($request->points_earned);
                }
            }
        }

        return response()->json($order->load('orderItems.product'), 201);
    }


    public function charge(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'payment_type' => 'required|in:qris,gopay,shopeepay',
            'selected_member' => 'nullable|array',
            'points_earned' => 'nullable|integer|min:0',
        ]);

        $orderNumber = strtoupper($request->payment_type) . '-' . date('Ymd') . '-' . Str::random(10);

        $subtotal = 0;
        $orderItems = [];
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $itemSubtotal = $product->price * $item['quantity'];
            $subtotal += $itemSubtotal;
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $itemSubtotal,
            ];
        }
        $tax = $subtotal * 0.1;
        $total = $subtotal + $tax;

        $processedBy = $request->processed_by
            ?? session('kasir_user_name')
            ?? session('admin_user_name')
            ?? (optional(\Auth::guard('kasir')->user())->name)
            ?? (optional(\Auth::guard('admin')->user())->name)
            ?? (optional(\Auth::user())->name);

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'processed_by' => $processedBy,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => 0,
            'total' => $total,
            // keep payment_method within existing enum values; store specific type in payment_type
            'payment_method' => 'qris',
            'status' => 'pending',
            'notes' => $request->notes,
        ]);
        foreach ($orderItems as $item) {
            $item['order_id'] = $order->id;
            OrderItem::create($item);
        }

        // Use Xendit QRIS to generate QR that can be paid by many apps (incl. GoPay)
        $xSecret = (string) config('services.xendit.secret_key', env('XENDIT_SECRET_KEY'));
        $xBase = (string) config('services.xendit.base_url', 'https://api.xendit.co');

        // Create QR code via Xendit
        $xPayload = [
            'external_id' => $order->order_number,
            'type' => 'DYNAMIC',
            'callback_url' => (string) config('services.xendit.callback_url', url('/api/xendit/webhook')),
            'amount' => (int) round($order->total),
        ];

        $response = Http::withBasicAuth($xSecret, '')
            ->acceptJson()
            ->post($xBase . '/qr_codes', $xPayload);

        if (!$response->ok()) {
            return response()->json([
                'message' => 'Xendit charge failed',
                'detail' => $response->json(),
            ], $response->status() ?: 422);
        }

        $body = $response->json();
        Log::info('xendit.qr.create.response', $body ?? []);
        // Map minimal fields
        $order->midtrans_order_id = $order->order_number;
        $order->midtrans_transaction_id = $body['id'] ?? null;
        $deeplinkUrl = null;
        $qrUrl = null;
        // Xendit returns qr_string under 'qr_string' and an 'image' link under 'qr_code' sometimes
        $order->qr_string = $body['qr_string'] ?? null;
        if (isset($body['qr_string']) && !$order->qr_string) {
            $order->qr_string = $body['qr_string'];
        }
        if (isset($body['qr_code'])) { $qrUrl = $body['qr_code']; }
        // save basic fields first
        $order->save();
        // set payment_type only if column exists
        try {
            if (Schema::hasColumn('orders', 'payment_type')) {
                $order->payment_type = 'qris';
                $order->save();
            }
        } catch (\Throwable $e) {
            Log::warning('orders.payment_type.save_failed', ['error' => $e->getMessage()]);
        }

        // Add points to member if selected (for QRIS, points will be added when payment is confirmed)
        // This will be handled in the webhook when payment is successful

        return response()->json([
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'midtrans_order_id' => $order->midtrans_order_id,
            'qr_string' => $body['qr_string'] ?? null,
            'qr_url' => $qrUrl,
            'deeplink_url' => $deeplinkUrl,
            'status' => $order->status,
            'total' => $order->total,
            'payment_type' => 'qris',
        ], 201);
    }

    public function checkStatus(Order $order)
    {
        return response()->json(['status' => $order->status, 'paid_at' => $order->paid_at]);
    }

    public function show(Order $order)
    {
        return response()->json($order->load('orderItems.product'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
            'selected_member' => 'nullable|array',
            'selected_member.id' => 'nullable|integer|exists:members,id',
            'points_earned' => 'nullable|integer|min:0',
        ]);

        $previousStatus = $order->status;
        $newStatus = $request->input('status');

        // Transition handling
        if ($previousStatus === 'pending' && $newStatus === 'completed') {
            // Decrease stock for each item
            foreach ($order->orderItems as $item) {
                Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
            }
            // Add points to member if provided on completion
            if ($request->filled('selected_member.id') && $request->input('points_earned') > 0) {
                $member = Member::find($request->input('selected_member.id'));
                if ($member) {
                    $member->addPoints((int) $request->input('points_earned'));
                }
            }
        }

        $order->update(['status' => $newStatus]);
        return response()->json($order->load('orderItems.product'));
    }
}
