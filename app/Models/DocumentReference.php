<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentReference extends Model
{
    use HasFactory;


    protected $fillable= ['identifier', 'basedOn', 'status', 'docStatus', 'type', 'category', 'subject', 'encounter',
        'event', 'facilityType', 'practiceSetting', 'period', 'date', 'author', 'attester', 'custodian', 'relatesTo',
        'description', 'securityLabel', 'content', 'context', 'sourcePatientInfo', 'related'
    ];

    protected $casts = [
        'identifier' => 'array',
        'basedOn' => 'array',
        'type' => 'array',
        'category' => 'array',
        'subject' => 'array',
        'encounter' => 'array',
        'event' => 'array',
        'facilityType' => 'array',
        'practiceSetting' => 'array',
        'period' => 'array',
        'author' => 'array',
        'attester' => 'array',
        'custodian' => 'array',
        'relatesTo' => 'array',
        'securityLabel' => 'array',
        'content' => 'array',
        'context' => 'array',
        'sourcePatientInfo' => 'array',
        'related' => 'array',
    ];
}
