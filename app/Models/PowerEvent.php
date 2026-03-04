<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PowerEvent extends Model
{
    protected $fillable = [
        'power_status',
        'reported_at',
        'notes',
    ];
}