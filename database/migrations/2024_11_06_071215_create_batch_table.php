<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchTable extends Migration
{
    public function up()
    {
        Schema::create('batch', function (Blueprint $table) {
            $table->id('batch_id');
            $table->string('code', 10)->unique(); // Código de 10 dígitos
            $table->string('batch_name', 100)->notNullable();
            $table->string('status', 20)->default('pending'); // Estado del lote
            $table->timestamp('requested_at')->nullable();
            $table->timestamps(); // Esto agrega created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('batch');
    }
}
