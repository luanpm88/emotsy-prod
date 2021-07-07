<?php

namespace Acelle\Jobs;

class UpdateSegmentJob extends Base
{
    protected $segment;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->segment->updateCache();
    }
}
