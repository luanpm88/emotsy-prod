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
