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
        Schema::create('dock_purchase_orders', function (Blueprint $table) {

            $table->id();

            $table->foreignId('dock_id')
                ->constrained('supplier_appointment_slot_docks')
                ->cascadeOnDelete();

            $table->foreignId('purchase_order_id')
                ->constrained('purchase_orders')
                ->cascadeOnDelete();

            $table->timestamps();

            // evita duplicados dock + order
            $table->unique(['dock_id', 'purchase_order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dock_purchase_orders');
    }
};