<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultCurrency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $currency = new Acelle\Model\Currency();
        $currency->id = 1;
        $currency->uid = '58bd48f91f10b';
        $currency->name = 'US Dollar';
        $currency->code = 'USD';
        $currency->format = '${PRICE}';
        $currency->status = 'active';
        $currency->save();
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
