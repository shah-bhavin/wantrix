<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Meta Graph API
    |--------------------------------------------------------------------------
    |
    | Base configuration used for all Meta Graph API requests.
    |
    */

    'graph_version' => env('META_GRAPH_VERSION', 'v26.0'),

    'graph_url' => env(
        'META_GRAPH_URL',
        'https://graph.facebook.com'
    ),

    /*
    |--------------------------------------------------------------------------
    | Meta App
    |--------------------------------------------------------------------------
    |
    | These credentials identify the Meta App used by Wantrix.
    |
    */

    'app_id' => env('META_APP_ID'),

    'app_secret' => env('META_APP_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Embedded Signup
    |--------------------------------------------------------------------------
    |
    | Configuration used when a customer connects their WhatsApp
    | Business Account to Wantrix through Meta Embedded Signup.
    |
    */

    'embedded_signup' => [

        'config_id' => env('META_CONFIG_ID'),

    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook
    |--------------------------------------------------------------------------
    |
    | Token used by Meta when verifying the Wantrix webhook endpoint.
    |
    */

    'webhook' => [

        'verify_token' => env('META_WEBHOOK_VERIFY_TOKEN'),

    ],

];