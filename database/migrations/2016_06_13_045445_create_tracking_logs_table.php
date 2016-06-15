<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
      
class CreateTrackingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('message_id');
            $table->integer('server_id');
            $table->integer('campaign_id');
            $table->string('status');
            $table->string('note');
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
        Schema::drop('tracking_logs');
    }
}
