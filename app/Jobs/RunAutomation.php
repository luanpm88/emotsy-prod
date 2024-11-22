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
        $this->automation->check();
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public $uniqueFor = 1200; // 30 days
    public function uniqueId()
    {
        // a fixed id, making sure only one job in queue at any given time
        return $this->automation->uid;
    }

    // For some reason, cache "redis" does not work
    public function uniqueVia(): Repository
    {
        return Cache::driver('file');
    }

    public function middleware()
    {
        return [(new WithoutOverlapping($this->automation->uid))->dontRelease()->expireAfter(1200)];
    }
}
