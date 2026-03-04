<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $primaryKey = 'order_products_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true; 
    // 👆 Aquí sí tienes timestamps completos

    protected $fillable = [
        'order_id',
        'product_id',
        'expected_quantity',
        'received_quantity',
        'unit_price',
        'is_completed',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}