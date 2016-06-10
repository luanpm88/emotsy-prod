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
            $table->integer('user_id');
            $table->integer('list_id');
            $table->integer('segment_id');
            $table->text('type');
            $table->text('name');
            $table->text('subject');
            $table->text('html');
            $table->text('plain');
            $table->text('from_email');
            $table->text('from_name');
            $table->text('to_name');
            $table->text('reply_to');
            $table->text('status');
            $table->boolean('sign_dkim');
            $table->boolean('track_open');
            $table->boolean('track_fbl');
            $table->boolean('track_click');
            $table->integer('resend');
            $table->timestamps();

            // $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('list_id')->references('id')->on('lists');
            // $table->foreign('segment_id')->references('id')->on('segments');
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
