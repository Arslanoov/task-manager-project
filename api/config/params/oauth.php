<?php

return [
    'oauth' => [
        'api_oauth_encryption_key' => 'key',
        'public_key_path' => dirname(__DIR__, 2) . '/public.key',
        'private_key_path' => dirname(__DIR__, 2) . '/private.key',
        'encryption_key' => 'key',
        'clients' => [
            'app' => [
                'secret'          => null,
                'name'            => 'App',
                'redirect_uri'    => null,
                'is_confidential' => false
            ]
        ]
    ]
];