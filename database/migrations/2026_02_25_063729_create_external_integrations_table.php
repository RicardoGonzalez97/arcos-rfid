<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_integrations', function (Blueprint $table) {
            $table->id();

            // Fuente externa
            $table->string('external_source'); // ERP_A, SAP, SQLITE_DEMO
            $table->string('external_type');   // purchase_order, sales_order
            $table->string('external_id');     // ID en la base externa

            // ID interno normalizado
            $table->unsignedBigInteger('internal_order_id');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Índices importantes
            |--------------------------------------------------------------------------
            */

            // Evita duplicados por fuente + id externo
          $table->unique(['external_source', 'external_type', 'external_id'],'ext_src_type_id_unique');

            // Optimiza búsquedas internas
            $table->index('internal_order_id');

            /*
            |--------------------------------------------------------------------------
            | Foreign key
            |--------------------------------------------------------------------------
            */

            $table->foreign('internal_order_id')
                ->references('order_id')
                ->on('orders')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_integrations');
    }
};