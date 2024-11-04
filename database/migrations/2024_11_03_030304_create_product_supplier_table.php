<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSupplierTable extends Migration
{
    public function up()
    {
        Schema::create('product_supplier', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('supplier_id');
            $table->primary(['product_id', 'supplier_id']);
            $table->foreign('product_id')->references('product_id')->on('product')->onDelete('cascade');
            $table->foreign('supplier_id')->references('supplier_id')->on('supplier')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_supplier');
    }
}
