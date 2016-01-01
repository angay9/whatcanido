<?php

namespace App\Events;

use App\Comment;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentWasDeleted extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $id;

    /**
     * Create a new comment instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
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
