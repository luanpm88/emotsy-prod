<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResellerGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reseller_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('options');
            $table->text('permissions');
            $table->integer('custom_order')->default(0);
            $table->integer('admin_id')->unsigned();
            
            $table->timestamps();
            
            // foreign
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reseller_groups');
    }
}
