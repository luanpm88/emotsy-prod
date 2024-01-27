<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_groups', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->string('name');
            $table->decimal('price', 16, 2);
            $table->string('frequency_amount');
            $table->string('frequency_unit');
            $table->text('options');
            $table->text('description')->nullable();
            $table->boolean('visible')->nullable();
            $table->integer('trial_amount')->nullable();
            $table->string('trial_unit')->nullable();
            $table->boolean('own_tracking_domain_required')->nullable();
            $table->string('type');

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
        Schema::dropIfExists('plan_groups');
    }
}
