<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeolocationLog extends Model
{
    use HasFactory;
    protected $table = 'geolocation_logs';

    protected $fillable = [
        'ip_address',
        'country',
        'region',
        'city',
        'latitude',
        'longitude',
    ];
}
