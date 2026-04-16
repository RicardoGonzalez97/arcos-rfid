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
       Schema::create('external_integrations', function (Blueprint $table) {
    $table->id();

    $table->string('external_source');
    $table->string('external_type');
    $table->string('external_id');

    $table->unsignedBigInteger('internal_order_id');

    $table->timestamps();

    $table->unique(
        ['external_source', 'external_type', 'external_id'],
        'ext_src_type_id_unique'
    );

    $table->index('internal_order_id');

    $table->foreign('internal_order_id')
        ->references('order_id')
        ->on('orders')
        ->cascadeOnDelete();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_integrations');
    }
};
