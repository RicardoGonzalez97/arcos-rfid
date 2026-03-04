<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false; 
    // 👆 solo tienes created_at

    protected $fillable = [
        'location',
        'type',
        'truck_id',
        'dock_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'order_id');
    }
}