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
        Schema::table('customers', function ($table) {
            $table->integer('uid')->unsigned()->change();
        });
        
        Schema::table('subscriptions', function ($table) {
            $table->integer('user_id')->unsigned()->change();
            $table->integer('plan_id')->unsigned()->change();

            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('customers')->onDelete('cascade');
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
