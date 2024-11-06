<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductBatchesTable extends Migration
{
    public function up()
    {
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id('product_batch_id');
            $table->foreignId('batch_id')->constrained('batches', 'batch_id');
            $table->foreignId('product_id')->constrained('product', 'product_id');
            $table->integer('quantity');
            $table->date('received_date');
            $table->date('expiration_date')->nullable();
            $table->string('status', 10);
            $table->timestamps();

            $table->unique(['batch_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_batches');
    }
}