<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InitAppRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Default role: administrator
        $administratorRole = \Acelle\Model\Role::newDefault();
        $administratorRole->fillAttributes([
            'name' => 'Full Access',
            'description' => 'The default role for new users who have full access to the customer account.',
        ]);
        $administratorRole->default = true;
        $administratorRole->readonly = true;
        $administratorRole->save();

        // add permission
        $administratorRole->addPermission('account.full_access');


        // 2. Default role: administrator
        $readOnlyRole = \Acelle\Model\Role::newDefault();
        $readOnlyRole->fillAttributes([
            'name' => 'Read Only',
            'description' => 'The default role for new users who can view all customer account data without the ability to edit or change it.',
        ]);
        $readOnlyRole->default = false;
        $readOnlyRole->readonly = true;
        $readOnlyRole->save();

        // add permission
        $readOnlyRole->addPermission('account.read_only');

        // 3. add administrator role to all old users
        foreach(\Acelle\Model\User::whereDoesntHave('roles')->get() as $user) {
            $user->setRole($administratorRole);
        }
    }
}
