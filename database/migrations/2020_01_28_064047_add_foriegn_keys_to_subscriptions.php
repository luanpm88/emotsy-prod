<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForiegnKeysToSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans', function ($table) {            
            $table->index('uid');
        });

        Schema::table('customers', function ($table) {            
            $table->index('uid');
        });

        \Acelle\Cashier\Subscription::whereRaw(sprintf('(%s NOT IN (SELECT uid FROM %s) OR %s NOT IN (SELECT uid FROM %s))', 'user_id', table('customers'), 'plan_id', table('plans')))->delete();

        Schema::table('subscriptions', function ($table) {
            \Doctrine\DBAL\Types\Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');
            
            $table->uuid('user_id')->change();
            $table->uuid('plan_id')->change();

            $table->foreign('plan_id')->references('uid')->on('plans')->onDelete('cascade');
            $table->foreign('user_id')->references('uid')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            //
        });
    }
}
