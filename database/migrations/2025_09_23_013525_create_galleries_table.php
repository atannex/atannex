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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('original_name', 255)->nullable();
            $table->string('type', 255)->default('logo')->index('idx_galleries_type');
            $table->string('image', 255);
            $table->text('description')->nullable();
            $table->string('flag', 50)->default('pending');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['type', 'flag'], 'idx_galleries_type_flag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
