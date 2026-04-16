<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('purchase_orders', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('company_id')->nullable();
    $table->unsignedBigInteger('supplier_id')->nullable();

    $table->string('estado_orden')->nullable();
    $table->date('fecha')->nullable();
    $table->date('fecha_inicial')->nullable();
    $table->date('fecha_entrega')->nullable();
    $table->date('fecha_envio')->nullable();

    $table->string('proveedor');
    $table->string('numero_proveedor');

    $table->string('folio_periodo')->nullable();
    $table->string('tipo')->nullable();
    $table->string('solicitante')->nullable();
    $table->string('no_determinante')->nullable();
    $table->string('no_ot')->nullable();
    $table->string('nombre_determinante')->nullable();

    $table->text('direccion_entrega')->nullable();
    $table->string('ciudad')->nullable();
    $table->string('estado')->nullable();

    $table->string('formato_negocio')->nullable();
    $table->string('facturar_a')->nullable();
    $table->text('comentarios')->nullable();

    $table->decimal('subtotal', 12, 2)->default(0);
    $table->decimal('iva', 12, 2)->default(0);
    $table->decimal('costo_maniobras', 12, 2)->default(0);
    $table->decimal('total', 12, 2)->default(0);

    $table->string('aceptado_por')->nullable();
    $table->date('fecha_aceptacion')->nullable();
    $table->string('codigo_validacion')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};

