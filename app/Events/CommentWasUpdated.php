<?php

namespace App\Events;

use App\Comment;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentWasUpdated extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $comment;

    /**
     * Create a new comment instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
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
        $this->comment->user; // This patch allowes to send participants along with the event object 

        return ['comment' => $this->comment];
    }
}
