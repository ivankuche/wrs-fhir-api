<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable= ['identifier', 'active', 'type', 'name', 'alias', 'telecom', 'address', 'partOf', 'contact', 'endpoint'
    ];

    protected $casts = [
        'identifier' => 'array',
        'type' => 'array',
        'telecom' => 'array',
        'address' => 'array',
        'partOf' => 'array',
        'contact' => 'array',
        'endpoint' => 'array',
    ];

}
