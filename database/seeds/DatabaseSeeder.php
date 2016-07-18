<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $created_at = Carbon\Carbon::now();
        $updated_at = $created_at;
        
        // Create default countries
        DB::table('countries')->insert([
            'id' => 1,
            'name' => 'Việt Nam',
            'code' => 'vn',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        // Create default languages
        DB::table('languages')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'name' => 'English',
            'code' => 'en',
            'region_code' => 'us',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        // Create default contact for admin user
        DB::table('contacts')->insert([
            'id' => 1,
            'first_name' => 'Luan',
            'last_name' => 'Pham',
            'company' => 'Mailixa Co,. Ldt.',
            'address_1' => '1202 Nguyen Van Qua, Tan Thoi Hiep, Dist. 12',
            'country_id' => 1,
            'state' => 'Ho Chi Minh City',
            'zip' => '70000',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        // Create default user group
        DB::table('user_groups')->insert([
            'id' => 1,
            'name' => 'Default',
            'user_id' => null,
            'backend_access' => true,
            'frontend_access' => true,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        // Create default admin user
        DB::table('users')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'contact_id' => 1,
            'user_group_id' => 1,
            'language_id' => 1,
            'first_name' => 'Luan',
            'last_name' => 'Pham',
            'email' => 'luanpm88@gmail.com',
            'password' => bcrypt('123456'),
            'status' => 'active',            
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        // Create list and default fields
        DB::table('mail_lists')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'contact_id' => 1,
            'user_id' => 1,
            'name' => 'My List',
            'status' => 'active',
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);

        DB::table('campaigns')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'mail_list_id' => 1,
            'user_id' => 1,
            'name' => 'My Campaign',
            'type' => 'regular',
            'subject' => 'Newsletter of June, 13',
            'from_email' => 'noreply@anphabeta.com',
            'reply_to' => 'noreply@anphabeta.com',
            'from_name' => 'Those were the days',
            'html' => '<b>Those</b> were the <span style="color:green">days</span>',
            'plain' => 'Those were the <i>days</i>',
            'status' => 'pending',
            'created_at' => $created_at,
            'updated_at' => $updated_at
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
            'updated_at' => $updated_at
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
            'updated_at' => $updated_at
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
            'updated_at' => $updated_at
        ]);

        DB::table('subscribers')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'user_id' => 1,
            'mail_list_id' => 1,
            'email' => 'minhnghivn@gmail.com',
            'status' => 'active',
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);

        DB::table('subscribers')->insert([
            'id' => 2,
            'uid' => uniqid(),
            'user_id' => 1,
            'mail_list_id' => 1,
            'email' => 'minhnghivn@yahoo.com',
            'status' => 'active',
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);
        
        DB::table('sending_servers')->insert([
            'id' => 1,
            'uid' => uniqid(),
            'name' => 'Server Amazon SMTP',
            'user_id' => 1,
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
            'updated_at' => $updated_at
        ]);

        DB::table('sending_servers')->insert([
            'id' => 2,
            'uid' => uniqid(),
            'name' => 'Server Amazon API',
            'user_id' => 1,
            'type' => 'amazon-api',
            'host' => 'email.us-west-2.amazonaws.com',
            'aws_region' => 'us-west-2',
            'aws_access_key_id' => 'AKIAJCLUP4FFFKZSQHGQ',
            'aws_secret_access_key' => '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',
            'quota_value' => 1000,
            'quota_base' => 5,
            'quota_unit' => 'minute',
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);

        DB::table('sending_servers')->insert([
            'id' => 3,
            'uid' => uniqid(),
            'name' => 'Server SMTP',
            'user_id' => 1,
            'type' => 'smtp',
            'host' => 'email-smtp.us-west-2.amazonaws.com',
            'smtp_username' => 'AKIAJFKZ6W6BKSV2EZ2Q',
            'smtp_password' => 'AhIei5ACkhVIC0p2QKRb6T5YzZBmCoOdQFdnyk5QyMQf',
            'smtp_port' => 25,
            'smtp_protocol' => 'tls',
            'quota_value' => 1000,
            'quota_base' => 5,
            'quota_unit' => 'minute',
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);

        DB::table('sending_servers')->insert([
            'id' => 4,
            'uid' => uniqid(),
            'name' => 'Server PHP Mail()',
            'user_id' => 1,
            'type' => 'php-mail',
            'quota_value' => 1000,
            'quota_base' => 5,
            'quota_unit' => 'minute',
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);

        DB::table('sending_servers')->insert([
            'id' => 5,
            'uid' => uniqid(),
            'name' => 'Server Sendmail',
            'user_id' => 1,
            'type' => 'sendmail',
            'sendmail_path' => '/usr/sbin/sendmail',
            'quota_value' => 1000,
            'quota_base' => 5,
            'quota_unit' => 'minute',
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);

        DB::table('tracking_logs')->insert([
            'id' => 1,
            'message_id' => 1,
            'sending_server_id' => 1,
            'user_id' => 1,
            'campaign_id' => 1,
            'subscriber_id' => 1,
            'status' => 'sent',
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);

        DB::table('tracking_logs')->insert([
            'id' => 2,
            'message_id' => 2,
            'sending_server_id' => 1,
            'user_id' => 1,
            'campaign_id' => 1,
            'subscriber_id' => 2,
            'status' => 'sent',
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);
        
        DB::table('open_logs')->insert([
            'id' => 1,
            'message_id' => 1,
            'ip_address' => '111.111.00.11',
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);
        
        DB::table('click_logs')->insert([
            'id' => 1,
            'message_id' => 1,
            'url' => 'http://google.com',
            'ip_address' => '111.111.00.11',
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);
        
        DB::table('feedback_logs')->insert([
            'id' => 1,
            'message_id' => 1,
            'feedback_type' => '0',
            'raw_feedback_content' => '0',
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);
        
        DB::table('unsubscribe_logs')->insert([
            'id' => 1,
            'message_id' => 1,
            'user_agent' => '0',
            'ip_address' => '111.111.00.11',
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);

        DB::table('user_group_sending_servers')->insert([
            'id' => 1,
            'sending_server_id' => 1,
            'user_group_id' => 1,
            'fitness' => 0,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);

        DB::table('user_group_sending_servers')->insert([
            'id' => 2,
            'sending_server_id' => 2,
            'user_group_id' => 1,
            'fitness' => 0,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);

        DB::table('user_group_sending_servers')->insert([
            'id' => 3,
            'sending_server_id' => 3,
            'user_group_id' => 1,
            'fitness' => 0,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);

        DB::table('user_group_sending_servers')->insert([
            'id' => 4,
            'sending_server_id' => 4,
            'user_group_id' => 1,
            'fitness' => 0,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);

        DB::table('user_group_sending_servers')->insert([
            'id' => 5,
            'sending_server_id' => 5,
            'user_group_id' => 1,
            'fitness' => 100,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);
    }
}
