<?php

namespace Mailixa\Console\Commands;

use Illuminate\Console\Command;
use Mailixa\Model\Campaign;
use Mailixa\Library\Log;

class SendCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        // configure the logger
        Log::configure(storage_path() . '/logs/mail.log');
        
        // get campaigns to run
        $campaigns = Campaign::getPendingCampaigns();
        Log::info(sizeof($campaigns) . " pending campaign(s) found");

        $index = 0;
        foreach ($campaigns as $campaign) {
            $index += 1;
            Log::info("Start sending campaign $index/" . sizeof($campaigns));
            //$campaign->sendCampaign();
        }
    }
}
