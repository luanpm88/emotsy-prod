<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAutoTriggersSetSubscriberIdNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // In case we need to keep auto triggers for actions like DELETE / MOVE contacts
        Schema::table('auto_triggers', function (Blueprint $table) {
            $table->integer('subscriber_id')->unsigned()->nullable()->change();
        });

        Schema::table('auto_triggers', function (Blueprint $table) {
            $table->dropForeign(['subscriber_id']);
        });

        Schema::table('auto_triggers', function (Blueprint $table) {
            $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auto_triggers', function (Blueprint $table) {
            //
        });
    }
}
