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
        Schema::create('store_transfer', function (Blueprint $table) {
            $table->id('store_transfer_id');
            $table->unsignedBigInteger('store_id');
            $table->string('store_transfer_name', 100)->notNullable();
            $table->string('status', 20)->default('pending');
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('received_date')->nullable();
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('store')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_transfer');
    }
};