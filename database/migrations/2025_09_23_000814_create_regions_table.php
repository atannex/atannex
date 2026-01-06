<?php

use App\Enums\Flag;
use App\Enums\Territories;
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
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('flag')->default(Flag::DRAFT);
            $table->unsignedInteger('position')->default(0);
            $table->string('slug_path')->nullable()->index();
            $table->string('territory')->default(Territories::QUARTER);
            $table->foreignId('parent_id')->nullable()->constrained('regions')->cascadeOnUpdate()->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
