<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provenance extends Model
{
    use HasFactory;

    protected $fillable= ['target','occurredPeriod', 'occurredDateTime', 'recorded', 'policy', 'location', 'authorization',
    'activity', 'basedOn', 'patient', 'encounter', 'agent', 'entity', 'signature'];

    protected $casts = [
        'target' => 'array',
        'occurredPeriod' => 'array',
        'location' => 'array',
        'authorization' => 'array',
        'activity' => 'array',
        'basedOn' => 'array',
        'patient' => 'array',
        'encounter' => 'array',
        'agent' => 'array',
        'entity' => 'array',
        'signature' => 'array',
    ];
}
