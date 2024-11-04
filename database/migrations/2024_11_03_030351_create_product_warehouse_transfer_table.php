<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductWarehouseTransferTable extends Migration
{
    public function up()
    {
        Schema::create('warehouse_transfer', function (Blueprint $table) {
            $table->id('transfer_id');
            $table->unsignedBigInteger('store_id');
            $table->timestamp('transfer_date');
            $table->string('status', 10)->default('active');
            $table->foreign('store_id')->references('store_id')->on('store');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouse_transfer');
    }
}
