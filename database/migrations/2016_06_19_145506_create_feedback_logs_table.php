<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message_id');
            $table->string('feedback_type');
            $table->text('raw_feedback_content');
            
            $table->timestamps();
            
            // foreign
            $table->foreign('message_id')->references('message_id')->on('tracking_logs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('feedback_logs');
    }
}
