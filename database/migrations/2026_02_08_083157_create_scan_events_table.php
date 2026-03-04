<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('scan_events', function (Blueprint $table) {

        $table->bigIncrements('scan_events_id');

        // 🔥 Relación con sesión de escaneo
        $table->uuid('scan_session_id')->nullable();

        $table->unsignedBigInteger('dock_id'); // eje principal

        $table->unsignedBigInteger('order_id')->nullable();
        $table->string('product_id', 50)->nullable(); 
     
        $table->string('event_status', 50);
        $table->timestamp('scanned_at')->useCurrent();

        // 🔥 Foreign keys
        $table->foreign('scan_session_id')
            ->references('scan_session_id')
            ->on('order_scan_sessions')
            ->onDelete('cascade');

        $table->foreign('order_id')
            ->references('order_id')
            ->on('orders')
            ->nullOnDelete();

        $table->foreign('product_id')
            ->references('product_id')
            ->on('products')
            ->nullOnDelete();

        // 🔥 Índices
        $table->index('scan_session_id');
        $table->index('dock_id');
        $table->index(['dock_id', 'event_status']);
        $table->index('product_id');
        $table->index('order_id');
    });
}

public function down(): void
{
    Schema::dropIfExists('scan_events');
}
};
