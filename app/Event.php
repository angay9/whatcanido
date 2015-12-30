<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    const CREATED_BY_USER = 1;
    const CREATED_BY_OTHERS = 2;
    const ALL_EVENTS = 3;

    protected $table = 'events';
    
    protected $fillable = ['title', 'desc', 'lat', 'lng', 'creator_id', 'starts_at'];

    public function isCreator(User $user)
    {
        return (int) $this->creator->id === (int)$user->id;
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }

    public function participants()
    {
        return $this->belongsToMany('App\User', 'events_users');
    }
}
