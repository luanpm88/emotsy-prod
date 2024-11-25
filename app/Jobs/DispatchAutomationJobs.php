<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\Repository;

use Acelle\Model\Customer;

class DispatchAutomationJobs implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle()
    {
        with_cache_lock('lock_run_all_automations', function() {
            $this->run();
        }, $getLockTimeout = 5, $lockExpiresAfter = 120, $timeoutCallback = function() {
            // do nothing, do not throw exception
        });
    }

    private function run()
    {
        $customers = Customer::all();
        foreach ($customers as $customer) {

            $automations = $customer->local()->activeAutomation2s;

            foreach ($automations as $automation) {
                if (!config('app.saas') || $customer->getCurrentActiveGeneralSubscription()) {

                    $automation->logger()->info(sprintf('Dispatch automation "%s"', $automation->name));
                    $job = new RunAutomation($automation);
                    safe_dispatch($job->onQueue(ACM_QUEUE_TYPE_HIGH));

                } else {
                    $automation->logger()->warning(sprintf('Automation "%s" skipped, user "%s" not on active subscription', $automation->name, $customer->displayName()));
                }
            }
        }
    }
}
