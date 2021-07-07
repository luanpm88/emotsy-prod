<?php

namespace Acelle\Jobs;

use Acelle\Model\MailList;

class VerifyMailListJob extends Base
{
    protected $mailListId;
    protected $serverId;

    /**
     * Create a new job instance.
     * @note: Parent constructors are not called implicitly if the child class defines a constructor.
     *        In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * @return void
     */
    public function __construct($mailListId, $serverId)
    {
        $this->mailListId = $mailListId;
        $this->serverId = $serverId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $list = MailList::find($this->mailListId);
        $list->runVerification($this->serverId);
    }
}
