<?php

use App\Enums\Flag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the `posts` database table with its columns, foreign keys, indexes, timestamps, and soft delete support.
     *
     * The table includes: an auto-incrementing `id`, `title`, unique `slug`, foreign keys `category_id` and `author_id`
     * (both cascade on delete), nullable `updated_by` (set to null on delete), `description`, nullable `image`,
     * `published_at`, `slug_path` (indexed), Laravel `created_at`/`updated_at` timestamps, and `deleted_at` for soft deletes.
     * Indexes are added for `category_id`, `author_id`, and `published_at`.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->text('description');
            $table->string('image')->nullable();
            $table->timestamp('published_at');
            $table->string('slug_path')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index('category_id');
            $table->index('author_id');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};