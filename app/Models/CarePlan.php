<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarePlan extends Model
{
    use HasFactory;

    protected $fillable= ['identifier','instantiatesCanonical', 'instantiatesUri', 'basedOn', 'replaces', 'partOf',
        'status', 'intent', 'category', 'title', 'description', 'subject', 'encounter', 'period', 'created',
        'author', 'contributor', 'careTeam', 'addresses', 'supportingInfo', 'goal', 'activity', 'note'
    ];

    protected $casts = [
        'identifier' => 'array',
        'instantiatesCanonical' => 'array',
        'instantiatesUri'=> 'array',
        'basedOn' => 'array',
        'replaces' => 'array',
        'category' => 'array',
        'subject' => 'array',
        'encounter' => 'array',
        'period' => 'array',
        'author' => 'array',
        'contributor' => 'array',
        'careTeam' => 'array',
        'addresses' => 'array',
        'supportingInfo' => 'array',
        'goal' => 'array',
        'activity' => 'array',
        'note' => 'array',
    ];

}
