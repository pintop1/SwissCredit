<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{

    protected $fillable = [
        'file', 'name', 'staff', 'folder', 'status',
    ];

}