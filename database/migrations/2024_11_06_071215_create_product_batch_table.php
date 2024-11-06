<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductBatchTable extends Migration
{
    public function up()
    {
        Schema::create('product_batch', function (Blueprint $table) {
            $table->id('product_batch_id');
            $table->unsignedBigInteger('batch_id'); // Cambié a unsignedBigInteger para ser consistente
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->notNullable();
            $table->date('expiration_date')->nullable(); // Fecha de expiración
            $table->string('status', 10)->default('active'); // Estado del producto en el lote

            // Definición de las claves foráneas
            $table->foreign('batch_id')->references('batch_id')->on('batch');
            $table->foreign('product_id')->references('product_id')->on('product');
            $table->unique(['batch_id', 'product_id']); // Asegura que no haya duplicados por lote y producto
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_batch');
    }
}
