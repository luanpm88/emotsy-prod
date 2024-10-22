<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserdbSubscribers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->uuid('uid');

            $table->string('email');
            $table->string('status');
            $table->string('ip')->nullable();
            $table->string('from')->nullable();
            $table->string('subscription_type')->nullable();
            $table->uuid('import_batch_id')->nullable();
            $table->text('tags')->nullable();

            $table->string('verification_status')->nullable();
            $table->string('last_verification_by', 100)->nullable();
            $table->mediumText('last_verification_result')->nullable();
            $table->dateTime('last_verification_at')->nullable();

            // Foreign keys
            $table->bigInteger('mail_list_id')->unsigned();
            $table->bigInteger('customer_id')->unsigned();

            // Foreign constraints
            $table->foreign('mail_list_id')->references('id')->on('mail_lists')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            // Indexes
            $table->unique(['mail_list_id', 'email']);
            $table->index('mail_list_id');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribers');
    }
}
