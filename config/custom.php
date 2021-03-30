<?php

return [
    // The standard format for storing date/time string
    // Used by Carbon
    // Important: both `date_format` (for PHP) and `date_format_sql` (for MySQL) must return the same output string
    'date_format' => 'Y-m-d',           // 2020-12-25
    'date_format_sql' => '%Y-%m-%d',    // 2020-12-25

    // Minimum recommended PHP version
    'php_recommended' => '7.2.5',

    // Minimum supported PHP version
    'php' => '7.1.3',
];
