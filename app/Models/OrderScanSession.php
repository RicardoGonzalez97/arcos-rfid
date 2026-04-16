<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderScanSession extends Model
{
    use HasFactory;

    protected $table = 'order_scan_sessions';

    // La clave primaria es un UUID
    protected $primaryKey = 'scan_session_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'scan_session_id',
        'dock_id',
        'status',
        'closed_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    /**
     * Relación con el dock (gate)
     */
    public function dock()
    {
        return $this->belongsTo(
            SupplierAppointmentSlotDock::class,
            'dock_id'
        );
    }

    /**
     * Relación con las anomalías
     */
    public function anomalies()
    {
        return $this->hasMany(
            Anomaly::class,
            'scan_session_id',
            'scan_session_id'
        );
    }
}