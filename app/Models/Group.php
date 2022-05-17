<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable= ['identifier', 'active', 'type', 'actual', 'code', 'name', 'description', 'quantity',
        'managingEntity', 'characteristic', 'member'];


    protected $casts = [
        'identifier' => 'array',
        'characteristic' => 'array',
        'member' => 'array',
    ];

}
