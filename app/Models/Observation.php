<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    use HasFactory;

    protected $fillable= ['identifier', 'basedOn', 'partOf', 'status', 'category', 'code', 'subject', 'focus', 'encounter',
        'effectiveDateTime', 'effectivePeriod', 'effectiveTiming', 'effectiveInstant', 'issued', 'performer', 'valueQuantity',
        'valueCodeableConcept', 'valueString', 'valueBoolean', 'valueInteger', 'valueRange', 'valueRatio', 'valueSampledData',
        'valueTime', 'valueDateTime', 'valuePeriod', 'dataAbsentReason', 'interpretation', 'note', 'bodySite', 'method',
        'specimen', 'device', 'referenceRange', 'hasMember', 'derivedFrom', 'component'
    ];

    protected $casts = [
        'identifier' => 'array',
        'basedOn' => 'array',
        'partOf' => 'array',
        'category' => 'array',
        'code' => 'array',
        'subject' => 'array',
        'focus' => 'array',
        'encounter' => 'array',
        'effectivePeriod' => 'array',
        'effectiveTiming' => 'array',
        'performer' => 'array',
        'valueQuantity' => 'array',
        'valueCodeableConcept' => 'array',
        'valueRange' => 'array',
        'valueRatio' => 'array',
        'valueSampledData' => 'array',
        'valuePeriod' => 'array',
        'dataAbsentReason' => 'array',
        'interpretation' => 'array',
        'note' => 'array',
        'bodySite' => 'array',
        'method' => 'array',
        'specimen' => 'array',
        'device' => 'array',
        'referenceRange' => 'array',
        'hasMember' => 'array',
        'derivedFrom' => 'array',
        'component' => 'array',
    ];
}
