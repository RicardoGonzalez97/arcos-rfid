<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'supplier_id',
        'estado_orden',
        'fecha',
        'proveedor',
        'numero_proveedor',
        'fecha_inicial',
        'fecha_entrega',
        'fecha_envio',
        'folio_periodo',
        'tipo',
        'solicitante',
        'no_determinante',
        'no_ot',
        'nombre_determinante',
        'direccion_entrega',
        'ciudad',
        'estado',
        'formato_negocio',
        'facturar_a',
        'comentarios',
        'subtotal',
        'iva',
        'costo_maniobras',
        'total',
        'aceptado_por',
        'fecha_aceptacion',
        'codigo_validacion',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relación con Items
    |--------------------------------------------------------------------------
    */

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function docks()
    {
        return $this->belongsToMany(
            SupplierAppointmentSlotDock::class,
            'dock_purchase_orders',
            'purchase_order_id',
            'dock_id'
        );
    }
}