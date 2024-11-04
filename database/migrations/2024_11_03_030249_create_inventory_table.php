<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTable extends Migration
{
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->integer('stock');
            $table->foreign('product_id')->references('product_id')->on('product');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouse')->onDelete('cascade');
            $table->foreign('store_id')->references('store_id')->on('store')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory');
    }
}
