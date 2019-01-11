<?php

return [
    'gateways' => [
        'cash' => [
            'name' => 'cash',
            'status' => 'active',
            'fields' => [],
        ],
        'stripe' => [
            'name' => 'cash',
            'status' => 'inactive',
            'fields' => [
                'publishable_key' => null,
                'secret_key' => null,
            ],
        ],
    ],
];