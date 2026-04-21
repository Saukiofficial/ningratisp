<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'midtrans' => [
        'url' => env('MTRANS_URL'),
        'merchant_id' => env('MTRANS_MERCHANT_ID'),
        'client_key' => env('MTRANS_CLIENT'),
        'server_key' => env('MTRANS_KEY'),
        'url_stg' => env('MTRANS_URL_STG'),
        'merchant_id_stg' => env('MTRANS_MERCHANT_ID_STG'),
        'client_key_stg' => env('MTRANS_CLIENT_STG'),
        'server_key_stg' => env('MTRANS_KEY_STG'),
    ],

];
