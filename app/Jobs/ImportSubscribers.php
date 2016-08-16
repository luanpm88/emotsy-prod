<?php

namespace Mailixa\Jobs;

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
    public function __construct(\Mailixa\Model\User $user, \Mailixa\Model\MailList $mail_list)
    {
        $this->user = $user;
        $this->mail_list = $mail_list;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        \Mailixa\Model\Subscriber::import($this->user, $this->mail_list);
    }
}
