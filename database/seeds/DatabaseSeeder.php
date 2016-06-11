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
        $updated_at = Carbon\Carbon::now();
        
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
            'name' => 'Việt Nam',
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
        
        // Create default admin user
        DB::table('users')->insert([
            'id' => 1,
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
    }
}
