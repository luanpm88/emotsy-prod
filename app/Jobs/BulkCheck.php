<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Acelle\Library\Traits\Trackable;
use Acelle\Model\Subscription;
use Acelle\Model\MailList;
use Acelle\Model\EmailVerificationServer;
use function Acelle\Helpers\create_temp_db_table;
use DB;

class BulkCheck implements ShouldQueue
{
    use Trackable;
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $timeout = 900;
    public $maxExceptions = 1; // This is required if retryUntil is used, otherwise, the default value is 255
    public $failOnTimeout = true;

    protected $server;
    protected $mailList;
    protected $subscription;
    protected $token;

    function __construct(MailList $mailList, EmailVerificationServer $server, Subscription $subscription, $token)
    {
        $this->mailList = $mailList;
        $this->server = $server;
        $this->subscription = $subscription;
        $this->token = $token;
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        // @important: remember that messages might be released over and over
        // if there is any limit setting in place
        // As a result, it is just save to have it retry virtually forever
        return now()->addDays(7);
    }


    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        $results = [];
        $serviceClient = $this->server->getServiceClient();
        $check = $serviceClient->bulkCheck($this->token, function($email, $verificationStatus, $raw) use (&$results) {
            $results[] = [
                'email' => $email,
                'status' => $verificationStatus,
                'raw' => $raw,
            ];
        });

        if ($check) {
            $this->updateVerificationResults($results);

            // Check done, launch a new round
            $job = new BulkVerify($this->mailList, $this->server, $this->subscription);
            $job->setMonitor($this->monitor);
            $this->batch()->add($job);
        } else {
            // wait until result is available, check again
            $this->release(120);
            return;
        }
    }

    private function updateVerificationResults($results)
    {
        $tmpFields = [
            "`email` TEXT",
            "`status` TEXT",
            "`raw` TEXT",
        ];

        create_temp_db_table($tmpFields, $results, function($tmpTable) {
            $nameWithPrefix = table($tmpTable);

            // IMPORTANT: do not use printf(), use sprintf() instead. printf produces and additional trailing enter char that causes invalid SQL syntax
            DB::statement(sprintf("
                UPDATE %s as s INNER JOIN %s t ON s.email = t.email
                SET
                  s.status = t.status,
                  s.last_verification_at = NOW(),
                  s.last_verification_by = %s,
                  s.last_verification_result = t.raw
                WHERE mail_list_id = %s
            ", table('subscribers'), $nameWithPrefix, db_quote($this->server->name), $this->mailList->id ));
        });
    }
}