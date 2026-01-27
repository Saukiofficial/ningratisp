<?php

return [
    'manager_host' => env('ZEROTIER_MANAGER_HOST', 'localhost'),
    'manager_port' => env('ZEROTIER_MANAGER_PORT', 9999),
    'proxy_url' => env('ZEROTIER_PROXY_URL', 'http://localhost'),
    'network_id' => env('ZEROTIER_NETWORK_ID'),
    'timeout' => env('ZEROTIER_TIMEOUT', 10),
];
