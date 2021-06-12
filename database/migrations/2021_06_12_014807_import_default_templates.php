<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImportDefaultTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $templates = [
            [
                'name' => 'Blank',
                'dir' => database_path('templates/basic/6037a0a8583a7'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
            ],
            [
                'name' => 'Pricing Table',
                'dir' => database_path('templates/basic/6037a2135b974'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
            ],
            [
                'name' => 'Listing & Tables',
                'dir' => database_path('templates/basic/6037a2250a3a3'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
            ],
            [
                'name' => 'One column layout',
                'dir' => database_path('templates/basic/6037a28418c95'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
            ],
            [
                'name' => '1-2 column layout',
                'dir' => database_path('templates/basic/6037a24ebdbd6'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
            ],
            [
                'name' => '1-2-1 column layout',
                'dir' => database_path('templates/basic/6037a2401b055'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
            ],
            [
                'name' => '1-3 column layout',
                'dir' => database_path('templates/basic/6037a275bf375'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
            ],
            [
                'name' => '1-3-1 column layout',
                'dir' => database_path('templates/basic/6037a25ddce80'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
            ],
            [
                'name' => '1-3-2 column layout',
                'dir' => database_path('templates/basic/6037a26b0a286'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
            ],
            [
                'name' => 'Two columns layout',
                'dir' => database_path('templates/basic/6037a2b67ed27'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
            ],
            [
                'name' => '2-1 column layout',
                'dir' => database_path('templates/basic/6037a2aa315d4'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
            ],
            [
                'name' => '2-1-2 column layout',
                'dir' => database_path('templates/basic/6037a29a35e05'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
            ],
            [
                'name' => 'Three columns layout',
                'dir' => database_path('templates/basic/6037a2dcb6c56'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
            ],
            [
                'name' => '3-1-3 column layout',
                'dir' => database_path('templates/basic/6037a2c3d7fa1'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
            ],
            [
                'name' => 'Blank',
                'dir' => database_path('templates/basic/6037a0a8583a7'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
            ],
            [
                'name' => 'Abandoned Cart Email #2',
                'dir' => database_path('templates/woos/woo-2'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_WOO,
            ],
            [
                'name' => 'Abandoned Cart Email #3',
                'dir' => database_path('templates/woos/woo-3'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_WOO,
            ],
            [
                'name' => 'Abandoned Cart Email #4',
                'dir' => database_path('templates/woos/woo-4'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_WOO,
            ],
            [
                'name' => 'Certified Yoga Therapist',
                'dir' => database_path('templates/themes/5d7b525bc015d'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_THEME,
            ],
            [
                'name' => 'Color Print - Your Print Companion',
                'dir' => database_path('templates/themes/5d7b526bbfd4c'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_THEME,
            ],
            [
                'name' => 'Gift Card!',
                'dir' => database_path('templates/themes/5d7b527be2bd2'),
                'source' => \Acelle\Model\Template::SOURCE_STANDARD,
                'category' => \Acelle\Model\Template::CATEGORY_THEME,
            ],
        ];

        foreach ($templates as $template) {
            \Acelle\Model\Template::createFromDir($template, $template['dir']);
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
