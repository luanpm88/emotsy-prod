<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CreateUpdateDefaultAppRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Default role: Full Access
        $administratorRole = \Acelle\Model\Role::global()
            ->readonly()
            ->whereName('Full Access')
            ->first();
        if (!$administratorRole) {
            $administratorRole = \Acelle\Model\Role::newGlobalRole();
            $administratorRole->saveRole(
                $name = 'Full Access',
                $description = 'The default role for new users who have full access to the customer account.',
                $permissions = []
            );
            $administratorRole->default = true;
            $administratorRole->readonly = true;
            $administratorRole->save();
        }
            

        // add permission
        $administratorRole->addPermission('campaign.full_access');
        $administratorRole->addPermission('list.full_access');
        $administratorRole->addPermission('automation.full_access');
        $administratorRole->addPermission('template.full_access');
        $administratorRole->addPermission('form.full_access');
        $administratorRole->addPermission('sending_server.full_access');
        $administratorRole->addPermission('sending_domain.full_access');
        $administratorRole->addPermission('account.full_access');


        // 2. Default role: Read Only
        $readOnlyRole = \Acelle\Model\Role::global()
            ->readonly()
            ->whereName('Read Only')
            ->first();
        if (!$readOnlyRole) {
            $readOnlyRole = \Acelle\Model\Role::newGlobalRole();
            $readOnlyRole->saveRole(
                $name = 'Read Only',
                $description = 'The default role for new users who can view all customer account data without the ability to edit or change it.',
                $permissions = []
            );
            $readOnlyRole->default = false;
            $readOnlyRole->readonly = true;
            $readOnlyRole->save();
        }

        // add permission
        $readOnlyRole->addPermission('campaign.read_only');
        $readOnlyRole->addPermission('list.read_only');
        $readOnlyRole->addPermission('automation.read_only');
        $readOnlyRole->addPermission('template.read_only');
        $readOnlyRole->addPermission('form.read_only');
        $readOnlyRole->addPermission('sending_server.read_only');
        $readOnlyRole->addPermission('sending_domain.read_only');
        $readOnlyRole->addPermission('account.read_only');
    }
}
