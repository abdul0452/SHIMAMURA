<?php

return [
    // Ambil dari Midtrans Dashboard > Settings > Access Keys
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),

    // false = pakai Sandbox (uji coba), true = akun production yang sudah live
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    // Biarkan default true, aman untuk kebanyakan kasus
    'is_sanitized' => true,
    'is_3ds' => true,
];
