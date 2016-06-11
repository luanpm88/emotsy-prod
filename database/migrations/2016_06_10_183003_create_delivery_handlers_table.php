<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryHandlersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_handlers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('host');
            $table->string('username');
            $table->string('password');
            $table->integer('port');
            $table->string('encryption');
            
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
        Schema::drop('delivery_handlers');
    }
}
