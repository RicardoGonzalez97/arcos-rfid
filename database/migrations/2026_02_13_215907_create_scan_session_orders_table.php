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
         Schema::create('scan_session_orders', function (Blueprint $table) {
            $table->id();

            $table->uuid('scan_session_id');
            $table->unsignedBigInteger('order_id');

            $table->timestamps();

            $table->unique(['scan_session_id', 'order_id']);

            $table->foreign('scan_session_id')
                ->references('scan_session_id')
                ->on('order_scan_sessions')
                ->onDelete('cascade');

            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scan_session_orders');
    }
};
