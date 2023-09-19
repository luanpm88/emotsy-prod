<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertDefaultAccountRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // default role
        $role = \Acelle\Model\Role::newDefault();
        $role->fillAttributes([
            'name' => 'Administrator',
            'description' => 'The default role for new customers with full access to the account',
        ]);
        $role->default = true;
        $role->save();

        foreach (config('permissions.account') as $permission) {
            // default role permission
            $rolePermission = new \Acelle\Model\RolePermission();
            $rolePermission->role_id = $role->id;
            $rolePermission->permission = $permission;
            $rolePermission->save();
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
