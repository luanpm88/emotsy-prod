<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('user_id')->unsigned();
            $table->integer('mail_list_id')->unsigned()->nullable();
            $table->integer('segment_id')->unsigned()->nullable();
            $table->text('type');
            $table->text('name');
            $table->text('subject');
            $table->text('html');
            $table->text('plain');
            $table->text('from_email');
            $table->text('from_name');
            $table->text('reply_to');
            $table->text('status');
            $table->boolean('sign_dkim');
            $table->boolean('track_open');
            $table->boolean('track_click');
            $table->integer('resend');
            $table->integer('custom_order');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('mail_list_id')->references('id')->on('mail_lists');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('campaigns');
    }
}
