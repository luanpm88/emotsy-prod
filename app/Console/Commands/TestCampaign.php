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
        $list = MailList::first();

        $reader = \League\Csv\Reader::createFromPath('/home/ubuntu/mailixa/users.csv');
        $headers = array_map('strtoupper', $reader->fetchOne());        
        $fields = collect($list->fields)->map(function($field) { return strtoupper($field->tag); })->toArray();

        $availableFields = array_intersect($headers, $fields);
        $batchSize = 1000;

        $results = $reader->fetchAssoc($headers);
        
        // split the entire list into smaller batches
        $this->eachBatch($results, $batchSize, function($batch) use ($availableFields) {
            // processing for every batch, 
            // using transaction to only commit at the end of the batch execution
            $now = Carbon::now()->timestamp;
            
            DB::beginTransaction();
            
            // create a temporary table containing the input subscribers
            $tmpTable = DB::getTablePrefix() . '__tmp_subscribers';
            $tmpFields = implode(',', array_map(function($field) { return "$field VARCHAR(255)"; }, $availableFields));
            DB::statement("DROP TEMPORARY TABLE IF EXISTS {$tmpTable};
                           CREATE TEMPORARY TABLE {$tmpTable}({$tmpFields});");

            // insert subscriber fields from the batch to the temporary table
            $data = collect($batch)->map(function($r) use ($availableFields) {
                // extract the fields whose name matches TAG NAME of MailList
                return array_intersect_key($r, array_flip($availableFields));
            })->toArray();
            DB::table('__tmp_subscribers')->insert($data);

            // insert to the main tables from the temp table
            // join to the main table to make sure no duplication
            DB::statement("DELETE FROM " . DB::getTablePrefix() . "__tmp_subscribers WHERE email IN (SELECT email FROM " . DB::getTablePrefix() . "subscribers)");
            DB::statement("INSERT INTO " . DB::getTablePrefix() . "subscribers(uid, mail_list_id, user_id, email, status, created_at, updated_at) 
                           SELECT 'uid', 3, NULL, uniq.email, '" . Subscriber::STATUS_SUBSCRIBED . "', FROM_UNIXTIME(" . $now . "), FROM_UNIXTIME(" . $now . ")
                           FROM (SELECT DISTINCT email FROM " . DB::getTablePrefix() . "__tmp_subscribers) uniq");
            DB::commit();


            DB::beginTransaction();
            foreach($availableFields as $field) {
                $sql = "INSERT INTO " . DB::getTablePrefix() . "subscriber_fields(subscriber_id, field_id, value, created_at, updated_at)
                SELECT t.subscriber_id, f.id, t.{$field}, FROM_UNIXTIME(" . $now . "), FROM_UNIXTIME(" . $now . ")
                FROM (SELECT main.id AS subscriber_id, tmp.{$field} FROM " . DB::getTablePrefix() . "subscribers main JOIN " . DB::getTablePrefix() . "__tmp_subscribers tmp ON tmp.email = main.email) t
                JOIN " . DB::getTablePrefix() . "fields f ON f.tag = '{$field}'";
                DB::statement($sql);
            }
            DB::commit();
        });
    }

    public function eachBatch($array = [], $batchSize = NULL, $callback) {
        $batch = [];
        foreach($array as $i => $value) {
            // skip the header
            if ($i == 0) {
                continue;
            }

            if ($i % $batchSize == 0) {
                $callback($batch);
                $batch = [];
            }
            $batch[] = $value;
        }
        
        // the last callback
        if (sizeof($batch) > 0) {
            $callback($batch);
        }
    }
}
