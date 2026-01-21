<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('slug_path')->index();

            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('region_id')->constrained('regions')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('employees')->nullOnDelete();

            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('published_at')->nullable();

            $table->boolean('is_breaking')->default(false);
            $table->timestamp('breaking_at')->nullable();
            $table->timestamp('breaking_expires')->nullable();

            $table->boolean('is_editor_pick')->default(false);
            $table->timestamp('editor_pick_at')->nullable();
            $table->timestamp('editor_pick_expires')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['region_id', 'category_id', 'author_id', 'published_at']);
            $table->index(['is_breaking', 'breaking_at']);
            $table->index(['is_editor_pick', 'editor_pick_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
