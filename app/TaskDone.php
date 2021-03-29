<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskDone extends Model
{
    protected $fillable = [
        'user', 'task', 
    ];
}
