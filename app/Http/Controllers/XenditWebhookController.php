<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Models\Order;
use App\Models\Product;

class XenditWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        Log::info('xendit.webhook', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
        ]);

        $callbackToken = config('services.xendit.callback_token');
        $providedToken = $request->header('x-callback-token');
        if ($callbackToken && $providedToken !== $callbackToken) {
            return Response::json(['ok' => false, 'message' => 'invalid token'], 401);
        }

        $data = $request->all();
        $externalId = $data['external_id'] ?? null;
        $status = strtolower($data['status'] ?? '');

        if (!$externalId) {
            return Response::json(['ok' => false, 'message' => 'invalid payload'], 200);
        }

        $order = Order::where('order_number', $externalId)->first();
        if (! $order) {
            return Response::json(['ok' => false, 'message' => 'order not found'], 200);
        }

        if (in_array($status, ['active','succeeded','paid','completed','settlement'])) {
            if ($order->status !== 'completed') {
                $order->status = 'completed';
                $order->paid_at = now();
                foreach ($order->orderItems as $oi) {
                    Product::where('id', $oi->product_id)->decrement('stock', $oi->quantity);
                }
            }
        } elseif (in_array($status, ['expired','canceled','cancelled','failed'])) {
            $order->status = 'cancelled';
        } else {
            $order->status = 'pending';
        }

        $order->save();
        return Response::json(['ok' => true], 200);
    }
}


