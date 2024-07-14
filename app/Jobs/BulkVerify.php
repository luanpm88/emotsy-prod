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

class BulkVerify implements ShouldQueue
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
    public $tries = 1;

    protected $server;
    protected $mailList;
    protected $subscription;

    function __construct(MailList $mailList, EmailVerificationServer $server, Subscription $subscription)
    {
        $this->server = $server;
        $this->mailList = $mailList;
        $this->subscription = $subscription;
    }

    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        $maxPerBatch = 100;
        $subscribersQuery = $this->mailList->subscribers()->unverified()->limit($maxPerBatch)->getQuery();
        $logger = $this->mailList->logger('Starting...');

        if (!$subscribersQuery->exists()) {
            $logger->info('No contact left, verification done');
            return;
        }

        // Submit a partition of list to the remote verification service
        // Get the session token or id, use it to check status
        $token = $this->server->getServiceClient()->bulkSubmit($subscribersQuery);

        // Dispatch checker job
        $bulkCheck = new BulkCheck($this->mailList, $this->server, $this->subscription, $token);
        $bulkCheck->setMonitor($this->monitor);
        $this->batch()->add($bulkCheck);
    }
}