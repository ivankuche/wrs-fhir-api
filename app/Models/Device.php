<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;







    protected $fillable= ['identifier', 'status', 'type', 'lotNumber', 'manufacturer', 'manufactureDate',
        'expirationDate',' model', 'version', 'patient', 'owner', 'contact', 'location', 'url', 'note', 'safety',
        'distinctIdentifier', 'serialNumber', 'udiCarrier'
    ];


    protected $casts = [
        'identifier' => 'array',
        'type' => 'array',
        'patient' => 'array',
        'owner' => 'array',
        'contact' => 'array',
        'location' => 'array',
        'note' => 'array',
        'safety' => 'array',
        'udiCarrier' => 'array',
    ];

}
