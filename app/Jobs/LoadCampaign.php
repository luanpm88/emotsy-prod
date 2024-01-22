<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Acelle\Library\Contracts\CampaignInterface;
use Acelle\Library\Traits\Trackable;
use Closure;
use Carbon\Carbon;

class LoadCampaign implements ShouldQueue
{
    use Trackable;
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    // @important: set the "retry_after" setting in config/queue.php to a value that is greater than $timeout;
    public $timeout = 7200;
    public $failOnTimeout = true;
    public $tries = 1;
    public $maxExceptions = 1;

    protected CampaignInterface $campaign;
    protected $page;
    protected $listOfIds;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CampaignInterface $campaign, int $page, array $listOfIds)
    {
        $this->campaign = $campaign;
        $this->page = $page;
        $this->listOfIds = $listOfIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        $this->campaign->logger()->info(sprintf('Loading contacts for page %s (#%s)', $this->page, sizeof($this->listOfIds)));

        $this->campaign->loadDeliveryJobsByIds(function (ShouldQueue $deliveryJob) {
            $this->batch()->add($deliveryJob);
        }, $this->page, $this->listOfIds);

        $this->campaign->debug(function ($info) {
            // Record last activity, no matter it is a successful delivery or exception
            // This information is useful when we want to audit delivery processes
            // i.e. when we can to automatically restart dead jobs for example
            $info['last_activity_at'] = Carbon::now()->toString();

            // Must return;
            return $info;
        });
    }
}
