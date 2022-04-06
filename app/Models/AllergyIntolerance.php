<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllergyIntolerance extends Model
{
    use HasFactory;

    protected $fillable= ['identifier', 'clinicalStatus', 'verificationStatus','type',
        'category', 'criticality', 'code', 'patient', 'encounter', 'onsetDateTime', 'onsetAge',
        'onsetPeriod', 'onsetRange', 'onsetString', 'recordedDate', 'recorder', 'asserter', 'lastOccurrence',
        'note', 'reaction'
     ];

    protected $casts = [
        'identifier' => 'array',
        'clinicalStatus' => 'array',
        'code' => 'array',
        'patient' => 'array',
        'encounter' => 'array',
        'onsetPeriod' => 'array',
        'onsetRange' => 'array',
        'recorder' => 'array',
        'asserter' => 'array',
        'note' => 'array',
        'reaction' => 'array'
    ];
}
