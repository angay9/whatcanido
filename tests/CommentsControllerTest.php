<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_store_comment()
    {
        $event = factory(App\Event::class)->create();
        $user = factory(App\User::class)->create();

        $this->be($user);

        $this->post('/comments', [
            'comment'   =>  'Test comment',
            'event_id'  =>  $event->id
        ])
        ->assertResponseStatus(200);

        $this->seeInDatabase('comments', [
            'comment'   =>  'Test comment',
            'event_id'  =>  $event->id
        ]);
    }

    /** @test */
    public function it_can_update_comment()
    {
        $event = factory(App\Event::class)->create();
        $user = factory(App\User::class)->create();

        $comment = factory(App\Comment::class)->create([
            'comment'   =>  'Test comment',
            'event_id'  =>  $event->id,
            'user_id'   =>  $user->id
        ]);

        $this->be($user);
        
        $url = '/comments/' . $comment->id;
        $this
            ->patch($url, [
                'id'    =>  $comment->id,
                'comment' => 'Test comment updated',
                'event_id'  =>  $event->id
            ])
            ->assertResponseStatus(200)
        ;

        $this->seeInDatabase('comments', [
            'comment'   =>  'Test comment updated',
            'event_id'  =>  $event->id
        ]);

    }

    /** @test */
    public function it_can_delete_comment()
    {
        $event = factory(App\Event::class)->create();
        $user = factory(App\User::class)->create();

        $comment = factory(App\Comment::class)->create([
            'comment'   =>  'Test comment',
            'event_id'  =>  $event->id,
            'user_id'   =>  $user->id
        ]);

        $this->be($user);

        $this->seeInDatabase('comments', [
            'comment'   =>  'Test comment',
            'event_id'  =>  $event->id
        ]);

        $this->delete('/comments/' . $comment->id)
            ->assertResponseStatus(200)
        ;

        $this->dontSeeInDatabase('comments', [
            'comment'   =>  'Test comment',
            'event_id'  =>  $event->id
        ]);
    }
}
