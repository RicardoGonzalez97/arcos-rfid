<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierAppointmentSlotDock extends Model
{
    protected $table = 'supplier_appointment_slot_docks';

    protected $fillable = [
        'number',
        'name',
        'is_active'
    ];

    /**
     * Relación con las órdenes de compra
     */
    public function purchaseOrders()
    {
        return $this->belongsToMany(
            PurchaseOrder::class,
            'dock_purchase_orders',
            'dock_id',
            'purchase_order_id'
        );
    }

    /**
     * ✅ Relación con las anomalías
     */
    public function anomalies()
    {
        return $this->hasMany(
            Anomaly::class,
            'dock_id'
        );
    }

    /**
     * ✅ Relación con las sesiones de escaneo
     */
    public function scanSessions()
    {
        return $this->hasMany(
            OrderScanSession::class,
            'dock_id'
        );
    }
}