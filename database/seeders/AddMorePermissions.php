<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AddMorePermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // add permissions to all roles that have account full access permission
        $rolesThatHaveAccountFullAccessPermission = \Acelle\Model\Role::whereHas('rolePermissions', function($query) {
            $query->where('permission', 'account.full_access');
        })->get();
        foreach ($rolesThatHaveAccountFullAccessPermission as $role) {
            $role->addPermission('campaign.full_access');
            $role->addPermission('list.full_access');
            $role->addPermission('automation.full_access');
        }

        // add permissions to all roles that have account read-only permission
        $rolesThatHaveAccountFullAccessPermission = \Acelle\Model\Role::whereHas('rolePermissions', function($query) {
            $query->where('permission', 'account.read_only');
        })->get();
        foreach ($rolesThatHaveAccountFullAccessPermission as $role) {
            $role->addPermission('campaign.read_only');
            $role->addPermission('list.read_only');
            $role->addPermission('automation.read_only');
        }
    }
}
