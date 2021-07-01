<?php

namespace Acelle\Jobs;

use Acelle\Library\Log as MailLog;

class UpdateCampaignJob extends Base
{
    protected $campaign;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->campaign->updateCache();
    }
}
