<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Insert from file
        $query = File::get(storage_path('install/database_init.sql'));
        $query = str_replace('<<prefix>>', env('DB_TABLES_PREFIX', ''), $query);        
        DB::statement($query);
        
        $created_at = Carbon\Carbon::now();
        $updated_at = $created_at;

        //// Create default countries
        //DB::table('countries')->insert([
        //    'id' => 1,
        //    'name' => 'Việt Nam',
        //    'code' => 'vn',
        //    'created_at' => $created_at,
        //    'updated_at' => $updated_at,
        //]);
        //
        //// Create default languages
        //DB::table('languages')->insert([
        //    'id' => 1,
        //    'uid' => uniqid(),
        //    'name' => 'English',
        //    'code' => 'en',
        //    'region_code' => 'us',
        //    'status' => 'active',
        //    'is_default' => true,
        //    'created_at' => $created_at,
        //    'updated_at' => $updated_at,
        //]);

        // Create default contact for admin user
        DB::table('contacts')->insert([
            'id' => 1,
            'first_name' => 'Luan',
            'last_name' => 'Pham',
            'company' => 'Acelle Co,. Ldt.',
            'address_1' => '1202 Nguyen Van Qua, Tan Thoi Hiep, Dist. 12',
            'country_id' => 1,
            'state' => 'Ho Chi Minh City',
            'zip' => '70000',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        // Create default contact for admin user
        DB::table('contacts')->insert([
            'id' => 2,
            'first_name' => 'Luan',
            'last_name' => 'Pham',
            'company' => 'Acelle Co,. Ldt.',
            'address_1' => '1202 Nguyen Van Qua, Tan Thoi Hiep, Dist. 12',
            'country_id' => 1,
            'state' => 'Ho Chi Minh City',
            'zip' => '70000',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        // Create default contact for admin user
        DB::table('contacts')->insert([
            'id' => 3,
            'first_name' => 'Luan',
            'last_name' => 'Pham',
            'company' => 'Acelle Co,. Ldt.',
            'address_1' => '1202 Nguyen Van Qua, Tan Thoi Hiep, Dist. 12',
            'country_id' => 1,
            'state' => 'Ho Chi Minh City',
            'zip' => '70000',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        // Create default contact for admin user
        DB::table('contacts')->insert([
            'id' => 4,
            'first_name' => 'Luan',
            'last_name' => 'Pham',
            'company' => 'Acelle Co,. Ldt.',
            'address_1' => '1202 Nguyen Van Qua, Tan Thoi Hiep, Dist. 12',
            'country_id' => 1,
            'state' => 'Ho Chi Minh City',
            'zip' => '70000',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        // Create default contact for admin user
        DB::table('contacts')->insert([
            'id' => 5,
            'first_name' => 'Luan',
            'last_name' => 'Pham',
            'company' => 'Acelle Co,. Ldt.',
            'address_1' => '1202 Nguyen Van Qua, Tan Thoi Hiep, Dist. 12',
            'country_id' => 1,
            'state' => 'Ho Chi Minh City',
            'zip' => '70000',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        // Create default user
        DB::table('users')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'email' => 'luanpm88@gmail.com',
            'password' => bcrypt('123456'),
            'status' => 'active',
            'activated' => 1,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        // Create default customer group
        DB::table('admin_groups')->insert([
            'id' => 1,
            'name' => 'Admin Group 1',
            'creator_id' => 1,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        // Create default customer for admin user
        DB::table('admins')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'user_id' => 1,
            'creator_id' => 1,
            'contact_id' => 4,
            'admin_group_id' => 1,
            'language_id' => 1,
            'first_name' => 'Luan',
            'last_name' => 'Pham',
            'timezone' => 'Atlantic/Cape_Verde',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        // Create default customer for admin user
        DB::table('customers')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'user_id' => 1,
            'admin_id' => 1,
            'contact_id' => 3,
            'language_id' => 1,
            'first_name' => 'Luan',
            'last_name' => 'Pham',
            'timezone' => 'Atlantic/Cape_Verde',
            'status' => 'active',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        // Create list and default fields
        DB::table('mail_lists')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'contact_id' => 2,
            'customer_id' => 1,
            'name' => 'My List',
            'status' => 'active',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('fields')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'mail_list_id' => 1,
            'type' => 'text',
            'label' => 'Email',
            'tag' => 'EMAIL',
            'visible' => true,
            'required' => true,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('fields')->insert([
            'id' => 2,
            'uid' => uniqid(),
            'mail_list_id' => 1,
            'type' => 'text',
            'label' => 'First name',
            'tag' => 'FNAME',
            'visible' => true,
            'required' => false,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('fields')->insert([
            'id' => 3,
            'uid' => uniqid(),
            'mail_list_id' => 1,
            'type' => 'text',
            'label' => 'Last name',
            'tag' => 'LNAME',
            'visible' => true,
            'required' => false,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('subscribers')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'mail_list_id' => 1,
            'email' => 'minhnghivn@gmail.com',
            'status' => 'subscribed',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('subscriber_fields')->insert([
            'id' => 1,
            'subscriber_id' => 1,
            'field_id' => 1,
            'value' => 'minhnghivn@gmail.com',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('subscribers')->insert([
            'id' => 2,
            'uid' => uniqid(),
            'mail_list_id' => 1,
            'email' => 'minhnghivn@yahoo.com',
            'status' => 'subscribed',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('subscriber_fields')->insert([
            'id' => 2,
            'subscriber_id' => 2,
            'field_id' => 1,
            'value' => 'minhnghivn@yahoo.com',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        // Create segment for list
        DB::table('segments')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'mail_list_id' => 1,
            'name' => 'Gmail!',
            'matching' => 'all',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        // Create conditions for segment
        DB::table('segment_conditions')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'segment_id' => 1,
            'field_id' => 1,
            'operator' => 'ends',
            'value' => 'gmail.com',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('bounce_handlers')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'admin_id' => 1,
            'name' => 'My Bounce Handler',
            'username' => 'nghipm@elarion.com',
            'password' => 'dummy!!!',
            'host' => 'mail.elarion.com',
            'port' => 143,
            'protocol' => 'imap',
            'encryption' => 'notls',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('bounce_handlers')->insert([
            'id' => 2,
            'uid' => uniqid(),
            'admin_id' => 1,
            'name' => 'GMX Bounce Handler',
            'username' => 'minhnghivn@gmx.com',
            'password' => 'dummy!!!',
            'host' => 'imap.gmx.com',
            'port' => 993,
            'protocol' => 'imap',
            'encryption' => 'ssl',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('feedback_loop_handlers')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'admin_id' => 1,
            'name' => 'GMX Feedback Loop Handler',
            'username' => 'minhnghivn@gmx.com',
            'password' => 'dummy!!!',
            'host' => 'imap.gmx.com',
            'port' => 993,
            'protocol' => 'imap',
            'encryption' => 'ssl',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('sending_servers')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'name' => 'Server Amazon SMTP',
            'admin_id' => 1,
            'type' => 'amazon-smtp',
            'host' => 'email-smtp.us-west-2.amazonaws.com',
            'aws_access_key_id' => 'AKIAJCLUP4FFFKZSQHGQ',
            'aws_secret_access_key' => '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',
            'aws_region' => 'us-west-2',
            'smtp_username' => 'AKIAJFKZ6W6BKSV2EZ2Q',
            'smtp_password' => 'AhIei5ACkhVIC0p2QKRb6T5YzZBmCoOdQFdnyk5QyMQf',
            'smtp_port' => 25,
            'smtp_protocol' => 'tls',
            'quota_value' => 1000,
            'quota_base' => 5,
            'quota_unit' => 'minute',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('sending_servers')->insert([
            'id' => 6,
            'uid' => uniqid(),
            'name' => 'Mailgun API',
            'admin_id' => 1,
            'type' => 'mailgun-api',
            'domain' => 'sandboxe80f3790d6784dcea27cbf61831dc094.mailgun.org',
            'api_key' => 'key-04dbf1c76df28775e4c3d9215d5ca548',
            'quota_value' => 1000,
            'quota_base' => 5,
            'quota_unit' => 'minute',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('sending_servers')->insert([
            'id' => 7,
            'uid' => uniqid(),
            'name' => 'Mailgun SMTP',
            'admin_id' => 1,
            'type' => 'mailgun-smtp',
            'domain' => 'sandboxe80f3790d6784dcea27cbf61831dc094.mailgun.org',
            'api_key' => 'key-04dbf1c76df28775e4c3d9215d5ca548',
            'host' => 'smtp.mailgun.org',
            'smtp_username' => 'postmaster@sandboxe80f3790d6784dcea27cbf61831dc094.mailgun.org',
            'smtp_password' => 'fc4ab8e20d3dd5354f8cc4a3fb8ba148',
            'smtp_port' => 25,
            'smtp_protocol' => 'tls',
            'quota_value' => 1000,
            'quota_base' => 5,
            'quota_unit' => 'minute',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('sending_servers')->insert([
            'id' => 2,
            'uid' => uniqid(),
            'name' => 'Server Amazon API',
            'admin_id' => 1,
            'type' => 'amazon-api',
            //'host' => 'email.us-west-2.amazonaws.com',
            'aws_region' => 'us-west-2',
            'aws_access_key_id' => 'AKIAJCLUP4FFFKZSQHGQ',
            'aws_secret_access_key' => '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',
            'quota_value' => 1000,
            'quota_base' => 5,
            'quota_unit' => 'minute',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('sending_servers')->insert([
            'id' => 3,
            'uid' => uniqid(),
            'name' => 'Server SMTP',
            'admin_id' => 1,
            'type' => 'smtp',
            'host' => 'email-smtp.us-west-2.amazonaws.com',
            'smtp_username' => 'AKIAJFKZ6W6BKSV2EZ2Q',
            'smtp_password' => 'AhIei5ACkhVIC0p2QKRb6T5YzZBmCoOdQFdnyk5QyMQf',
            'smtp_port' => 25,
            'smtp_protocol' => 'tls',
            'quota_value' => 1000,
            'quota_base' => 5,
            'quota_unit' => 'minute',
            'bounce_handler_id' => 1,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('sending_servers')->insert([
            'id' => 4,
            'uid' => uniqid(),
            'name' => 'Server PHP Mail()',
            'admin_id' => 1,
            'type' => 'php-mail',
            'quota_value' => 1000,
            'quota_base' => 5,
            'quota_unit' => 'minute',
            'bounce_handler_id' => 1,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('sending_servers')->insert([
            'id' => 5,
            'uid' => uniqid(),
            'name' => 'Server Sendmail',
            'admin_id' => 1,
            'type' => 'sendmail',
            'sendmail_path' => '/usr/sbin/sendmail',
            'quota_value' => 1000,
            'quota_base' => 5,
            'quota_unit' => 'minute',
            'bounce_handler_id' => 1,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('sending_servers')->insert([
            'id' => 8,
            'uid' => uniqid(),
            'name' => 'SendGrid API',
            'admin_id' => 1,
            'type' => 'sendgrid-api',
            'api_key' => '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',
            'status' => 'active',
            'quota_value' => 1000,
            'quota_base' => 5,
            'quota_unit' => 'minute',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('sending_servers')->insert([
            'id' => 9,
            'uid' => uniqid(),
            'name' => 'SendGrid SMTP',
            'admin_id' => 1,
            'type' => 'sendgrid-smtp',
            'api_key' => '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',
            'host' => 'smtp.sendgrid.net',  // giá trị gợi ý màu xám xám trong textbox
            'smtp_username' => 'sendgridlouis',
            'smtp_password' => 'louis123',
            'smtp_port' => 587, // giá trị gợi ý màu xám xám trong textbox
            'status' => 'active',
            'quota_value' => 1000,
            'quota_base' => 5,
            'quota_unit' => 'minute',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('customer_group_sending_servers')->insert([
            'id' => 1,
            'sending_server_id' => 1,
            'customer_group_id' => 1,
            'fitness' => 0,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('customer_group_sending_servers')->insert([
            'id' => 2,
            'sending_server_id' => 2,
            'customer_group_id' => 1,
            'fitness' => 0,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('customer_group_sending_servers')->insert([
            'id' => 3,
            'sending_server_id' => 3,
            'customer_group_id' => 1,
            'fitness' => 0,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('customer_group_sending_servers')->insert([
            'id' => 4,
            'sending_server_id' => 4,
            'customer_group_id' => 1,
            'fitness' => 0,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('customer_group_sending_servers')->insert([
            'id' => 5,
            'sending_server_id' => 5,
            'customer_group_id' => 1,
            'fitness' => 0,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('customer_group_sending_servers')->insert([
            'id' => 6,
            'sending_server_id' => 6,
            'customer_group_id' => 1,
            'fitness' => 0,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);

        DB::table('customer_group_sending_servers')->insert([
            'id' => 7,
            'sending_server_id' => 7,
            'customer_group_id' => 1,
            'fitness' => 100,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        DB::table('currencies')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'admin_id' => 1,
            'name' => 'USD',
            'code' => 'USD',
            'format' => '${PRICE}',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        DB::table('plans')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'admin_id' => 1,
            'currency_id' => 1,
            'name' => 'Free',
            'price' => 0.0,
            'quota' => 500000,
            'frequency_amount' => 1,
            'frequency_unit' => 'month',
            'frequency_amount' => 1,
            'options' => '',
            'status' => 'active',
            'color' => 'rgb(20,130,160)',
            'custom_order' => 0,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        DB::table('plans')->insert([
            'id' => 2,
            'uid' => uniqid(),
            'admin_id' => 1,
            'currency_id' => 1,
            'name' => 'Advanced',
            'price' => 500.0,
            'quota' => 1000000,
            'frequency_amount' => 1,
            'frequency_unit' => 'month',
            'frequency_amount' => 1,
            'options' => '',
            'status' => 'active',
            'color' => '#E91E63',
            'custom_order' => 1,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        DB::table('payment_methods')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'name' => 'Pay in Cash',
            'admin_id' => 1,
            'type' => 'cash',
            'status' => 'active',
            'custom_order' => 0,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        DB::table('payment_methods')->insert([
            'id' => 2,
            'uid' => uniqid(),
            'name' => 'Pay with PayPal',
            'admin_id' => 1,
            'type' => 'braintree_paypal',
            'status' => 'inactive',
            'options' => '{"environment":"sandbox","merchantId":"g3xq9gs9y9dvqqv6","publicKey":"c3xh5n9n2q8rkwct","privateKey":"14d2a2b3b337286f245c9976f2e4fe57"}',
            'custom_order' => 1,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        DB::table('payment_methods')->insert([
            'id' => 3,
            'uid' => uniqid(),
            'name' => 'Pay by Credit card',
            'admin_id' => 1,
            'type' => 'braintree_credit_card',
            'status' => 'inactive',
            'options' => '{"environment":"sandbox","merchantId":"g3xq9gs9y9dvqqv6","publicKey":"c3xh5n9n2q8rkwct","privateKey":"14d2a2b3b337286f245c9976f2e4fe57"}',
            'custom_order' => 2,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
    }
}
