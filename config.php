<?php

return [
    'name' => 'atom',
    'key' => 'atom',

    'domains' => [],

    'table_prefix' => env('ATOM_TABLE_PREFIX', 'atom'),
    'db_connection' => env('ATOM_DB_CONNECTION', 'mysql'),

    'api_prefix' => env('ATOM_API_PREFIX', 'api'),

    'local_driver' => [],
    'cloud_driver' => [],

    'override' => [
        'filesystems' => []
    ],


    'oauth2' => [
        'url' => [
            'base' => env('ATOM_OAUTH2_URL_BASE', 'http://id.app.razavi.test/oauth/authorize'),
            'token' => env('ATOM_OAUTH2_URL_TOKEN', 'http://id.app.razavi.test/oauth/token'),
            'user' => env('ATOM_OAUTH2_URL_USER', 'http://id.app.razavi.test/api/user'),
        ]
    ],

    'api_service' =>[
        "headers" =>  [
            'Content-Type' => env("API_HEADER_CONTENT_TYPE",'application/json'),
            'Accept' => env("API_HEADER_ACCEPT",'application/json'),
        ]
    ]

];
