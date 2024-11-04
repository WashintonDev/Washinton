<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSaleTable extends Migration
{
    public function up()
    {
        Schema::create('sale', function (Blueprint $table) {
            $table->id('sale_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('store_id');
            $table->timestamp('sale_date')->nullable();
            $table->decimal('total_amount', 8, 2);
            $table->foreign('product_id')->references('product_id')->on('product');
            $table->foreign('store_id')->references('store_id')->on('store');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sale');
    }
}
