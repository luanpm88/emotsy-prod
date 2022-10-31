<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CopyDefaultDarkLogoSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (\Acelle\Model\Setting::get('site_logo_light') &&
            !\Acelle\Model\Setting::get('site_logo_dark')
        ) {
            \Acelle\Model\Setting::set('site_logo_dark', \Acelle\Model\Setting::get('site_logo_light'));
        }
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
