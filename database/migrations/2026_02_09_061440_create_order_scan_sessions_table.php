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

            $table->id('order_scan_session_id');

            $table->uuid('scan_session_id');
            $table->unsignedBigInteger('order_id');

            $table->string('status', 20)->default('OPEN');
            $table->timestamp('closed_at')->nullable();

            $table->timestamps(); // created_at / updated_at

           
            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->onDelete('cascade');

          
            $table->unique('scan_session_id');
            $table->index(['order_id', 'status']);
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
