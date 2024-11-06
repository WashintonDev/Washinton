<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchesTable extends Migration
{
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id('batch_id');
            $table->string('batch_name', 100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('batches');
    }
}