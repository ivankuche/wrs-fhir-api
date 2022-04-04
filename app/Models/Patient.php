<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable= ['identifier','active', 'name', 'telecom', 'gender', 'birthdate',
    'deceasedBoolean', 'deceasedDateTime', 'address', 'maritalStatus', 'contact'];

    protected $casts = [
        //'identifier' => 'array',
        'name' => 'array',
        'telecom' => 'array',
        'address' => 'array',
        'maritalStatus' => 'array',
        'contact' => 'array'
    ];
}
