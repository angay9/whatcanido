<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public static $fields = ['email_notifications'];
    
    public $timestamps = false;

    protected $table = 'settings';
    
    protected $fillable = ['user_id', 'email_notifications'];
    
}
