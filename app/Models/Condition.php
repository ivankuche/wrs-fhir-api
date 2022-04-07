<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    protected $fillable= ['identifier', 'clinicalStatus', 'verificationStatus', 'category', 'severity', 'code',
        'bodySite', 'subject', 'encounter', 'onsetDateTime', 'onsetAge', 'onsetPeriod', 'onsetRange', 'onsetString',
        'abatementDateTime', 'abatementAge',  'abatementPeriod', 'abatementRange', 'abatementString', 'recordedDate',
        'recorder', 'asserter', 'stage', 'evidence', 'note'
    ];


    protected $casts = [
        'identifier' => 'array',
        'clinicalStatus' => 'array',
        'verificationStatus' => 'array',
        'category' => 'array',
        'severity' => 'array',
        'code' => 'array',
        'bodySite' => 'array',
        'encounter' => 'array',
        'onsetPeriod' => 'array',
        'onsetRange' => 'array',
        'abatementPeriod' => 'array',
        'abatementRange' => 'array',
        'recorder' => 'array',
        'asserter' => 'array',
        'stage' => 'array',
        'evidence' => 'array',
        'note' => 'array',
    ];
}
