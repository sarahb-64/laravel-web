<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seo_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('url')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('priority')->default(0)->unsigned();
            $table->string('domain')->nullable();
            $table->string('timezone')->default('UTC');
            $table->timestamp('last_analyzed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['name', 'url', 'status']);
            $table->index('priority');
            $table->index('domain');
            $table->index('last_analyzed_at');
        });
    }
};