<?php

return [
    // Enable autossl for tracking domain
    'autossl' => true,

    // Caddy server IP address (for pointing an A record to)
    'caddy.server_ip' => '51.79.255.61',

    // Admin email address used to obtain an SSL cert
    'caddy.admin_email_address' => 'admin@sendmails.io',

    // Reverse proxy, serving https request
    'caddy.reverse_proxy' => 'localhost:8080',
];
