<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resellers', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('user_id')->unsigned();
            $table->integer('admin_id')->unsigned();
            $table->integer('contact_id')->unsigned()->nullable();
            $table->integer('reseller_group_id')->unsigned();
            $table->integer('language_id')->unsigned()->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('image')->nullable();
            $table->string('timezone');
            $table->string('status')->nullable();
            $table->string('color_scheme')->nullable();
            
            $table->timestamps();
            
            // foreign
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('reseller_group_id')->references('id')->on('reseller_groups')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('resellers');
    }
}
