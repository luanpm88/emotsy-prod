<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutomationTriggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('automation_triggers', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('automation_id')->unsigned();
            $table->string('type_name');
            $table->timestamp('send_at')->nullable();
            $table->timestamp('recurring_send_at')->nullable();
            $table->string('recurring_type')->nullable();
            $table->integer('recurring_day_of_week')->nullable();
            $table->boolean('recurring_only_first_week')->nullable();
            $table->integer('recurring_month')->nullable();
            $table->integer('recurring_day_of_month')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('automation_triggers');
    }
}
