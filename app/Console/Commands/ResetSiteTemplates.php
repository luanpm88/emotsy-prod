<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;

class ResetSiteTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-site-templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $category = \Acelle\Model\TemplateCategory::firstOrCreate(
            ['name' => 'Site'], // Conditions to check
        );

        // LIST all form templates here
        $templates = [
            [
                'name' => 'Blank',
                'dir' => database_path('templates/site/0000-blank/6037a26b22000'),
            ], [
                'name' => 'Simple',
                'dir' => database_path('templates/site/0001-simple/6037a26b22001'),
            ], [
                'name' => 'Besiness Frontpage',
                'dir' => database_path('templates/site/0002-business-frontpage/6037a26b22002'),
            ], [
                'name' => 'Freelancer',
                'dir' => database_path('templates/site/0003-freelancer/6037a26b22003'),
            ], [
                'name' => 'Heroic Features',
                'dir' => database_path('templates/site/0004-heroic-features/6037a26b22004'),
            ], [
                'name' => 'Landing Page',
                'dir' => database_path('templates/site/0005-landing-page/6037a26b22005'),
            ], [
                'name' => 'Stylish Portfolio',
                'dir' => database_path('templates/site/0006-stylish-portfolio/6037a26b22006'),
            ], [
                'name' => 'Agency',
                'dir' => database_path('templates/site/0007-agency/6037a26b22007'),
            ],
        ];

        // Delete existing template and create again with new updates
        foreach ($templates as $meta) {
            // UID is the folder name, "5d7b527be2bd2" for example
            $uid = basename($meta['dir']);

            // DELETE existing template, in case this migration is executed many times
            \Acelle\Model\Template::where('uid', $uid)->delete();

            // Build $template attributes and create
            $meta['uid'] = $uid;
            $meta['is_default'] = true; // default templates that are originally created
            $meta['builder'] = true;
            $meta['type'] = \Acelle\Model\Template::TYPE_SITE;
            $template = \Acelle\Model\Template::createFromDirectory($meta, $meta['dir']);
        }
    }
}
