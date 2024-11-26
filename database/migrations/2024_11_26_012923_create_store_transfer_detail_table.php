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
        Schema::create('store_transfer_detail', function (Blueprint $table) {
            $table->id('transfer_detail_id');
            $table->unsignedBigInteger('store_transfer_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->notNullable();
            $table->string('status', 10)->default('pending');

            // Definición de las claves foráneas
            $table->foreign('store_transfer_id')->references('store_transfer_id')->on('store_transfer')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('product')->onDelete('cascade');
            $table->unique(['store_transfer_id', 'product_id']);
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_transfer_detail');
    }
};