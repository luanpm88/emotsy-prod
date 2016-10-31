<?php

namespace Acelle\Providers;

use Illuminate\Support\ServiceProvider;

class JobServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // mark the SystemJob record as RUNNING
        \Queue::before(function (\Illuminate\Queue\Events\JobProcessing $event) {
            $job = $this->getJobObject($event);
            $job->setStarted();
        });
        
        // mark the SystemJob record as DONE
        \Queue::after(function (\Illuminate\Queue\Events\JobProcessed $event) {
            $job = $this->getJobObject($event);
            $job->setDone();
        });
        
        // mark the SystemJob record as FAILED
        \Queue::failing(function (\Illuminate\Queue\Events\JobFailed $event) {
            $job = $this->getJobObject($event);
            $job->setFailed();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    private function getJobObject($event) {
        return unserialize($event->data['data']['command']);
    }
}
