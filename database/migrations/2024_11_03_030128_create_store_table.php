<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreTable extends Migration
{
    public function up()
    {
        Schema::create('store', function (Blueprint $table) {
            $table->id('store_id');
            $table->string('name', 50);
            $table->char('phone', 10);
            $table->text('address')->nullable();
            $table->string('status', 10)->default('active');
            $table->string('city', 30);
            $table->string('state', 30);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('store');
    }
}
