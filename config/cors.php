<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Default 
    'allowed_origins' => ['*'],

    // Add frontend URLs for CORS to allow requests from them
    // 'allowed_origins' => [
    //     'http://localhost:5173',
    //     'http://127.0.0.1:5173',
    //     'http://localhost:3000', // Add other frontend URLs as needed
    // ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // default false | true for access control allow credentials
    'supports_credentials' => true,

];
