<?php

use App\Enums\Flag;
use App\Enums\Territories;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('flag')->default(Flag::PENDING);
            $table->string('slug_path')->nullable();
            $table->string('territory')->default(Territories::QUARTER)->nullable();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('regions')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('post_region', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')
                ->nullable()
                ->constrained('posts')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('region_id')
                ->nullable()
                ->constrained('regions')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamps();
            $table->index(['post_id', 'region_id']);
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('post_region');
        Schema::dropIfExists('regions');

        Schema::enableForeignKeyConstraints();
    }
};
