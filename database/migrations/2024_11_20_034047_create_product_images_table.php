<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductImagesTable extends Migration
{
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->unsignedBigInteger('product_id'); // Clave foránea al producto
            $table->string('image_path'); // Ruta de la imagen
            $table->timestamps(); // Campos created_at y updated_at

            // Definir la clave foránea
            $table->foreign('product_id')->references('product_id')->on('product')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_images');
    }
}
