<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;


    protected $fillable= ['identifier', 'code', 'status', 'manufacturer', 'form', 'amount', 'ingredient', 'batch'
    ];

    protected $casts = [
        'identifier' => 'array',
        'code' => 'array',
        'manufacturer' => 'array',
        'form' => 'array',
        'amount' => 'array',
        'ingredient' => 'array',
        'batch' => 'array',
    ];
}
