<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('backlink_prices', function (Blueprint $table) {
            $table->id();
            $table->string('domain');
            $table->string('type'); // dofollow, nofollow, etc.
            $table->decimal('price', 10, 2);
            $table->integer('da')->nullable(); // Domain Authority
            $table->integer('pa')->nullable(); // Page Authority
            $table->integer('traffic')->nullable();
            $table->string('language')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backlink_prices');
    }
};
