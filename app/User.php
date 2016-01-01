<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'address'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function createdEvents()
    {
        return $this->hasMany('App\Event', 'creator_id', 'id');
    }

    public function settings()
    {
        return $this->hasOne('App\Settings', 'user_id', 'id');
    }
}
