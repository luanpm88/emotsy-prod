<?php

namespace Mailixa\Console\Commands;

use Illuminate\Console\Command;
use Mailixa\Model\Campaign;
use Mailixa\Model\User;
use Mailixa\Model\SendingServer;
use Mailixa\Library\Log;
use DB;

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
        //$u = User::first();
        //$u->overQuota(); 
        DB::enableQueryLog();

        $s = SendingServer::find(3);
        echo $s->sendingQuota();

        var_dump(Campaign::getPendingCampaigns());

        dd(DB::getQueryLog());

    }
}
