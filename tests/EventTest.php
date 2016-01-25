<?php

use App\Event;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class EventTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function event_can_check_its_owner()
    {
        $event = factory(Event::class)->create();
        $owner = factory(User::class)->create();
        $notOwner = factory(User::class)->create();

        $event->creator()->associate($owner);
        $event->save();

        $this->assertTrue($event->isCreator($owner));
        $this->assertFalse($event->isCreator($notOwner));
    }
}
