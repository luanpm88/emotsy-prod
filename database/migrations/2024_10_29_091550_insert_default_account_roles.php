<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertDefaultAccountRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // import database via seeder
        \Artisan::call('db:seed', ['--class' => 'CreateUpdateDefaultAppRolesSeeder']);

        // 3. add administrator role to all old users that upgrade to rbac version
        $administratorRole = \Acelle\Model\Role::global()
            ->readonly()
            ->whereName('Full Access')
            ->first();
        foreach(\Acelle\Model\User::whereDoesntHave('roles')->get() as $user) {
            $user->setRole($administratorRole);
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
