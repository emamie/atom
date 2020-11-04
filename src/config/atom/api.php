<?php

return [
    'auth' => [
    	'razavi/appcms' => [
    	    /*
    	     'allow_json_token' => env('API_ALLOW_JSON_TOKEN', false),
             'allow_request_token' => env('API_ALLOW_REQUEST_TOKEN', false),
    	     'request_token_name' => 'token',// name for token for requests
    	     'tokens' => [
    	        'name_for_access' => env('token_for_access'),
    	        ...
    	     ],
    	     */
            'allow_json_token' => env('API_ALLOW_JSON_TOKEN', false),
            'allow_request_token' => env('API_ALLOW_REQUEST_TOKEN', false),
            'request_token_name' => 'token',// name for token for requests
            'tokens' => [
                'powerbi' => env('API_TOKEN_POWERBI'),
            ],
        ],
        'razavi/salamat' => [
            'allow_json_token' => env('API_ALLOW_JSON_TOKEN', false),
            'allow_request_token' => env('API_ALLOW_REQUEST_TOKEN', false),
            'request_token_name' => 'token',// name for token for requests
            'tokens' => [
                'siagh' => env('API_TOKEN_SIAGH'),
                'powerbi' => env('API_TOKEN_POWERBI'),
            ],
        ],
        'razavi/Support' => [
            'allow_json_token' => env('API_ALLOW_JSON_TOKEN', false),
            'allow_request_token' => env('API_ALLOW_REQUEST_TOKEN', false),
            'request_token_name' => 'token',// name for token for requests
            'tokens' => [
                'siagh' => env('API_SUPPORT_TOKEN_SIAGH'),
            ],
        ]
    ]
];
