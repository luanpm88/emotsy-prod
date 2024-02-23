<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixLayouts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (\Acelle\Model\Layout::all() as $layout) {
            $layout->content = str_replace("\\r\\n", " ", $layout->content);
            $layout->save();
        }

        foreach (\Acelle\Model\Page::all() as $page) {
            $page->content = str_replace("\\r\\n", " ", $page->content);
            $page->save();
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
