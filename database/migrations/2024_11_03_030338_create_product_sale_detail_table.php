<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSaleDetailTable extends Migration
{
    public function up()
    {
        Schema::create('sale_detail', function (Blueprint $table) {
            $table->id('sale_detail_id');
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->decimal('price_per_unit', 8, 2);
            $table->decimal('total_price', 8, 2);
            $table->foreign('sale_id')->references('sale_id')->on('sale');
            $table->foreign('product_id')->references('product_id')->on('product');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sale_detail');
    }
}
