<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertUrlRootSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Trick here
        preg_match('/^https{0,1}:\/\/[^\/]*/', \Acelle\Model\Setting::get('url_delivery_handler'), $result);
        \Acelle\Model\Setting::set('url_root', $result[0]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
