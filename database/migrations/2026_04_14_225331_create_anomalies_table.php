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
        Schema::create('anomalies', function (Blueprint $table) {
            $table->id('anomaly_id');

            // Relación con la sesión de escaneo (UUID)
            $table->uuid('scan_session_id');

            // Relación con el dock (tabla correcta)
            $table->unsignedBigInteger('dock_id');

            // Información del tag RFID
            $table->string('tag_id');

            // Tipo de anomalía
            $table->enum('anomaly_type', [
                'unknown',
                'duplicate',
                'extra',
                'missing'
            ]);

            // Estado de la anomalía
            $table->enum('status', [
                'open',
                'investigating',
                'resolving',
                'resolved',
                'ignored'
            ])->default('open');

            // Información opcional para resolución
            $table->string('assigned_product_id')->nullable();
            $table->string('batch_number')->nullable();
            $table->integer('quantity')->default(1);
            $table->text('notes')->nullable();

            // Auditoría
            $table->unsignedBigInteger('resolved_by')->nullable();
            $table->timestamp('detected_at')->useCurrent();
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            // 🔗 Foreign Keys
            $table->foreign('scan_session_id')
                ->references('scan_session_id')
                ->on('order_scan_sessions')
                ->onDelete('cascade');

            // 🔥 Corrección aquí: referencia a la tabla correcta de docks
            $table->foreign('dock_id')
                ->references('id')
                ->on('supplier_appointment_slot_docks')
                ->onDelete('cascade');

                
            // Índices para rendimiento
            $table->index('scan_session_id');
            $table->index('dock_id');
            $table->index('anomaly_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anomalies');
    }
};