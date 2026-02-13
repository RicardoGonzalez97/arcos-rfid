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
       Schema::create('rfid_tags_info', function (Blueprint $table) {
            $table->bigIncrements('rfid_tags_info_id');

            $table->string('rfid')->unique();
            $table->unsignedBigInteger('product_id');

            $table->timestamp('created_at')->useCurrent();

            $table->foreign('product_id')->references('product_id')->on('products');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfid_tags_info');
    }
};
