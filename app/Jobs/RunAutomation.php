<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\Repository;
use Acelle\Model\Automation2;

class RunAutomation implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $automation;
    protected $customer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Automation2 $automation)
    {
        $this->automation = $automation;
        $this->customer = $automation->customer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->customer->setUserDbConnection();

        with_cache_lock("run_automation_{$this->automation->uid}", function () {
            $this->automation->check();
        }, $getLockTimeout = 2, $lockTime = 1200, $waitTimeoutCallback = function () {
            $this->automation->logger()->info('Cannot get lock, silently quit');
        });
    }
}
