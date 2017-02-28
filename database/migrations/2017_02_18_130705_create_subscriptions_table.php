<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('plan_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->integer('admin_id')->unsigned()->nullable();
            $table->text('options');
            $table->decimal('price', 16, 2);
            $table->string('currency_code');
            $table->string('plan_name');
            $table->string('plan_color');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->string('status');
            
            $table->timestamps();
            
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
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
        Schema::drop('subscriptions');
    }
}
