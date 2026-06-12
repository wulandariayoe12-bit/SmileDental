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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'onopay' => [
        'web_url' => env('ONOPAY_WEB_URL', 'http://onopay.web.id'),
        'api_url' => env('ONOPAY_API_URL', 'http://onopay.web.id/api/v1'),
        'api_key' => env('ONOPAY_API_KEY'),
        'merchant_phone' => env('ONOPAY_MERCHANT_PHONE'),
        'merchant_code' => env('ONOPAY_MERCHANT_CODE', 'SMILEDENTAL'),
        'qris_expiry_minutes' => env('ONOPAY_QRIS_EXPIRY_MINUTES', 30),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
