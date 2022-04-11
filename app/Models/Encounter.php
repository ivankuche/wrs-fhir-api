<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encounter extends Model
{
    use HasFactory;

    protected $fillable= ['identifier', 'status', 'statusHistory', 'class', 'classHistory', 'type', 'serviceType',
        'priority', 'subject', 'episodeOfCare', 'basedOn', 'participant', 'appointment', 'period', 'length', 'reasonCode',
        'reasonReference', 'diagnosis', 'account', 'hospitalization', 'location', 'serviceProvider', 'partOf'
    ];

    protected $casts = [
        'identifier' => 'array',
        'statusHistory' => 'array',
        'class' => 'array',
        'classHistory' => 'array',
        'type' => 'array',
        'serviceType' => 'array',
        'priority' => 'array',
        'subject' => 'array',
        'episodeOfCare' => 'array',
        'basedOn' => 'array',
        'participant' => 'array',
        'appointment' => 'array',
        'period' => 'array',
        'length' => 'array',
        'reasonCode' => 'array',
        'reasonReference' => 'array',
        'diagnosis' => 'array',
        'account' => 'array',
        'hospitalization' => 'array',
        'location' => 'array',
        'serviceProvider' => 'array',
        'partOf' => 'array',
    ];

}
