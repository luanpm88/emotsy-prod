<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSendingDomains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table('sending_domains', function (Blueprint $table) {
                $table->dropForeign(['admin_id']);
            });

            Schema::table('sending_domains', function (Blueprint $table) {
                $table->dropForeign(['customer_id']);
            });
        } catch (\Exception $ex) {

        }

        try {
            Schema::table('sending_domains', function (Blueprint $table) {
                $table->dropForeign('sending_domains_admin_id_foreign');
            });

            Schema::table('sending_domains', function (Blueprint $table) {
                $table->dropForeign('sending_domains_customer_id_foreign');
            });
        } catch (\Exception $ex) {

        }

        // Clean upu invalid records
        \Acelle\Model\SendingDomain::whereNull('customer_id')->delete();

        // Change
        Schema::table('sending_domains', function (Blueprint $table) {
            $table->integer('admin_id')->unsigned()->nullable()->change();
            $table->integer('customer_id')->unsigned()->nullable(false)->change();

            $table->foreign('admin_id')->references('id')->nullable()->on('admins')->onDelete('set null');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sending_domains', function (Blueprint $table) {
            //
        });
    }
}
