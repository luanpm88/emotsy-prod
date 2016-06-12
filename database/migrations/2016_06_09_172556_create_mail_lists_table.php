<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('user_id')->unsigned();
            $table->integer('contact_id')->unsigned();
            $table->string('name');
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->string('send_to');
            $table->text('description')->nullable();
            $table->text('email_subscribe');
            $table->text('email_unsubscribe');
            $table->text('email_daily');
            
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
        Schema::drop('mail_lists');
    }
}
