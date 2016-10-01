<?php

/**
 * RunCampaign class.
 *
 * CLI interface for trigger email sending (by cronjob)
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   Console App
 *
 * @author     N. Pham <n.pham@acellemail.com>
 * @author     L. Pham <l.pham@acellemail.com>
 * @copyright  Acelle Co., Ltd
 * @license    Acelle Co., Ltd
 *
 * @version    1.0
 *
 * @link       http://acellemail.com
 */

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;
use Acelle\Library\Log;
use Acelle\Model\Campaign;

class RunCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:run';

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
        // initialize the logging engine
        Log::configure(storage_path().'/logs/mail.log');

        try {
            $this->execRunCampaign();
            Log::info('Campaigns finished!');
        } catch (\Exception $e) {
            Log::error('Something went wrong: '.$e->getMessage());
        }
    }
    
    /**
     * Actually send the campaign.
     *
     */
    private function execRunCampaign()
    {
        // guarantee that only one process can be run at one time
        // use socket as lock
        Log::info('Try to start campaigns...');
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (false === $socket) {
            throw new Exception("can't create socket: ".socket_last_error($socket));
        }

        // hide warning, because error will be checked manually
        if (false === @socket_bind($socket, '127.0.0.1', 60202)) {
            // some other process is already running
            Log::warning('Another campaign sending process is already running. Terminated!');
            exit;
        } else {
            // just go ahead
            Log::info('Started!');
        }

        $campaigns = Campaign::getWaitingCampaigns();

        Log::info(sizeof($campaigns).' campaigns found');
        $count = 1;
        foreach ($campaigns as $campaign) {
            Log::info('Starting campaign '.$campaign->name." ($count/".sizeof($campaigns).')');
            // Only run one process if pcntl is not enabled
            if (extension_loaded('pcntl')) {
                $campaign->startMultiProcesses();
            } else {
                $campaign->start();
            }
            Log::info('Finish campaign '.$campaign->name);
            $count += 1;
        }
    }
}
