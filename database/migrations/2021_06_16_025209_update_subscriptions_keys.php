<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Acelle\Model\Subscription;
class UpdateSubscriptionsKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Clean up all subscription
        $subscriptions = Subscription::all();
        foreach($subscriptions as $s) {
            $s->delete();
        }


        // Columns to change
        $columns = ['customer_id', 'plan_id', 'user_id'];

        // Drop foreign keys
        foreach($columns as $column) {
            try {
                Schema::table('subscriptions', function (Blueprint $table) use ($column) {
                    $table->dropForeign([$column]);
                });
            } catch(\Exception $ex) {
                // ignore
            }
            
        }

        // Drop columns
        foreach($columns as $column) {
            try {
                Schema::table('subscriptions', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            } catch(\Exception $ex) {
                // ignore
            }
        }

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->integer('customer_id')->unsigned();
            $table->integer('plan_id')->unsigned();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->renameColumn('customer_id', 'user_id');
        });
    }
}
