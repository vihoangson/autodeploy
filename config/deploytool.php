<?php
return [
    'secret' => [
        'production' => env('DEPLOYTOOL_SECRET_TOKEN_PRODUCTION', '5432112345')
    ],
    'domain' => [
        'production' => env('DEPLOYTOOL_DOMAIN_PRODUCTION', '')
    ]
];
