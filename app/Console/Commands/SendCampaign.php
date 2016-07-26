<?php

namespace Mailixa\Console\Commands;

use Illuminate\Console\Command;
use Mailixa\Model\Campaign;
use Mailixa\Model\User;
use Mailixa\Model\SendingServer;
use Mailixa\Library\Log;
use DB;

class Gaugau {
    public static $gaugau = [];

    public static function add($s) {
        self::$gaugau[] = $s;
    }
}

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
        //DB::enableQueryLog();

        //$s = SendingServer::find(3);
        //echo $s->sendingQuota();

        //var_dump(Campaign::getPendingCampaigns());

        $c = Campaign::find(1);
        $subscribers = $c->getPendingSubscribers();
        
        array_slice($subscribers, 0, 1);
        

        // Creating the pool of threads(stored as array)

        /*
        for ($i = 1; $i <= 2; ++$i) { 
            $pid = pcntl_fork(); 

            if (!$pid) { 
                sleep(1); 
                print "In child $i " . getmypid() . "\n";
                Gaugau::$gaugau[] = $i * 10 + $i;
                sleep(1);
                Gaugau::$gaugau[] = $i * 10 + $i;
                sleep(1);
                Gaugau::$gaugau[] = $i * 10 + $i;
                sleep(1);
                Gaugau::$gaugau[] = $i * 10 + $i;
                sleep(1);
                Gaugau::$gaugau[] = $i * 10 + $i;
                sleep(1);
                Gaugau::$gaugau[] = $i * 10 + $i;
                sleep(1);
                print_r(Gaugau::$gaugau);
                if ($i != 33) {
                    $x = 1/0;
                }
                exit($i); 
            }
        }
        

        while (pcntl_waitpid(0, $status) != -1) { 
            $status = pcntl_wexitstatus ($status);
            echo "Child $status completed\n";
            
        }
        */

        //dd(DB::getQueryLog());

    }
}
