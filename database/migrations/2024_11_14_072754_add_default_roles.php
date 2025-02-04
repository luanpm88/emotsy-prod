<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // find or create default admin role
        $adminRole = \Acelle\Model\Role::whereCode('organization_admin')->first();
        if (!$adminRole) {
            $adminRole = \Acelle\Model\Role::newGlobalRole();
            $adminRole->code = 'organization_admin';
            $adminRole->name = 'Full Access';
            $adminRole->description = 'The default role for new users who have full access to the customer account.';
            $adminRole->is_global = true;
            $adminRole->readonly = true;
            $adminRole->save();
        }
        // add permission
        $adminRole->assignDefaultAdminPermissions();

        // find or create default readonly role
        $readonlyRole = \Acelle\Model\Role::whereCode('organization_readonly')->first();
        if (!$readonlyRole) {
            $readonlyRole = \Acelle\Model\Role::newGlobalRole();
            $readonlyRole->code = 'organization_readonly';
            $readonlyRole->name = 'Read Only';
            $readonlyRole->description = 'The default role for new users who can view all customer account data without the ability to edit or change it.';
            $readonlyRole->is_global = true;
            $readonlyRole->readonly = true;
            $readonlyRole->save();
        }
        // add permission
        $readonlyRole->assignDefaultReadonlyPermissions();

        // Add administrator role to all old users that upgrade to rbac version
        foreach(\Acelle\Model\User::whereDoesntHave('roles')->get() as $user) {
            $user->setRole($adminRole);
        }



        // find or create default readonly role
        $role = \Acelle\Model\Role::whereCode('campaign_approver')->first();
        if (!$role) {
            $role = \Acelle\Model\Role::newGlobalRole();
            $role->code = 'campaign_approver';
            $role->name = 'Campaign approver';
            $role->description = 'Can approve campaigns';
            $role->is_global = true;
        }
        $role->readonly = false;
        $role->save();
        // add permission
        $role->assignDefaultCampaignApproverPermissions();

        // Add administrator role to all old users that upgrade to rbac version
        foreach(\Acelle\Model\User::whereDoesntHave('roles')->get() as $user) {
            $user->setRole($role);
        }


        // find or create default readonly role
        $role = \Acelle\Model\Role::whereCode('list_manager')->first();
        if (!$role) {
            $role = \Acelle\Model\Role::newGlobalRole();
            $role->code = 'list_manager';
            $role->name = 'List manager';
            $role->description = 'Can upload lists but not work on the campaigns';
            $role->is_global = true;
        }
        $role->readonly = false;
        $role->save();
        // add permission
        $role->assignDefaultListManagerPermissions();

        // Add administrator role to all old users that upgrade to rbac version
        foreach(\Acelle\Model\User::whereDoesntHave('roles')->get() as $user) {
            $user->setRole($role);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
