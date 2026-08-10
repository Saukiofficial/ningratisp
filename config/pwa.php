<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Would you like the install button to appear on all pages?
      Set true/false
    |--------------------------------------------------------------------------
    */

    'install-button' => true,

    /*
    |--------------------------------------------------------------------------
    | PWA Manifest Configuration
    |--------------------------------------------------------------------------
    |  php artisan erag:update-manifest
    */

    'manifest' => [
        'name' => env('APP_NAME') ?? 'WiFi App',
        'short_name' => 'NingratApp',
        'start_url' => '/customer',
        'background_color' => '#6777ef',
        'display' => 'fullscreen',
        'description' => 'Customer wifi system App',
        'theme_color' => '#6777ef',
        'icons' => [
            [
                'src' => 'logo.png',
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'any maskable',
            ],
            [
                'src' => 'logo.png',
                'sizes' => '192x192',
                'type' => 'image/png',
                'purpose' => 'any maskable',
            ],
        ],
        'screenshots' => [
            [
                'src' => 'assets/img/pwa/ss-1.png',
                'sizes' => '520x999',
                'types' => 'image/png',
                'label' => 'Modern login page',
                'form_factor' => 'narrow',
            ],
            [
                'src' => 'assets/img/pwa/ss-2.png',
                'sizes' => '520x999',
                'types' => 'image/png',
                'label' => 'Dashboard full control with nice view',
                'form_factor' => 'narrow',
            ],
            [
                'src' => 'assets/img/pwa/ss-3.png',
                'sizes' => '520x999',
                'types' => 'image/png',
                'label' => 'Confirm payment for better clarify',
                'form_factor' => 'narrow',
            ],
        ],
        'display_override' => ['fullscreen', 'minimal-ui'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Debug Configuration
    |--------------------------------------------------------------------------
    | Toggles the application's debug mode based on the environment variable
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Livewire Integration
    |--------------------------------------------------------------------------
    | Set to true if you're using Livewire in your application to enable
    | Livewire-specific PWA optimizations or features.
    */

    'livewire-app' => false,
];
