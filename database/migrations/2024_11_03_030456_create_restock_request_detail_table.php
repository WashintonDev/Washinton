<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestockRequestDetailTable extends Migration
{
    public function up()
    {
        Schema::create('restock_request_detail', function (Blueprint $table) {
            $table->id('request_detail_id');
            $table->unsignedBigInteger('restock_request_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->foreign('restock_request_id')->references('restock_request_id')->on('restock_request');
            $table->foreign('product_id')->references('product_id')->on('product');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restock_request_detail');
    }
}
