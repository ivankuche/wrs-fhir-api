<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practitioner extends Model
{
    use HasFactory;

    protected $fillable= ['identifier','active', 'name', 'telecom', 'address', 'gender', 'birthDate', 'photo',
    'qualification','communication'];

    protected $casts = [
        'identifier' => 'array',
        'name' => 'array',
        'telecom' => 'array',
        'address' => 'array',
        'photo' => 'array',
        'qualification' => 'array',
        'communication' => 'array',
    ];
}
