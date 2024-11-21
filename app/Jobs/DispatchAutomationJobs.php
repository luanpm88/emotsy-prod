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

use Acelle\Model\Customer;

class DispatchAutomationJobs implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $customers = Customer::all();
        foreach ($customers as $customer) {

            $automations = $customer->local()->activeAutomation2s;

            foreach ($automations as $automation) {
                if (!config('app.saas') || $customer->getCurrentActiveGeneralSubscription()) {

                    $automation->logger()->info(sprintf('Dispatch automation "%s"', $automation->name));
                    $job = new RunAutomation($automation);
                    dispatch($job->onQueue(ACM_QUEUE_TYPE_HIGH));

                } else {
                    $automation->logger()->warning(sprintf('Automation "%s" skipped, user "%s" not on active subscription', $automation->name, $customer->displayName()));
                }
            }
        }
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public $uniqueFor = 120;
    public function uniqueId()
    {
        // a fixed id, making sure only one job in queue at any given time
        return 1;
    }

    public function uniqueVia(): Repository
    {
        return Cache::driver('file');
    }

    public function middleware()
    {
        return [(new WithoutOverlapping(1))->dontRelease()->expireAfter(120)];
    }


}
