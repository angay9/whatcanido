<?php 

namespace App\Http\Controllers\Behaviours;

use App\Event;
use App\Repositories\EventsRepository;

trait PaginatesEvents {
    
    protected function paginateEvents(EventsRepository $eventsRepo, $whichOnes, $perPage = 20) 
    {
        $orderBy = explode('.', request()->input('orderBy', 'starts_at.desc'));
        $showOld = (bool)request()->input('showOld', false);
        $paginator = $eventsRepo
            ->paginate($perPage, $whichOnes, ['participants', 'creator'], [$orderBy[0] => $orderBy[1]], $showOld)
            ->toArray()
        ;

        return $paginator;
    }
}