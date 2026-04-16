<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anomaly extends Model
{
    protected $table = 'anomalies';
    protected $primaryKey = 'anomaly_id';

    protected $fillable = [
        'scan_session_id',
        'dock_id',
        'tag_id',
        'anomaly_type',
        'status',
        'assigned_product_id',
        'batch_number',
        'quantity',
        'notes',
        'resolved_by',
        'detected_at',
        'resolved_at',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    // Relación con la sesión de escaneo
    public function scanSession()
    {
        return $this->belongsTo(
            OrderScanSession::class,
            'scan_session_id',
            'scan_session_id'
        );
    }

    // Relación con el dock
    public function dock()
    {
        return $this->belongsTo(
            SupplierAppointmentSlotDock::class,
            'dock_id'
        );
    }

    // Usuario que resolvió la anomalía
    public function resolver()
    {
        return $this->belongsTo(
            User::class,
            'resolved_by'
        );
    }
}