<?php

namespace App\Http\Controllers;

use DB;
use App\Event;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Events\UserLeftEvent;
use App\Events\EventWasCreated;
use App\Http\Controllers\Controller;
use App\Repositories\EventsRepository;
use App\Events\UserParticipatedInEvent;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Controllers\Behaviours\PaginatesEvents;

class EventsController extends Controller
{
    use PaginatesEvents;

    /**
     * Events repository
     * 
     * @var App\Repositories\EventsRepository
     */
    private $repo;

    public function __construct(EventsRepository $repo)
    {
        $this->middleware('auth');
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = $this->paginateEvents($this->repo, Event::CREATED_BY_OTHERS, 20);

        if (request()->ajax()) {
            return $this->respondSuccess(['paginator' => $paginator]);
        }

        return view('events.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Requests\StoreEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
        $requestData = request()->only(['title', 'description', 'latitude', 'longitude', 'starts_at', 'place']);

        $event = $this->repo->create([
            'title' =>  $requestData['title'],
            'desc'   =>  $requestData['description'],
            'lat'   =>  $requestData['latitude'],
            'lng'   =>  $requestData['longitude'],
            'starts_at'   =>  $requestData['starts_at'],
            'place'   =>  $requestData['place'],
            'creator_id'    =>  auth()->user()->id
        ]);

        event(new EventWasCreated($event));

        return $this->respondCreated();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::with(['comments.user', 'participants', 'creator'])->findOrFail($id);
        
        return view('events.show', ['event' => $event]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::with(['comments', 'participants', 'creator'])->findOrFail($id);
        if (!$event->isCreator(auth()->user())) {
            return $this->respondUnauthorized();
        }

        return view('events.edit', ['event' => $event]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Requests\UpdateEventRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, $id)
    {
        $requestData = request()->only(['title', 'description', 'latitude', 'longitude', 'starts_at', 'place']);
        $event = $this->repo->update($id, [
            'title' =>  $requestData['title'],
            'desc'   =>  $requestData['description'],
            'lat'   =>  $requestData['latitude'],
            'lng'   =>  $requestData['longitude'],
            'starts_at' =>  $requestData['starts_at'],
            'place' =>  $requestData['place'],
        ]);

        return $this->respondSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Enroll user in an event
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function participate(Request $request)
    {
        $eventId = request()->input('event_id');

        $event = $this->repo->participate($eventId, auth()->user()->id, ['participants']);

        event(new UserParticipatedInEvent($event, auth()->user()));

        return $this->respondCreated();
    }

    /**
     * Leave an event
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function leave(Request $request)
    {
        $eventId = request()->input('event_id');
        
        $event = $this->repo->leave($eventId, auth()->user()->id, ['participants']);

        event(new UserLeftEvent($event, auth()->user()));
        
        return $this->respondSuccess();
    }
}
