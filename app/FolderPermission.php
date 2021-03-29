<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FolderPermission extends Model
{
    protected $fillable = [
        'staff', 'permission', 'folder', 'director', 'internal_control', 'status',
    ];
}
