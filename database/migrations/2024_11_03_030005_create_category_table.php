<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('name', 50);
            $table->text('description');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('category_id')->on('category')->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('category');
    }
}
