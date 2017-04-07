<?php

namespace Acelle\Listeners;

use Acelle\Events\UserUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserUpdatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserUpdated  $event
     * @return void
     */
    public function handle(UserUpdated $event)
    {
        if ($event->delayed) {
            dispatch(new \Acelle\Jobs\UpdateUserJob($event->customer));
        } else {
            $event->user->customer->updateCache();
        }
    }
}
