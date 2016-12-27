<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;
use Acelle\Library\Log;
use Acelle\Library\StringHelper;
use Acelle\Library\QuotaTracker;
use Acelle\Model\Campaign;
use Acelle\Model\User;
use Acelle\Model\MailList;
use Acelle\Model\Subscriber;
use Acelle\Model\TrackingLog;
use Acelle\Model\AutoEvent;
use Acelle\Model\SendingServer;
use Acelle\Model\SendingServerElasticEmailApi;
use Acelle\Model\SendingServerElasticEmail;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

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
        $e = AutoEvent::first();
        $e->check();
    }
}
