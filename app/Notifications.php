<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    //							
    protected $fillable = [
        'icon', 'title', 'message', 'staff', 'status', 'link', 'bg', 'uniqid', 
    ];
}
