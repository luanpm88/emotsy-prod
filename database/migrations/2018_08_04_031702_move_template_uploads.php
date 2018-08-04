<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveTemplateUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Acelle\Library\Tool::xcopy(public_path('upload/templates'),storage_path('app/templates'), '0777');
        \Acelle\Library\Tool::xdelete(public_path('upload/templates'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Acelle\Library\Tool::xcopy(storage_path('app/templates'), public_path('upload/templates'), '0777');
        \Acelle\Library\Tool::xdelete(storage_path('app/templates'));
    }
}
