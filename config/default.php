<?php

return [
    'site_name' => [
        'value' => config('app.name'),
        'type' => 'string',
    ],
    'site_keyword' => [
        'value' => 'Email Marketing, Campaigns, Lists',
        'type' => 'string',
    ],
    'site_logo_light' => [
        'value' => '',
        'type' => 'image',
    ],
    'site_logo_dark' => [
        'value' => '',
        'type' => 'image',
    ],
    'site_favicon' => [
        'value' => '',
        'type' => 'image',
    ],
    'license' => [
        'cat' => 'license',
        'value' => '',
        'type' => 'string',
    ],
    'license_type' => [
        'cat' => 'license',
        'value' => '',
        'type' => 'string',
    ],
    'license_status' => [
        'cat' => 'license',
        'value' => '',
        'type' => 'string',
    ],
    'license_supported_until' => [
        'cat' => 'license',
        'value' => '',
        'type' => 'string',
    ],
    'site_online' => [
        'value' => 'true',
        'type' => 'boolean',
    ],
    'site_offline_message' => [
        'value' => 'Application currently offline. We will come back soon!',
        'type' => 'text',
    ],
    'site_description' => [
        'value' => 'Makes it easy for you to create, send, and optimize your email marketing campaigns.',
        'type' => 'text',
    ],
    'default_language' => [
        'value' => 'en',
        'type' => 'string',
    ],
    'frontend_scheme' => [
        'value' => 'default',
        'type' => 'enum',
        'options' => [
            'default',
            'blue',
            'green',
            'brown',
            'pink',
            'grey',
            'white',
        ],
    ],
    'backend_scheme' => [
        'value' => 'default',
        'type' => 'enum',
        'options' => [
            'default',
            'blue',
            'green',
            'brown',
            'pink',
            'grey',
            'white',
        ],
    ],
    'captcha_engine' => [
        'value' => 'recaptcha',
        'type' => 'string',
    ],
    'login_recaptcha' => [
        'value' => 'no',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    'embedded_form_recaptcha' => [
        'value' => 'no',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    'list_sign_up_captcha' => [
        'value' => 'no',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    'enable_user_registration' => [
        'value' => 'yes',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    'registration_recaptcha' => [
        'value' => 'yes',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    'custom_script' => [
        'value' => '',
        'type' => 'text',
    ],
    'builder' => [
        'value' => 'both',
        'type' => 'enum',
        'options' => [
            'both',
            'pro',
            'classic',
        ],
    ],
    'import_subscribers_commitment' => [
        'value' => null,
        'type' => 'text',
    ],
    'url_unsubscribe' => [
        'value' => '',
        'type' => 'string',
    ],
    'url_open_track' => [
        'value' => '', // action('CampaignController@open', ["message_id" => trans("messages.MESSAGE_ID")]),
        'type' => 'string',
    ],
    'url_click_track' => [
        'value' => '', // action('CampaignController@click', ["message_id" => trans("messages.MESSAGE_ID"), "url" => trans("messages.URL")]),
        'type' => 'string',
    ],
    'url_delivery_handler' => [
        'value' => '', // action('DeliveryController@notify'),
        'type' => 'string',
    ],
    'url_update_profile' => [
        'value' => '',
        'type' => 'string',
    ],
    'url_web_view' => [
        'value' => '',
        'type' => 'string',
    ],
    'php_bin_path' => [
        'cat' => 'cronjob',
        'value' => '',
        'type' => 'string',
    ],
    'remote_job_token' => [
        'cat' => 'cronjob',
        'value' => '',
        'type' => 'string',
    ],
    'cronjob_last_execution' => [
        'cat' => 'monitor',
        'value' => 0,
        'type' => 'string',
    ],
    'cronjob_min_interval' => [
        'cat' => 'monitor',
        'value' => '15 minutes',
        'type' => 'string',
    ],
    'spf_record' => [
        'cat' => 'dns',
        'value' => null,
        'type' => 'string',
    ],
    'spf_host' => [
        'cat' => 'dns',
        'value' => null,
        'type' => 'string',
    ],
    'verification_hostname' => [
        'cat' => 'dns',
        'value' => 'emarketing',
        'type' => 'string',
    ],
    'dkim_selector' => [
        'cat' => 'dns',
        'value' => 'mailer',
        'type' => 'string',
    ],
    'allow_turning_off_dkim_signing' => [
        'value' => 'yes',
        'type' => 'string',
    ],
    'escape_dkim_dns_value' => [
        'value' => 'no',
        'type' => 'string',
    ],
    'verify_subscriber_email' => [
        'value' => 'no',
        'type' => 'string',
    ],
    'send_notification_email_for_list_subscription' => [
        'value' => 'no',
        'type' => 'string',
    ],
    'aws_verification_server' => [
        'value' => 'no',
        'type' => 'string',
    ],
    'geoip.engine' => [
        'value' => 'sqlite', # available values are sqlite|nekudo|mysql
        'type' => 'string',
    ],
    'geoip.enabled' => [
        'value' => 'no',
        'type' => 'string',
    ],
    'geoip.last_message' => [
        'value' => null,
        'type' => 'string',
    ],
    'geoip.sqlite.dbname' => [
        'value' => 'storage/app/GeoLite2-City.mmdb',
        'type' => 'string',
    ],
    'geoip.sqlite.source_url' => [
        'value' => 'https://acellemail.s3.amazonaws.com/GeoLite2-City_20230404.mmdb',
        'type' => 'string',
    ],
    'geoip.sqlite.source_hash' => [
        'value' => '1b6368f0e80b1be2dd5be3606d25ac16',
        'type' => 'string',
    ],
    'delivery.sendmail' => [
        'value' => 'yes',
        'type' => 'string',
    ],
    'delivery.phpmail' => [
        'value' => 'yes',
        'type' => 'string',
    ],
    'subscription.expiring_period' => [
        'cat' => 'payment',
        'value' => '7',
        'type' => 'string',
    ],
    'subscription.auto_billing_period' => [
        'cat' => 'payment',
        'value' => '3',
        'type' => 'string',
    ],
    'allowed_due_subscription' => [
        'cat' => 'payment',
        'value' => 'no',
        'type' => 'string',
    ],
    'theme.beta' => [
        'value' => 'no',
        'type' => 'string',
    ],
    'spamassassin.command' => [
        'value' => 'spamc -R',
        'type' => 'string',
    ],
    'spamassassin.required' => [
        'value' => 'no',
        'type' => 'string',
    ],
    'spamassassin.enabled' => [
        'value' => 'no',
        'type' => 'string',
    ],
    'mta.api_endpoint' => [
        'value' => null,
        'type' => 'string',
    ],
    'mta.api_key' => [
        'value' => null,
        'type' => 'string',
    ],
    'storage.s3' => [
        'value' => null,
        'type' => 'string',
    ],
    'rss.enabled' => [
        'value' => 'yes',
        'type' => 'string',
    ],
    'list.clone_for_others' => [
        'value' => 'no',
        'type' => 'string',
    ],
    'gateways' => [
        'value' => '["direct"]',
        'type' => 'array',
    ],
    'automation.trigger_imported_contacts' => [
        'value' => 'no',
        'type' => 'string',
    ],
    'campaign.bcc' => [
        'value' => '',
        'type' => 'string',
    ],
    'campaign.cc' => [
        'value' => '',
        'type' => 'string',
    ],
    'campaign.tracking_domain' => [
        'value' => 'no',
        'type' => 'string',
    ],
    'list.allow_single_optin' => [
        'value' => 'yes',
        'type' => 'string',
    ],
    'campaign.enforce_unsubscribe_url_check' => [
        'value' => 'no',
        'type' => 'string',
    ],
    'layout.menu_bar' => [
        'value' => 'left',
        'type' => 'string',
    ],
    'invoice.current' => [
        'value' => '1',
        'type' => 'number',
    ],
    'invoice.format' => [
        'value' => '%08d',  // a number of 8 digit, for example: sprintf('%08d', 15) -> 00000015
        'type' => 'string',
    ],
    'customer_can_change_language' => [
        'value' => 'yes',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    'customer_can_change_personality' => [
        'value' => 'yes',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    'campaign.stop_on_error' => [
        'value' => 'no',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    'not_require_card_for_trial' => [
        'value' => 'no',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    '2fa_enable' => [
        'value' => 'no',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    'tracking_domain.enable_https' => [
        'value' => 'no',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    'email.enable_one_click_unsubscribe_headers' => [
        'value' => 'yes',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    'campaign.duplicate' => [
        'value' => 'yes',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    'list.default.double_optin' => [
        'value' => 'no',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    'email.default.skip_failed_message' => [
        'value' => 'no',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    'notification.on_user_created' => [
        'value' => '',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    'signature.enabled' => [
        'value' => 'no',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
    'blacklist.use_global_blacklist' => [
        'value' => 'yes',
        'type' => 'boolean',
        'options' => ['no', 'yes'],
    ],
];


            