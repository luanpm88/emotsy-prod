<?php

return [
    'plan' => [
        'limit' => 3,
        'public_page' => false,
    ],
    'sending_server' => [
        'limit' => 1
    ],
    'bounce_handler' => [
        'disable' => true
    ],
    'feedback_loop_handler' => [
        'disable' => true
    ],
    'email_verfication_server' => [ 
        'disable' => true
    ],
    'campaign' => [ 'limit' => 1 ],
    'automation' => [ 'disable' => true ],
    'list' => [
        'limit' => 1,
        'segment' => false,
    ],
    'form' => [ 'disable' => true ],
    'website' => [ 'disable' => true ],
];
