<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    //				
    protected $fillable = [
        'signature', 'signature_two', 'inuse', 'offer',
    ];
}
