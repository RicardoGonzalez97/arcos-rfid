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
       Schema::create('purchase_order_items', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('purchase_order_id');
    $table->string('codigo_cliente')->nullable();
    $table->string('modelo')->nullable();
    $table->string('marca')->nullable();
    $table->date('fecha_entrega')->nullable();
    $table->text('descripcion')->nullable();

    $table->integer('cantidad');
    $table->decimal('precio_unitario', 12, 2);
    $table->decimal('subtotal', 12, 2);
    $table->decimal('total', 12, 2);

    $table->timestamps();

    $table->index('purchase_order_id');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
