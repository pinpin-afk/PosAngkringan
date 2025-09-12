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
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'processed_by' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,qris,transfer,midtrans',
            'notes' => 'nullable|string',
            'selected_member' => 'nullable|array',
            'points_earned' => 'nullable|integer|min:0',
            'idempotency_key' => 'nullable|string|max:191',
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
        // Draft orders should be marked as 'draft' to be listed and editable via draft endpoints
        $status = $isDraft ? 'draft' : (in_array($request->payment_method, ['qris','transfer','midtrans']) ? 'pending' : 'completed');

        $processedBy = $request->processed_by
            ?? session('kasir_user_name')
            ?? session('admin_user_name')
            ?? (optional(\Auth::guard('kasir')->user())->name)
            ?? (optional(\Auth::guard('admin')->user())->name)
            ?? (optional(\Auth::user())->name);

        // Idempotency: if idempotency_key provided, return existing matching pending order
        if (!empty($validated['idempotency_key'])) {
            $existing = Order::where('idempotency_key', $validated['idempotency_key'])->first();
            if ($existing) {
                return response()->json($existing->load('orderItems.product'), 200);
            }
        } else {
            // Fallback dedupe: same processed_by + method + total, created within last 2 minutes and still pending
            $existing = Order::where('processed_by', $processedBy)
                ->where('payment_method', $request->payment_method)
                ->where('total', $total)
                ->where('status', 'pending')
                ->where('created_at', '>=', now()->subMinutes(2))
                ->latest()
                ->first();
            if ($existing) {
                return response()->json($existing->load('orderItems.product'), 200);
            }
        }

        // Generate unique transfer code when payment_method is transfer
        $uniqueCode = null;
        $transferAmount = null;
        if ($request->payment_method === 'transfer') {
            // 3-digit unique code 101-999 to avoid very small deltas
            $uniqueCode = random_int(101, 999);
            $transferAmount = (int) round($total) + $uniqueCode;
        }

        $order = Order::create([
            'order_number' => $orderNumber,
            'idempotency_key' => $validated['idempotency_key'] ?? null,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'processed_by' => $processedBy,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => 0,
            'total' => $total,
            'unique_code' => $uniqueCode,
            'transfer_amount' => $transferAmount,
            'payment_method' => $request->payment_method,
            'payment_status' => in_array($request->payment_method, ['qris', 'transfer', 'midtrans']) ? 'pending' : 'paid',
            'status' => $status,
            'notes' => $request->notes,
        ]);
        // dd($order);
        foreach ($orderItems as $item) {
            $item['order_id'] = $order->id;
            OrderItem::create($item);
        }

        // For methods other than qris/transfer/midtrans and not draft, decrease stock immediately
        if (!in_array($request->payment_method, ['qris', 'transfer', 'midtrans']) && !$isDraft) {
            foreach ($request->items as $item) {
                Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
            }
        }

        // Add points to member only when completed immediately (not draft, not qris/transfer/midtrans)
        if (!$isDraft && !in_array($request->payment_method, ['qris', 'transfer', 'midtrans'])) {
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
            'payment_status' => 'pending',
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

    public function verifyTransfer(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,verified,paid,failed',
            'transfer_notes' => 'nullable|string|max:1000',
        ]);

        $order->update([
            'payment_status' => $request->payment_status,
            'transfer_verified_at' => $request->payment_status === 'verified' ? now() : null,
            'transfer_notes' => $request->transfer_notes,
        ]);

        // If payment is verified and order is pending, complete the order
        if ($request->payment_status === 'verified' && $order->status === 'pending') {
            $order->update(['status' => 'completed']);
            
            // Decrease stock for each item
            foreach ($order->orderItems as $item) {
                Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diperbarui',
            'order' => $order->load('orderItems.product')
        ]);
    }

    public function report(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');
        $cashier = $request->query('cashier');
        $method = $request->query('method');
        $status = $request->query('status');

        $query = Order::query()->with('orderItems');
        if ($from) { $query->where('created_at', '>=', $from); }
        if ($to) { $query->where('created_at', '<=', $to); }
        if ($cashier) { $query->where('processed_by', $cashier); }
        if ($method) { $query->where('payment_method', $method); }
        if ($status) { $query->where('status', $status); }

        $orders = $query->orderByDesc('created_at')->get();

        $totalSales = (float) $orders->sum('total');
        $totalOrders = (int) $orders->count();
        $itemsCount = (int) $orders->sum(function ($o) { return $o->orderItems->sum('quantity'); });
        $avgOrder = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Breakdown per method
        $byMethod = $orders->groupBy('payment_method')->map(function($grp){
            $sum = (float) $grp->sum('total');
            return [ 'count' => $grp->count(), 'total' => $sum ];
        });

        return response()->json([
            'summary' => [
                'total_sales' => $totalSales,
                'total_orders' => $totalOrders,
                'total_items' => $itemsCount,
                'avg_order' => $avgOrder,
                'by_method' => $byMethod,
            ],
            'orders' => $orders,
        ]);
    }

    /**
     * Get draft orders for current cashier
     */
    public function getDrafts(Request $request)
    {
        $cashier = auth('kasir')->user()->name;
        
        $drafts = Order::where('processed_by', $cashier)
            ->where('status', 'draft')
            ->with('orderItems.product')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($drafts);
    }

    /**
     * Delete a draft order
     */
    public function deleteDraft($id, Request $request)
    {
        $cashier = auth('kasir')->user()->name;
        
        $draft = Order::where('id', $id)
            ->where('processed_by', $cashier)
            ->where('status', 'draft')
            ->first();

        if (!$draft) {
            return response()->json(['message' => 'Draft order not found'], 404);
        }

        $draft->delete();

        return response()->json(['message' => 'Draft order deleted successfully']);
    }

    /**
     * Update a draft order
     */
    public function updateDraft($id, Request $request)
    {
        $cashier = auth('kasir')->user()->name;
        
        $draft = Order::where('id', $id)
            ->where('processed_by', $cashier)
            ->where('status', 'draft')
            ->first();

        if (!$draft) {
            return response()->json(['message' => 'Draft order not found'], 404);
        }

        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'table_number' => 'nullable|string|max:10',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Update order details
        $draft->update([
            'customer_name' => $validated['customer_name'] ?? '',
            'customer_phone' => $validated['customer_phone'] ?? '',
            'table_number' => $validated['table_number'] ?? '',
            'notes' => $validated['notes'] ?? '',
        ]);

        // Update order items
        $draft->orderItems()->delete();
        
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $itemTotal = $product->price * $item['quantity'];
            $subtotal += $itemTotal;

            $draft->orderItems()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'total' => $itemTotal,
            ]);
        }

        // Recalculate totals
        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $tax;

        $draft->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ]);

        return response()->json([
            'message' => 'Draft order updated successfully',
            'order' => $draft->load('orderItems.product')
        ]);
    }

    /**
     * Cancel an order (set status to cancelled and payment_status to failed)
     */
    public function cancelOrder($id, Request $request)
    {
        $cashier = auth('kasir')->user()->name;
        
        $order = Order::where('id', $id)
            ->where('processed_by', $cashier)
            ->whereIn('status', ['pending', 'draft'])
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found or cannot be cancelled'], 404);
        }

        $order->update([
            'status' => 'cancelled',
            'payment_status' => 'failed'
        ]);

        return response()->json([
            'message' => 'Order cancelled successfully',
            'order' => $order
        ]);
    }
}
