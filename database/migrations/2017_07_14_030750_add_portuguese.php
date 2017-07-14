<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Acelle\Model\Language;

class AddPortuguese extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $lang = new Language();
        $lang->name = 'Portuguese';
        $lang->code = 'pt';
        $lang->region_code = 'pt';
        $lang->status = 'active';
        $lang->save();
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
