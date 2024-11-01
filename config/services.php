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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'model' => App\Models\Shop::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'client_id' => env('STRIPE_CLIENT_ID'),
        'account_id' => env('STRIPE_ACCOUNT_ID'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_CLIENT_SECRET'),
        'merchant_id' => env('PAYPAL_PARTNER_MERCHANT_ID'),
        'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
        'sandbox' => env('PAYPAL_SANDBOX_MODE'),
    ],


    'cybersource' => [
        'merchant_id' => env('CYBERSOURCE_MERCHANT_ID'),
        'api_key_id' => env('CYBERSOURCE_API_KEY_ID'),
        'secret' => env('CYBERSOURCE_SECRET'),
        'sandbox' => env('CYBERSOURCE_SANDBOX'),
    ],

    'nexmo' => [
        'key' => env('NEXMO_KEY'),
        'secret' => env('NEXMO_SECRET'),
        'sms_from' => '15556666666',
    ],

    'facebook' => [
        'client_id'     => env('FB_CLIENT_ID'),
        'client_secret' => env('FB_CLIENT_SECRET'),
        'redirect'      => env('APP_URL') . '/socialite/customer/facebook/callback',

        // Pixel
        'pixel_id'      => env('FB_PIXEL_ID'),
        'sessionKey' => env('FB_PIXEL_SESSION_KEY', config('app.name') . '_facebookPixel'),
        'token' => env('FACEBOOK_PIXEL_TOKEN', ''), // Only if you plan using Conversions API for server events
        'test_event_code' => env('FACEBOOK_TEST_EVENT_CODE') // This is used to test server events
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('APP_URL') . '/socialite/customer/google/callback',
        'place_api_key' => env('GOOGLE_PLACE_KEY'),
        'gtm_container_id' => env('GTM_CONTAINER_ID'),
    ],

    'recaptcha' => [
        'key' => env('GOOGLE_RECAPTCHA_KEY'),
        'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
    ],

    'pusher' => [
        'id' => env('PUSHER_APP_ID'),
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'cluster' => env('PUSHER_APP_CLUSTER'),
    ],

    'twitter' => [
        'pixel_id'      => env('TWITTER_PIXEL_ID'),
    ],

    'tiktok' => [
        'pixel_id'      => env('TIKTOK_PIXEL_ID'),
    ],

    'linkedin' => [
        'partner_id'      => env('LINKEDIN_PARTNER_ID'),
    ],

    'pinterest' => [
        'pixel_id'      => env('PINTEREST_PIXEL_ID'),
    ],
];
