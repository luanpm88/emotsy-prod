<?php

namespace Acelle\Jobs;

class UpdateSegmentJob extends Base implements ShouldBeUnique
{
    protected $segment;

    public function __construct($segment)
    {
        $this->segment = $segment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->segment->updateCache();
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public $uniqueFor = 60 * 24 * 30; // 1 month
    public function uniqueId()
    {
        return $this->segment->id;
    }
}
