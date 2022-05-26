<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;
use function Acelle\Helpers\pcopy;
use Artisan;

class InitPlugin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a sample plugin namely awesome/hello';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        pcopy(database_path('plugins/awesome/hello'), storage_path('app/plugins/awesome/hello'));
        Artisan::call('plugin:load awesome/hello');
    }
}
