<?php

namespace App\Events;

use App\Event;
use App\Events\Event as BaseEvent;
use App\User;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class UserLeftEvent extends BaseEvent implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * Event
     * 
     * @var \App\Event
     */
    public $event;

    /**
     * User
     * 
     * @var \App\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Event $event, User $user)
    {
        $this->event = $event;
        $this->user = $user;
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

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $this->event->participants; // This patch allowes to send participants along with the event object 
        $this->event->creator; // This patch allowes to send creator along with the event object 

        return ['event' => $this->event, 'user' => $this->user];
    }

}
