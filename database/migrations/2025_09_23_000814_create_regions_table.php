<?php

use App\Enums\Flag;
use App\Enums\Territories;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the `regions` table with its columns, constraints, defaults, and soft deletes.
     *
     * The table includes an auto-increment `id`; `name` and `slug` (unique); nullable `description` and `logo`;
     * `flag` defaulting to `Flag::DRAFT`; unsigned `position` defaulting to 0; nullable indexed `slug_path`;
     * `territory` defaulting to `Territories::QUARTER`; nullable `parent_id` referencing `regions.id` (cascade on update, set null on delete);
     * nullable JSON `metadata`; automatic `created_at`/`updated_at` timestamps; and a `deleted_at` soft delete column.
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