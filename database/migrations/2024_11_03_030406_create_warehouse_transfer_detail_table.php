<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseTransferDetailTable extends Migration
{
    public function up()
    {
        Schema::create('warehouse_transfer_detail', function (Blueprint $table) {
            $table->id('transfer_detail_id');
            $table->unsignedBigInteger('transfer_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->foreign('transfer_id')->references('transfer_id')->on('warehouse_transfer');
            $table->foreign('product_id')->references('product_id')->on('product');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouse_transfer_detail');
    }
}
