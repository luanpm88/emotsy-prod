<?php

return [
    'gateways' => [
        'direct' => [
            'name' => 'direct',
            'fields' => [
                'database_path' => 'app/direct_payment.sqlite3',
            ],
        ],
        'stripe' => [
            'name' => 'stripe',
            'fields' => [
                'publishable_key' => null,
                'secret_key' => null,
            ],
        ],
        'coinpayments' => [
            'name' => 'coinpayments',
            'fields' => [
                'merchant_id' => null,
                'public_key' => null,
                'private_key' => null,
                'ipn_secret' => null,
            ],
        ],
    ],
];