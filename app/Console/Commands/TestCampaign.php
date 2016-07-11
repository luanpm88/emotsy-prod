<?php

namespace Mailixa\Console\Commands;

use Illuminate\Console\Command;
use Mailixa\Library\Log;
use Mailixa\Model\Campaign;
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
        //$handler = \Mailixa\Model\FeedbackLoopHandler::first();
        //$handler->start();

        /*
        Log::configure(storage_path() . '/logs/mail.log');

        Log::info('hello');
        Log::error('hello');
        Log::warning('hello');
        Log::alert('hello');
        Log::emergency('hello');
        Log::debug('hello');
        Log::critical('hello');
        Log::notice('hello');
        */

        $c = Campaign::first();
        $c->status = 'pending';
        $c->save();

        echo \Mailixa\Model\SendingServer::where('id', '>=', function($query){
            $query->select(DB::raw('count(*)'))->from('tracking_logs')->whereRaw('sending_server_id = 1 AND `create_at` BETWEEN NOW() - INTERVAL 1 HOUR AND NOW()');
        })->toSql() . "\n";
    }
}
