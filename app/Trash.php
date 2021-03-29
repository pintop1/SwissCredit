<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trash extends Model
{
    protected $fillable = [
        'type', 'user', 'folder', 'the_id',
    ];
}
