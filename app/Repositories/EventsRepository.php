<?php namespace App\Repositories;

use App\Event;

class EventsRepository {

    protected $model = Event::class;
    
    public function participate($eventId, $userId, array $with = [])
    {
        $event = Event::with($with)->findOrFail($eventId);
        $event->participants()->attach($userId);

        return $event;
    }

    public function leave($eventId, $userId, array $with = [])
    {
        $event = Event::with($with)->findOrFail($eventId);
        $event->participants()->detach($userId);

        return $event;
    }

    public function create(array $data)
    {
        return Event::create($data);
    }

    public function update($id, array $data)
    {
        $event = Event::findOrFail($id);
        return $event->update($data);
    }

    public function paginate($perPage = 20, $which = Event::ALL_EVENTS, array $with = [], array $orderBy = ['starts_at' => 'DESC'])
    {
        $query = Event::with($with);

        if ($which == Event::CREATED_BY_USER) {
            $query->where('creator_id', '=', auth()->user()->id);
        }

        if ($which == Event::CREATED_BY_OTHERS) {
            $query->where('creator_id', '!=', auth()->user()->id);
        }

        if ($orderBy) {
            reset($orderBy);
            $field = key($orderBy);
            $query->orderBy($field, $orderBy[$field]);
        }

        return $query->paginate($perPage);
    }

}