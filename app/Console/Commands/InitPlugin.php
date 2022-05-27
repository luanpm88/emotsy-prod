<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;
use Acelle\Model\Plugin;

class InitPlugin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:init {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a sample plugin for Acelle. For example: php artisan plugin:init awesome/my_plugin';

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
        $name = $this->argument('name');
        Plugin::init($name);
    }
}
