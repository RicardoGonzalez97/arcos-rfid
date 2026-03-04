<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false; 
    // 👆 Solo tienes created_at, no updated_at

    protected $fillable = [
        'product_id',
        'name',
        'provider',
        'code',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'product_id', 'product_id');
    }
}