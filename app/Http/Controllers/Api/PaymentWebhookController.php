<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function telegram(Request $request, string $secret)
    {
        $expectedSecret = (string) env('WEBHOOK_SECRET');
        if (!$expectedSecret || !hash_equals($expectedSecret, $secret)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $payload = $request->all();
        Log::info('telegram.webhook.payload', $payload);

        // Support messages from groups and channels
        $message = $payload['message']['text']
            ?? $payload['edited_message']['text']
            ?? $payload['channel_post']['text']
            ?? $payload['edited_channel_post']['text']
            ?? null;

        $chatId = (string) (
            $payload['message']['chat']['id']
            ?? $payload['edited_message']['chat']['id']
            ?? $payload['channel_post']['chat']['id']
            ?? $payload['edited_channel_post']['chat']['id']
            ?? ''
        );

        $allowedChatId = (string) env('TELEGRAM_ALLOWED_CHAT_ID', '');
        if ($allowedChatId && $chatId && $chatId !== $allowedChatId) {
            Log::warning('telegram.webhook.ignored_chat', ['chat_id' => $chatId]);
            return response()->json(['message' => 'ignored'], 200);
        }

        if (!$message) {
            return response()->json(['message' => 'no message'], 200);
        }

        // TEMP: Echo back to confirm webhook delivery path end-to-end
        try {
            $this->notifyTelegram("Webhook OK: " . mb_substr($message, 0, 120), $chatId);
        } catch (\Throwable $e) {
            Log::warning('telegram.webhook.echo_failed', ['error' => $e->getMessage()]);
        }

        // Parse amount from message (supports formats like: 50.123, 50,123, 50123, Rp 50.123)
        // Strategy: get largest integer found in the text as amount in IDR
        preg_match_all('/\d[\d\.,]*/', $message, $matches);
        $candidates = $matches[0] ?? [];
        $amount = null;
        foreach ($candidates as $raw) {
            $normalized = (int) preg_replace('/[\.,]/', '', $raw);
            if ($normalized > 0) {
                if ($amount === null || $normalized > $amount) {
                    $amount = $normalized;
                }
            }
        }

        if (!$amount) {
            Log::warning('telegram.webhook.no_amount_parsed', ['message' => $message]);
            return response()->json(['message' => 'no amount found'], 200);
        }

        // Match to transfer orders by transfer_amount first (DB-agnostic),
        // then fallback by computing (round(total) + unique_code) in PHP to avoid DB-specific casts
        $order = Order::where('payment_method', 'transfer')
            ->where('payment_status', 'pending')
            ->where('transfer_amount', $amount)
            ->latest()
            ->first();

        if (!$order) {
            // Fallback: scan a recent window of pending transfer orders and compute match in PHP
            $candidates = Order::where('payment_method', 'transfer')
                ->where('payment_status', 'pending')
                ->latest()
                ->limit(50)
                ->get();
            foreach ($candidates as $candidate) {
                $expected = (int) round((float) $candidate->total) + (int) ($candidate->unique_code ?? 0);
                if ($expected === (int) $amount) {
                    $order = $candidate;
                    break;
                }
            }
        }

        if (!$order) {
            Log::warning('telegram.webhook.order_not_found', ['amount' => $amount]);
            return response()->json(['message' => 'order not found for amount'], 200);
        }

        // Update order: mark verified and completed, decrease stock
        $order->update([
            'payment_status' => 'verified',
            'transfer_verified_at' => now(),
            'status' => 'completed',
            'transfer_notes' => trim("Auto-verified via Telegram. Message: " . mb_substr($message, 0, 200)),
        ]);

        foreach ($order->orderItems as $item) {
            Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
        }

        // Optional: notify back via Telegram (fallback to source chat if notify id not set)
        $this->notifyTelegram(
            "✅ Transfer terverifikasi otomatis. Order: {$order->order_number}, Amount: Rp " . number_format($amount, 0, ',', '.'),
            $chatId
        );

        return response()->json(['message' => 'ok', 'order_id' => $order->id]);
    }

    private function notifyTelegram(string $text, ?string $fallbackChatId = null): void
    {
        $token = (string) env('TELEGRAM_BOT_TOKEN');
        $chatId = (string) env('TELEGRAM_NOTIFY_CHAT_ID', env('TELEGRAM_ALLOWED_CHAT_ID', ''));
        if (!$chatId && $fallbackChatId) {
            $chatId = (string) $fallbackChatId;
        }
        if (!$token || !$chatId) {
            Log::warning('telegram.notify.skipped', ['reason' => 'missing_token_or_chat', 'has_token' => (bool) $token, 'chat_id' => $chatId]);
            return;
        }
        try {
            $url = "https://api.telegram.org/bot{$token}/sendMessage";
            $resp = \Illuminate\Support\Facades\Http::asForm()->post($url, [
                'chat_id' => $chatId,
                'text' => $text,
            ]);
            Log::info('telegram.notify.sent', ['status' => $resp->status(), 'body' => $resp->json()]);
        } catch (\Throwable $e) {
            Log::error('telegram.notify.failed', ['error' => $e->getMessage()]);
        }
    }
}


