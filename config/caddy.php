<?php

return [
    // Is this the server that hosts caddy?
    // If yes, configuration files must be written on this server
    'is_server' => env('AS_CADDY_SERVER', false),

    // Enable autossl for tracking domain
    'autossl' => true,

    // Caddy server IP address (for pointing an A record to)
    'server_ip' => '51.79.255.61',

    // Directory containing configuration file
    'config_dir' => '/home/sendmails/autossl/',

    // Admin email address used to obtain an SSL cert
    'admin_email_address' => 'admin@sendmails.io',

    // Reverse proxy, serving https request
    'reverse_proxy' => 'localhost:8080',
];
