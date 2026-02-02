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
        Schema::create('video_modules', function (Blueprint $table) {
            $table->id();
            $table->longText('content')->nullable();
            $table->foreignId('video_id')
                ->constrained('videos')
                ->cascadeOnDelete()
                ->index();
            $table->json('images')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_modules');
    }
};
