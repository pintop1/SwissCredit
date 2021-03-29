<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FilledChecklist extends Model
{
    protected $fillable = [
        'checklist', 'value', 'form', 'staff',
    ];
}
