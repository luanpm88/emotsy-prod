<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Acelle\Model\Automation2;

class UpdateAutomation extends Base implements ShouldBeUnique
{
    protected $automation;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($automation)
    {
        $this->automation = $automation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->automation->mailList()->exists()) {
            $this->automation->updateCache();
        }
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public $uniqueFor = 60 * 24 * 30; // 1 month
    public function uniqueId()
    {
        return $this->automation->id;
    }
}
