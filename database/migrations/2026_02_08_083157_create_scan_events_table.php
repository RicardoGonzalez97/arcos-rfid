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
        Schema::create('scan_events', function (Blueprint $table) {
            $table->bigIncrements('scan_events_id');

            $table->string('scan_session_id');

            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('rfid_tags_info_id')->nullable();

            $table->string('event_status', 50);
            $table->timestamp('scanned_at')->useCurrent();

            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->nullOnDelete();

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->nullOnDelete();

            $table->foreign('rfid_tags_info_id')
                ->references('rfid_tags_info_id')
                ->on('rfid_tags_info')
                ->nullOnDelete();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scan_events');
    }
};
