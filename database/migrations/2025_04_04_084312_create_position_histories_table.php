<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('position_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keyword_id')->constrained('keywords')->onDelete('cascade');
            $table->integer('position');
            $table->string('search_engine')->default('google');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('position_histories');
    }
};