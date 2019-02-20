<?php

return [
    'gateways' => [
        'cash' => [
            'name' => 'cash',
            'status' => 'active',
            'fields' => [],
        ],
        'stripe' => [
            'name' => 'stripe',
            'status' => 'inactive',
            'fields' => [
                'publishable_key' => null,
                'secret_key' => null,
            ],
        ],
        'braintree' => [
            'name' => 'braintree',
            'status' => 'inactive',
            'fields' => [
                'environment' => null,
                'merchant_id' => null,
                'public_key' => null,
                'private_key' => null,
            ],
        ],
    ],
];