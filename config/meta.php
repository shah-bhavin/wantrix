<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Meta Graph API
    |--------------------------------------------------------------------------
    */

    'graph_version' => env('META_GRAPH_VERSION', 'v25.0'),

    'graph_url' => env(
        'META_GRAPH_URL',
        'https://graph.facebook.com'
    ),

];