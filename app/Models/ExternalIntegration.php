<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalIntegration extends Model
{
    protected $fillable = [
        'external_source',
        'external_type',
        'external_id',
        'internal_order_id',
    ];
}