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
        Schema::create('power_events', function (Blueprint $table) {
            $table->id();

            $table->string('power_status', 50);

            $table->timestamp('reported_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('power_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('power_events');
    }
};
