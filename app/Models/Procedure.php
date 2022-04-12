<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    use HasFactory;

    protected $fillable= ['identifier', 'instantiatesCanonical', 'instantiatesUri', 'basedOn', 'partOf', 'status',
        'statusReason', 'category', 'code', 'subject', 'encounter', 'performedDateTime', 'performedPeriod',
        'performedString', 'performedAge', 'performedRange', 'recorder', 'asserter', 'performer',  'location',
        'reasonCode', 'reasonReference', 'bodySite', 'outcome', 'report', 'complication', 'complicationDetail',
        'followUp', 'note', 'focalDevice', 'usedReference', 'usedCode'
    ];

    protected $casts = [
        'identifier' => 'array',
        'instantiatesCanonical' => 'array',
        'instantiatesUri' => 'array',
        'basedOn' => 'array',
        'partOf' => 'array',
        'statusReason' => 'array',
        'category' => 'array',
        'code' => 'array',
        'subject' => 'array',
        'encounter' => 'array',
        'performedPeriod' => 'array',
        'performedAge' => 'array',
        'performedRange' => 'array',
        'recorder' => 'array',
        'asserter' => 'array',
        'performer' => 'array',
        'location' => 'array',
        'reasonCode' => 'array',
        'reasonReference' => 'array',
        'bodySite' => 'array',
        'outcome' => 'array',
        'report' => 'array',
        'complication' => 'array',
        'complicationDetail' => 'array',
        'followUp' => 'array',
        'note' => 'array',
        'focalDevice' => 'array',
        'usedReference' => 'array',
        'usedCode' => 'array',
    ];
}
