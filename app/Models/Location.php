<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;


    protected $fillable= ['identifier', 'status', 'operationalStatus', 'name', 'alias', 'description', 'mode',
        'type', 'telecom', 'address', 'physicalType', 'position', 'managingOrganization', 'partOf',  'hoursOfOperation',
        'availabilityExceptions', 'endpoint'
    ];

    protected $casts = [
        'identifier' => 'array',
        'operationalStatus' => 'array',
        'mode' => 'array',
        'type' => 'array',
        'telecom' => 'array',
        'address' => 'array',
        'physicalType' => 'array',
        'position' => 'array',
        'managingOrganization' => 'array',
        'partOf' => 'array',
        'hoursOfOperation' => 'array',
        'availabilityExceptions' => 'array',
        'endpoint' => 'array',
    ];
}
