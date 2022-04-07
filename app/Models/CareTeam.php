<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareTeam extends Model
{
    use HasFactory;

    protected $fillable= ['identifier', 'status', 'category', 'name', 'subject', 'encounter', 'period', 'participant',
        'reasonCode', 'reasonReference', 'managingOrganization', 'telecom', 'note'];


    protected $casts = [
        'identifier' => 'array',
        'category' => 'array',
        'subject' => 'array',
        'encounter' => 'array',
        'period' => 'array',
        'participant'=> 'array',
        'reasonCode'=> 'array',
        'reasonReference'=> 'array',
        'managingOrganization'=> 'array',
        'telecom'=> 'array',
        'note'=> 'array',
    ];

}
