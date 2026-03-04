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
        Schema::create('order_scan_sessions', function (Blueprint $table) {

            $table->uuid('scan_session_id')->primary(); // 👈 CAMBIO AQUÍ

            $table->unsignedBigInteger('dock_id'); // ya no nullable si es obligatorio

            $table->string('status', 20)->default('OPEN');
            $table->timestamp('closed_at')->nullable();

            $table->timestamps();

            $table->index('dock_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_scan_sessions');
    }
};
