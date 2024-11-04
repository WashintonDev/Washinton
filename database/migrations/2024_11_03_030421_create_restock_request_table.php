<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestockRequestTable extends Migration
{
    public function up()
    {
        Schema::create('restock_request', function (Blueprint $table) {
            $table->id('restock_request_id');
            $table->unsignedBigInteger('supplier_id');
            $table->timestamp('request_date');
            $table->string('status', 10)->default('active');
            $table->foreign('supplier_id')->references('supplier_id')->on('supplier');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restock_request');
    }
}
