<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('name', 80);
            $table->string('sku', 10)->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('status', 10)->default('active');
            $table->string('image', 100)->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('supplier_id');
            $table->string('type', 50);
            $table->foreign('category_id')->references('category_id')->on('category');
            $table->foreign('supplier_id')->references('supplier_id')->on('supplier');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product');
    }
}
