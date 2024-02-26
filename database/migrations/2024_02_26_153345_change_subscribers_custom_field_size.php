<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Acelle\Model\MailList;
use Acelle\Model\Subscriber;

class ChangeSubscribersCustomFieldSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach(MailList::listCustomFields() as $fieldName) {
            if (Schema::hasColumn('subscribers', $fieldName)) {

                Schema::table('subscribers', function (Blueprint $table) use ($fieldName) {
                    $table->mediumText($fieldName)->nullable()->change();
                });

            }
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
