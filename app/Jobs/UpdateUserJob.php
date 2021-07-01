<?php

namespace Acelle\Jobs;

class UpdateUserJob extends Base
{
    protected $customer;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->customer->updateCache();
    }
}
