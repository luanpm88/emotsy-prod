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
        // Default templates
        $templates = [
            [
                'name' => 'Blank',
                'dir' => database_path('templates/basic/6037a0a8583a7'),
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
                'builder' => true,
            ],
            [
                'name' => 'Pricing Table',
                'dir' => database_path('templates/basic/6037a2135b974'),
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
                'builder' => true,
            ],
            [
                'name' => 'Listing & Tables',
                'dir' => database_path('templates/basic/6037a2250a3a3'),
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
                'builder' => true,
            ],
            [
                'name' => 'One column layout',
                'dir' => database_path('templates/basic/6037a28418c95'),
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
                'builder' => true,
            ],
            [
                'name' => '1-2 column layout',
                'dir' => database_path('templates/basic/6037a24ebdbd6'),
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
                'builder' => true,
            ],
            [
                'name' => '1-2-1 column layout',
                'dir' => database_path('templates/basic/6037a2401b055'),
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
                'builder' => true,
            ],
            [
                'name' => '1-3 column layout',
                'dir' => database_path('templates/basic/6037a275bf375'),
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
                'builder' => true,
            ],
            [
                'name' => '1-3-1 column layout',
                'dir' => database_path('templates/basic/6037a25ddce80'),
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
                'builder' => true,
            ],
            [
                'name' => '1-3-2 column layout',
                'dir' => database_path('templates/basic/6037a26b0a286'),
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
                'builder' => true,
            ],
            [
                'name' => 'Two columns layout',
                'dir' => database_path('templates/basic/6037a2b67ed27'),
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
                'builder' => true,
            ],
            [
                'name' => '2-1 column layout',
                'dir' => database_path('templates/basic/6037a2aa315d4'),
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
                'builder' => true,
            ],
            [
                'name' => '2-1-2 column layout',
                'dir' => database_path('templates/basic/6037a29a35e05'),
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
                'builder' => true,
            ],
            [
                'name' => 'Three columns layout',
                'dir' => database_path('templates/basic/6037a2dcb6c56'),
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
                'builder' => true,
            ],
            [
                'name' => '3-1-3 column layout',
                'dir' => database_path('templates/basic/6037a2c3d7fa1'),
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
                'builder' => true,
            ],
            [
                'name' => 'Blank',
                'dir' => database_path('templates/basic/6037a0a8583a7'),
                'category' => \Acelle\Model\Template::CATEGORY_BASIC,
                'builder' => true,
            ],
            [
                'name' => 'Abandoned Cart Email #2',
                'dir' => database_path('templates/woos/woo-2'),
                'category' => \Acelle\Model\Template::CATEGORY_WOO,
                'builder' => true,
            ],
            [
                'name' => 'Abandoned Cart Email #3',
                'dir' => database_path('templates/woos/woo-3'),
                'category' => \Acelle\Model\Template::CATEGORY_WOO,
                'builder' => true,
            ],
            [
                'name' => 'Abandoned Cart Email #4',
                'dir' => database_path('templates/woos/woo-4'),
                'category' => \Acelle\Model\Template::CATEGORY_WOO,
                'builder' => true,
            ],
            [
                'name' => 'Certified Yoga Therapist',
                'dir' => database_path('templates/themes/5d7b525bc015d'),
                'category' => \Acelle\Model\Template::CATEGORY_THEME,
                'builder' => true,
            ],
            [
                'name' => 'Color Print - Your Print Companion',
                'dir' => database_path('templates/themes/5d7b526bbfd4c'),
                'category' => \Acelle\Model\Template::CATEGORY_THEME,
                'builder' => true,
            ],
            [
                'name' => 'Gift Card!',
                'dir' => database_path('templates/themes/5d7b527be2bd2'),
                'category' => \Acelle\Model\Template::CATEGORY_THEME,
                'builder' => true,
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
