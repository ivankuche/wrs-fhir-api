<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Immunization extends Model
{
    use HasFactory;

    protected $fillable= ['identifier', 'instantiatesCanonical', 'instantiatesUri', 'basedOn', 'status', 'statusReason',
        'vaccineCode', 'manufacturer', 'lotNumber', 'expirationDate', 'patient', 'encounter', 'occurrenceDateTime',
        'occurrenceString', 'recorded', 'primarySource', 'informationSource', 'location', 'site', 'route', 'doseQuantity',
        'performer', 'note', 'reason', 'isSubpotent', 'subpotentReason', 'education', 'programEligibility', 'fundingSource',
        'reaction', 'protocolApplied'


];

protected $casts = [
    'identifier' => 'array',
    'instantiatesCanonical' => 'array',
    'basedOn' => 'array',
    'status' => 'array',
    'statusReason' => 'array',
    'vaccineCode' => 'array',
    'manufacturer' => 'array',
    'patient' => 'array',
    'encounter' => 'array',
    'informationSource' => 'array',
    'location' => 'array',
    'site' => 'array',
    'route' => 'array',
    'doseQuantity' => 'array',
    'performer' => 'array',
    'note' => 'array',
    'reason' => 'array',
    'subpotentReason' => 'array',
    'education' => 'array',
    'programEligibility' => 'array',
    'fundingSource' => 'array',
    'reaction' => 'array',
    'protocolApplied' => 'array',
];
}
