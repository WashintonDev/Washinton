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
            $table->string('name', 80); // Nombre del producto
            $table->string('sku', 10)->unique(); // SKU único
            $table->string('brand', 50); // Marca del producto
            $table->text('description')->nullable(); // Descripción del producto
            $table->decimal('price', 8, 2); // Precio del producto
            $table->string('status', 10)->default('active'); // Estado del producto
            $table->string('image', 100)->nullable(); // URL de la imagen del producto
            $table->unsignedBigInteger('category_id'); // Relación con categoría
            $table->unsignedBigInteger('supplier_id'); // Relación con proveedor
            $table->string('type', 50); // Tipo del producto
            $table->decimal('volume', 8, 2)->nullable(); // Volumen del producto
            $table->string('unit', 10)->nullable(); // Unidad de medida (litros, kg, etc.)
            $table->foreign('category_id')->references('category_id')->on('category'); // Llave foránea de categoría
            $table->foreign('supplier_id')->references('supplier_id')->on('supplier'); // Llave foránea de proveedor
            $table->timestamps(); // Timestamps de creación y actualización
        });
    }

    public function down()
    {
        Schema::dropIfExists('product');
    }
}
