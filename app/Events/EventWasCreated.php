<?php

namespace App\Events;

use App\Event as EventEntity;
use App\Events\Event as BaseEvent;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EventWasCreated extends BaseEvent implements ShouldBroadcast
{
    use SerializesModels;

    public $event;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(EventEntity $event)
    {
        $this->event = $event;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['whatcanido-channel'];
    }
}
