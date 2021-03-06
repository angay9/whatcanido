<?php

namespace App\Events;

use App\Event as EventEntity;
use App\Events\Event as BaseEvent;
use App\User;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class UserParticipatedInEvent extends BaseEvent implements ShouldBroadcast
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
    public function __construct(EventEntity $event, User $user)
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
        // This patch allowes to send participants along with the event object 
        $this->event->participants; 
        
        // This patch allowes to send creator along with the event object 
        $this->event->creator; 

        return ['event' => $this->event, 'user' => $this->user];
    }
    
}
