<?php

namespace Acelle\Events;

use Acelle\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MailListUpdated extends Event
{
    use SerializesModels;
    
    public $list;
    public $delayed;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($list, $delayed = true)
    {
        $this->list = $list;
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
