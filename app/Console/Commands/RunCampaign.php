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
        //try {
            // initialize the logging engine
            Log::configure(storage_path().'/logs/mail.log');
            
            // Actually run the campaign
            Campaign::run();

            $c = Campaign::where('status', Campaign::STATUS_READY)->orderBy('id', 'DESC')->whereRaw('(run_at IS NULL OR run_at <= NOW())')->limit(1)->first();

            var_dump($c);

        //} catch (\Exception $e) {
        //    Log::error('Something went wrong: '.$e->getMessage());
        //}
    }
}
