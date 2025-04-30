<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seo_analyses', function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique();
            $table->decimal('page_load_time', 10, 2)->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('heading_structure')->nullable();
            $table->decimal('image_alt_coverage', 5, 2)->nullable();
            $table->json('internal_links')->nullable();
            $table->json('external_links')->nullable();
            $table->boolean('mobile_friendly')->nullable();
            $table->boolean('ssl_enabled')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seo_analyses');
    }
};