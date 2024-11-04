<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductBatchTable extends Migration
{
    public function up()
    {
        Schema::create('product_batch', function (Blueprint $table) {
            $table->id('batch_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->date('received_date');
            $table->date('expiration_date')->nullable();
            $table->string('status', 10)->default('active');
            $table->foreign('product_id')->references('product_id')->on('product');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_batch');
    }
}
