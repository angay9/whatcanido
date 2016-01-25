<?php

use App\Event;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function user_can_list_created_events()
    {
        $user = factory(User::class)->create();
        $events = factory(Event::class, 5)->create();

        $user->createdEvents()->saveMany($events);

        $this->assertCount(5, $user->createdEvents);
    }

}
