<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Repositories\EventsRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
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
        $orderBy = explode('.', request()->input('orderBy', 'starts_at.desc'));

        $paginator = $this
            ->eventsRepo
            ->paginate(20, Event::CREATED_BY_USER, ['participants', 'creator'], [$orderBy[0] => $orderBy[1]])
            ->toArray()
        ;

        if (request()->ajax()) {
            return $this->respondSuccess(['paginator' => $paginator]);
        }

        return view('user.events', compact('paginator'));
    }
}
