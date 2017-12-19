<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventsControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(App\User::class)->create();
        $this->be($this->user);
    }

    /** @test */
    public function it_paginates_events()
    {
        $this->get('/events')
            ->assertResponseStatus(200)
        ;
    }

    /** @test */
    public function it_can_store_event()
    {
        $event = factory(App\Event::class)->create();


        $this->post('/events', [
                'title' =>  'Title',
                'description'   =>  'Description',
                'latitude'   =>  $event->lat,
                'longitude'   =>  $event->lng,
                'starts_at' =>  date('Y-m-d H:i:s', strtotime('tomorrow')),
                'place' =>  'London, UK',
            ])
            ->assertResponseStatus(201)
        ;
        
        $this->seeInDatabase('events', [
            'id'    =>  $event->id
        ]);
    }

    /** @test */
    public function user_can_paritipate_in_event()
    {
        $event = factory(App\Event::class)->create();

        $this->post('/events/participate', [
                'event_id'  =>  $event->id
            ])
            ->assertResponseStatus(201)
        ;
        $this->seeInDatabase('events_users', [
            'event_id'    =>  $event->id,
            'user_id'   =>  $this->user->id
        ]);
    }

    /** @test */
    public function user_can_leave_event()
    {
        $event = factory(App\Event::class)->create();

        // Participate
        $this->post('/events/participate', [
                'event_id'  =>  $event->id
            ])
            ->assertResponseStatus(201)
        ;
        $this->seeInDatabase('events_users', [
            'event_id'    =>  $event->id,
            'user_id'   =>  $this->user->id
        ]);

        // Leave
        $this->delete('/events/leave', [
                'event_id'  =>  $event->id
            ])
            ->assertResponseStatus(200)
        ;
        $this->dontSeeInDatabase('events_users', [
            'event_id'    =>  $event->id,
            'user_id'   =>  $this->user->id
        ]);
    }
}