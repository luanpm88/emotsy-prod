<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendingServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sending_servers', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->string('type');
            $table->string('host');
            $table->string('username');
            $table->string('password');
            $table->integer('port');
            $table->string('sendmail_path');
            $table->string('protocol');
            $table->integer('custom_order');
            
            $table->timestamps();
            
            // foreign
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sending_servers');
    }
}
