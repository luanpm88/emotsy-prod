<?php

namespace Acelle\Events;

use Acelle\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserUpdated extends Event
{
    use SerializesModels;

    public $user;
    public $delayed;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $delayed = true)
    {
        $this->user = $user;
        $this->delayed = $delayed;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
