<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Version
    |--------------------------------------------------------------------------
    |
    | This value is the version of your application. This value is used when
    | the framework needs to place the application's version in a notification
    | or any other location as required by the application or its packages.
    */

    'version' => trim(file_get_contents(__DIR__ . '/../VERSION')),

    /*
    |--------------------------------------------------------------------------
    | Application Documentation
    |--------------------------------------------------------------------------
    |
    | A URL to where you can find documentation on the API.
    */

    'documentation_url' => env('APP_DOCUMENTATION_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Production URL
    |--------------------------------------------------------------------------
    |
    | For documentation generation purposes.
    */

    'production_url' => env('APP_PRODUCTION_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Application Message
    |--------------------------------------------------------------------------
    |
    | A short notice to appear at the bottom of each API call
    */

    'message' => env('APP_MESSAGE', null),

    /*
    |--------------------------------------------------------------------------
    | Configuration Documentation
    |--------------------------------------------------------------------------
    |
    | An array of key-value pairs that will be output at the bottom of each
    | call to the API. This is useful to convey things like URLs to other
    | systems the returned data relies on to function. For example, if one of
    | the fields is `image` and the file name needs to be retrieved from a
    | particular server, you can document the server here.
    */

    'config_documentation' => [
        'iiif_url' => env('IIIF_URL', null),
        'website_url' => env('WEBSITE_URL', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Proxy URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the frontend to properly generate URLs when using
    | the app behind a CDN or load balancer.
    |
    */

    'proxy_url' => env('PROXY_URL', env('APP_URL', 'http://localhost')),
    'proxy_scheme' => env('PROXY_SCHEME', 'http'),

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | Special rules for throttling and deep-pagination.
    |
    */

    'auth' => [
        'restricted' => env('APP_RESTRICTED', true),
        'max_attempts' => 60,
        'max_resources_guest' => 1000,
        'max_resources_user' => 10000,
        'login_whitelist_ips' => array_map('trim', explode(',', env('LOGIN_WHITELIST_IPS', '127.0.0.1/32'))),
        'access_whitelist_ips' => array_map('trim', explode(',', env('ACCESS_WHITELIST_IPS', ''))),
    ],

];
