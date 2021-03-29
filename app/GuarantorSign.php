<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuarantorSign extends Model
{
					
    protected $fillable = [
        'form_id', 'sign_1', 'sign_2', 'default', 
    ];
}
