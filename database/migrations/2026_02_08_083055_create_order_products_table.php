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
       Schema::create('order_products', function (Blueprint $table) {

        $table->bigIncrements('order_products_id');

        $table->unsignedBigInteger('order_id');
        $table->string('product_id', 50);

        $table->unsignedInteger('expected_quantity');
        $table->unsignedInteger('received_quantity')->default(0);

        $table->decimal('unit_price', 10, 2);

        $table->boolean('is_completed')->default(false);

        $table->timestamps();

        /*
        |--------------------------------------------------------------------------
        | Índices
        |--------------------------------------------------------------------------
        */

        // 🔥 Evita duplicados y optimiza búsquedas por orden + producto
        $table->unique(['order_id', 'product_id']);

        // 🔥 Optimiza whereIn(order_id)
        $table->index('order_id');

        // 🔥 Optimiza búsquedas por producto
        $table->index('product_id');

        // 🔥 Optimiza validación de pendientes (micro-optimización útil)
        $table->index(['order_id', 'received_quantity']);

        /*
        |--------------------------------------------------------------------------
        | Foreign Keys
        |--------------------------------------------------------------------------
        */

        $table->foreign('order_id')
            ->references('order_id')
            ->on('orders')
            ->cascadeOnDelete();

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
        Schema::dropIfExists('order_products');
    }
};
