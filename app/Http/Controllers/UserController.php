<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Repositories\EventsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Behaviours\PaginatesEvents;

class UserController extends Controller
{
    use PaginatesEvents;

    private $eventsRepo;

    public function __construct(EventsRepository $eventsRepo)
    {
        $this->eventsRepo = $eventsRepo;
    }

    /**
     * List all created by user events
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function events(Request $request)
    {
        $paginator = $this->paginateEvents($this->eventsRepo, 20);

        if (request()->ajax()) {
            return $this->respondSuccess(['paginator' => $paginator]);
        }

        return view('user.events', compact('paginator'));
    }
}
