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
    Schema::create('scan_session_results', function (Blueprint $table) {

        $table->bigIncrements('scan_session_results_id');

        $table->uuid('scan_session_id');
        $table->unsignedBigInteger('order_id');   // 🔥 NECESARIO
        $table->unsignedBigInteger('dock_id');

        $table->integer('expected_total')->default(0);
        $table->integer('scanned_total')->default(0);
        $table->integer('missing_total')->default(0);
        $table->integer('extra_total')->default(0);

        $table->string('status', 20);

        $table->timestamps();

        $table->index('scan_session_id');
        $table->index('order_id');
        $table->index('dock_id');

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
        Schema::dropIfExists('scan_session_results');
    }
};
