<?php

namespace Acelle\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportSubscribers extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;
    protected $mail_list;

    /**
     * Create a new job instance.
     */
    public function __construct(\Acelle\Model\User $user, \Acelle\Model\MailList $mail_list)
    {
        $this->user = $user;
        $this->mail_list = $mail_list;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        \Acelle\Model\Subscriber::import($this->user, $this->mail_list);
    }
}
