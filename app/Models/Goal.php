<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable= ['identifier', 'lifecycleStatus', 'achievementStatus', 'category', 'continuous', 'priority',
        'description', 'subject', 'startDate', 'startCodeableConcept', 'target', 'statusDate', 'statusReason',
        'source', 'addresses', 'note', 'outcome'
    ];

    protected $casts = [
        'identifier' => 'array',
        'achievementStatus' => 'array',
        'category' => 'array',
        'priority' => 'array',
        'description' => 'array',
        'subject' => 'array',
        'startCodeableConcept' => 'array',
        'target' => 'array',
        'source' => 'array',
        'addresses' => 'array',
        'note' => 'array',
        'outcome' => 'array',
    ];
}
