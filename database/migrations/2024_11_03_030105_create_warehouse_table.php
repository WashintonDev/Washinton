<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseTable extends Migration
{
    public function up()
    {
        Schema::create('warehouse', function (Blueprint $table) {
            $table->id('warehouse_id');
            $table->string('name', 80);
            $table->char('phone', 10);
            $table->string('status', 10)->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouse');
    }
}
