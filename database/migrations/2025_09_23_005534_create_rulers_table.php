<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rulers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->string('dynasty')->nullable();
            $table->string('title')->default('mbe');
            $table->string('classification')->default('unclassified');
            $table->date('reign_start')->nullable();
            $table->date('reign_end')->nullable();
            $table->foreignId('region_id')->constrained('regions')->cascadeOnDelete();
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->string('flag')->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rulers');
    }
};
