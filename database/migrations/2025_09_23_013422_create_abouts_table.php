<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('subtitle')->nullable();

            $table->text('description')->nullable();
            $table->text('map')->nullable();
            $table->json('image')->nullable();
            $table->json('cta')->nullable();

            $table->text('video_url')->nullable();

            $table->json('features')->nullable();
            $table->json('story')->nullable();
            $table->json('counters')->nullable();

            $table->json('info')->nullable();

            $table->string('flag')->default('pending');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};
