<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;
use Acelle\Library\Log;
use Acelle\Model\Campaign;
use Acelle\Model\TrackingLog;
use Acelle\Model\SendingServer;
use Acelle\Model\SendingServerElasticEmailApi;
use Acelle\Model\SendingServerElasticEmail;

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
        $job = new \Acelle\Jobs\ImportSubscribersJob(\Acelle\Model\MailList::first(), \Acelle\Model\User::first());
        //$systemJob = $job->getSystemJob();

        dispatch($job);
    }
}
