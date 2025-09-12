<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Transaction;

class MidtransReconcile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'midtrans:reconcile {order_number? : Specific order_number to reconcile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reconcile pending Midtrans orders by fetching latest transaction status';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Configure Midtrans SDK
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        $specificOrderNumber = $this->argument('order_number');

        $query = Order::query()
            ->where('payment_method', 'midtrans')
            ->where('payment_status', 'pending');

        if ($specificOrderNumber) {
            $query->where('order_number', $specificOrderNumber);
        }

        $count = 0;
        $query->orderBy('id')->chunk(50, function ($orders) use (&$count) {
            foreach ($orders as $order) {
                try {
                    $status = Transaction::status($order->order_number);
                    $transactionStatus = $status->transaction_status ?? null;
                    $fraudStatus = $status->fraud_status ?? '';

                    if (!$transactionStatus) {
                        continue;
                    }

                    $originalPaymentStatus = $order->payment_status;
                    $originalStatus = $order->status;

                    if ($transactionStatus === 'capture') {
                        if ($fraudStatus === 'challenge') {
                            $order->payment_status = 'pending';
                            $order->status = 'pending';
                        } else {
                            $order->payment_status = 'paid';
                            $order->status = 'completed';
                            $this->decrementStock($order);
                        }
                    } elseif ($transactionStatus === 'settlement') {
                        $order->payment_status = 'paid';
                        $order->status = 'completed';
                        $this->decrementStock($order);
                    } elseif ($transactionStatus === 'pending') {
                        $order->payment_status = 'pending';
                        $order->status = 'pending';
                    } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                        $order->payment_status = 'failed';
                        $order->status = 'cancelled';
                    }

                    if ($order->isDirty(['payment_status', 'status'])) {
                        $order->save();
                        $count++;
                        Log::info('Midtrans reconcile updated order', [
                            'order_id' => $order->order_number,
                            'from' => [$originalStatus, $originalPaymentStatus],
                            'to' => [$order->status, $order->payment_status],
                        ]);
                    }
                } catch (\Throwable $e) {
                    Log::error('Midtrans reconcile error', [
                        'order_id' => $order->order_number,
                        'message' => $e->getMessage(),
                    ]);
                }
            }
        });

        $this->info("Reconcile done. Updated {$count} order(s).");
        return Command::SUCCESS;
    }

    private function decrementStock(Order $order): void
    {
        foreach ($order->orderItems as $item) {
            $product = $item->product;
            if ($product) {
                $product->stock -= $item->quantity;
                $product->save();
            }
        }
    }
}


