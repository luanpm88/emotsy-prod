<?php

namespace Acelle\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessing;
use Acelle\Library\Log as MailLog;
use Queue;

class JobServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Initialize the MailLog which writes logs to mail.log
        $this->initMailLog();

        Queue::before(function (JobProcessing $event) {
            //
        });

        Queue::after(function (JobProcessed $event) {
            //
        });

        Queue::failing(function (JobFailed $event) {
            //
        });
    }
}
