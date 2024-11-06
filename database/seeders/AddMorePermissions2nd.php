<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AddMorePermissions2nd extends Seeder
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
            $role->addPermission('template.full_access');
            $role->addPermission('form.full_access');
            $role->addPermission('sending_server.full_access');
            $role->addPermission('sending_domain.full_access');
        }

        // add permissions to all roles that have account read-only permission
        $rolesThatHaveAccountFullAccessPermission = \Acelle\Model\Role::whereHas('rolePermissions', function($query) {
            $query->where('permission', 'account.read_only');
        })->get();
        foreach ($rolesThatHaveAccountFullAccessPermission as $role) {
            $role->addPermission('template.read_only');
            $role->addPermission('form.read_only');
            $role->addPermission('sending_server.read_only');
            $role->addPermission('sending_domain.read_only');
        }
    }
}
