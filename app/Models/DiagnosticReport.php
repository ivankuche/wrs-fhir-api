<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosticReport extends Model
{
    use HasFactory;

    protected $fillable= ['identifier', 'basedOn', 'status', 'category', 'code', 'subject', 'encounter',
        'effectiveDateTime', 'effectivePeriod', 'issued', 'performer', 'resultsInterpreter', 'specimen', 'result',
        'note', 'imagingStudy', 'media', 'composition', 'conclusion', 'conclusionCode', 'presentedForm'

    ];

    protected $casts = [
        'identifier' => 'array',
        'basedOn' => 'array',
        'category' => 'array',
        'code' => 'array',
        'subject' => 'array',
        'encounter' => 'array',
        'effectivePeriod' => 'array',
        'performer' => 'array',
        'resultsInterpreter' => 'array',
        'specimen' => 'array',
        'result' => 'array',
        'note' => 'array',
        'imagingStudy' => 'array',
        'media' => 'array',
        'composition' => 'array',
        'conclusionCode' => 'array',
        'presentedForm' => 'array',
    ];
}
