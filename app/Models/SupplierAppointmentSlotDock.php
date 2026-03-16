<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierAppointmentSlotDock extends Model
{
    protected $fillable = [
        'number',
        'name',
        'is_active'
    ];

    public function purchaseOrders()
    {
        return $this->belongsToMany(
            PurchaseOrder::class,
            'dock_purchase_orders',
            'dock_id',
            'purchase_order_id'
        );
    }
}
