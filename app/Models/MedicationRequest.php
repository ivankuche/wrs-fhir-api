<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationRequest extends Model
{
    use HasFactory;

    protected $fillable= ['identifier', 'status', 'statusReason', 'intent', 'category', 'priority', 'doNotPerform',
        'reportedBoolean', 'reportedReference', 'medicationCodeableConcept', 'medicationReference', 'subject', 'encounter',
        'supportingInformation', 'authoredOn', 'requester', 'performer', 'performerType', 'recorder', 'reasonCode',
        'reasonReference', 'instantiatesCanonical', 'instantiatesUri', 'basedOn', 'groupIdentifier', 'courseOfTherapyType',
        'insurance', 'note', 'dose', 'dosageInstruction', 'dispenseRequest', 'substitution', 'priorPrescription', 'detectedIssue',
        'eventHistory'
    ];

    protected $casts = [
        'identifier' => 'array',
        'statusReason' => 'array',
        'category' => 'array',
        'reportedReference' => 'array',
        'medicationCodeableConcept' => 'array',
        'medicationReference' => 'array',
        'subject' => 'array',
        'encounter' => 'array',
        'supportingInformation' => 'array',
        'requester' => 'array',
        'performer' => 'array',
        'performerType' => 'array',
        'recorder' => 'array',
        'reasonCode' => 'array',
        'reasonReference' => 'array',
        'basedOn' => 'array',
        'groupIdentifier' => 'array',
        'courseOfTherapyType' => 'array',
        'insurance' => 'array',
        'note' => 'array',
        'dose' => 'array',
        'dosageInstruction' => 'array',
        'dispenseRequest' => 'array',
        'substitution' => 'array',
        'priorPrescription' => 'array',
        'detectedIssue' => 'array',
        'eventHistory' => 'array',
    ];
}
