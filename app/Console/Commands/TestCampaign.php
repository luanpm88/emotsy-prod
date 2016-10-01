<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;
use Acelle\Library\Log;
use Acelle\Model\Campaign;
use Acelle\Model\TrackingLog;
use Acelle\Model\SendingServer;

class TestCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 
    }
}
