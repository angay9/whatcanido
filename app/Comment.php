<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    
    protected $fillable = ['comment', 'user_id', 'event_id'];
    
    public function event()
    {
        return $this->belongsTo('App\Event', 'event_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function isOwner(User $user)
    {
        return $user->id == $this->user->id;
    }
}
