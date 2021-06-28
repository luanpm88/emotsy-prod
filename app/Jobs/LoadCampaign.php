<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Acelle\Model\Campaign;
use Acelle\Model\Subscriber;

class LoadCampaign implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

        Collection::times(3, function () {
            sleep(2);
            $subscriber = Subscriber::first();
            $this->batch()->add(new SendMessage($subscriber));
        });

        Collection::times(3, function () {
            sleep(2);
            $subscriber = Subscriber::first();
            $this->batch()->add(new SendMessage($subscriber));
        });
    }
}
