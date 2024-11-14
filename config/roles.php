<?php

// Default roles and permissions

return [
    'organization_admin' => [
        'campgaign.full',
        'list.full',
        // or simply all.full?
    ],

    'organization_readonly' => [
        'campaign.readonly',
        // more goes here
    ]
];
