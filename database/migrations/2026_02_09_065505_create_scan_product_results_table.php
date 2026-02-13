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
       Schema::create('scan_product_results', function (Blueprint $table) {
        $table->bigIncrements('scan_product_results_id');

        $table->string('scan_session_id');
        $table->unsignedBigInteger('order_id');
        $table->unsignedBigInteger('product_id');

        $table->integer('expected_qty');
        $table->integer('scanned_qty');

        $table->string('status', 20); // OK | MISSING | EXTRA

        $table->timestamp('created_at')->useCurrent();

        $table->foreign('order_id')
            ->references('order_id')
            ->on('orders')
            ->onDelete('cascade');

        $table->foreign('product_id')
            ->references('product_id')
            ->on('products');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scan_product_results');
    }
};
