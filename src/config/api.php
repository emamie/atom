<?php

return [
    'auth' => [
    	'vendor-example/package-example' => [
            'token' => env('API_AUTH_VENDOR_PACKAGE_TOKEN', 'token'),// 64 character
            'request_token_name' => env('API_AUTH_VENDOR_PACKAGE_API_TOKEN', 'api_token'),

            'allow_json_token' => env('API_AUTH_VENDOR_PACKAGE_JSON', true),
            'allow_request_token' => env('API_AUTH_VENDOR_PACKAGE_REQUEST', true),
        ]
    ]
];
