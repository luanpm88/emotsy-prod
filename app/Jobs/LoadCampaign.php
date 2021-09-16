<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Acelle\Model\Campaign;
use Acelle\Model\Subscriber;
use Illuminate\Support\Carbon;
use Acelle\Library\Traits\Trackable;

class LoadCampaign implements ShouldQueue
{
    use Trackable;
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $timeout = 7200;
    public $failOnTimeout = true;
    public $tries = 1;
    public $maxExceptions = 1;

    protected $campaign;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
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

        $this->campaign->setSending();

        $this->campaign->prepare(function ($campaign, $subscriber, $server) {
            $this->batch()->add(new SendMessage($campaign, $subscriber, $server));
        });
    }
}
