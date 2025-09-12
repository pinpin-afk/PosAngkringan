<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create Snap Token for payment
     */
    public function createSnapToken(Request $request)
    {
        try {
            // Validate server key configured
            if (!config('midtrans.server_key')) {
                throw new \RuntimeException('MIDTRANS_SERVER_KEY is not set. Please configure your .env');
            }
            $order = Order::findOrFail($request->order_id);
            
            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) round($order->total),
                ],
                'customer_details' => [
                    'first_name' => $order->customer_name ?: 'Customer',
                    'phone' => $order->customer_phone ?: '08123456789',
                ],
                'item_details' => $order->orderItems->map(function ($item) {
                    return [
                        'id' => $item->product_id,
                        'price' => (int) round($item->price),
                        'quantity' => (int) $item->quantity,
                        'name' => $item->product->name,
                    ];
                })->toArray(),
                'callbacks' => [
                    'finish' => config('app.url') . '/pos/payment/success',
                    'unfinish' => config('app.url') . '/pos/payment/unfinish',
                    'error' => config('app.url') . '/pos/payment/error',
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $order->order_number,
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Midtrans webhook notification
     */
    public function webhook(Request $request)
    {
        try {
            // Log incoming webhook for debugging
            // Log::info('Midtrans webhook received', $request->all());
            
            $notification = new Notification();
            
            $orderNumber = $notification->order_id;
            $statusCode = $notification->status_code;
            $grossAmount = $notification->gross_amount;
            $signatureKey = $notification->signature_key;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? '';

            // Verify signature
            $serverKey = config('midtrans.server_key');
            $mySignatureKey = hash('sha512', $orderNumber . $statusCode . $grossAmount . $serverKey);
            
            if ($signatureKey !== $mySignatureKey) {
                Log::warning('Midtrans webhook signature mismatch', [
                    'order_id' => $orderNumber,
                    'received_signature' => $signatureKey,
                    'calculated_signature' => $mySignatureKey
                ]);
                return response()->json(['message' => 'Invalid signature'], 400);
            }

            // Find order
            $order = Order::where('order_number', $orderNumber)->first();
            if (!$order) {
                Log::warning('Midtrans webhook: Order not found', ['order_id' => $orderNumber]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Update order status based on transaction status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $order->payment_status = 'pending';
                    $order->status = 'pending';
                } else if ($fraudStatus == 'accept') {
                    $order->payment_status = 'paid';
                    $order->status = 'completed';
                    $this->decrementStock($order);
                }
            } else if ($transactionStatus == 'settlement') {
                $order->payment_status = 'paid';
                $order->status = 'completed';
                $this->decrementStock($order);
            } else if ($transactionStatus == 'pending') {
                $order->payment_status = 'pending';
                $order->status = 'pending';
            } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $order->payment_status = 'failed';
                $order->status = 'cancelled';
            }

            $order->save();

            Log::info('Midtrans webhook processed', [
                'order_id' => $orderNumber,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_status' => $order->payment_status
            ]);

            return response()->json(['message' => 'Webhook processed successfully']);

        } catch (\Exception $e) {
            Log::error('Midtrans webhook error: ' . $e->getMessage());
            return response()->json(['message' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Decrement product stock after successful payment
     */
    private function decrementStock(Order $order)
    {
        foreach ($order->orderItems as $item) {
            $product = $item->product;
            $product->stock -= $item->quantity;
            $product->save();
        }
    }
}
