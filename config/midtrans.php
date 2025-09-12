<?php

return [
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'is_production' => env('MIDTRANS_MODE', 'sandbox') === 'production',
    'is_sanitized' => true,
    'is_3ds' => true,
    'webhook_url' => env('MIDTRANS_WEBHOOK_URL'),
];
