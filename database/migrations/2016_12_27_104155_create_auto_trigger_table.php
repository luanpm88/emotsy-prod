<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoTriggerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_triggers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('auto_event_id')->unsigned();
            $table->dateTime('start_at');
            $table->dateTime('finish_at');
            $table->text('last_error');
            $table->timestamps();
            
            $table->foreign('auto_event_id')->references('id')->on('auto_events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('auto_triggers');
    }
}
