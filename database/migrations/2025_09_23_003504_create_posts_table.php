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
            $table->string('flag')->default('draft');
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->text('description');
            $table->string('image')->nullable();
            $table->timestamp('published_at');
            $table->json('metadata')->nullable();
            $table->string('slug_path')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('is_breaking')->default(false);
            $table->timestamp('breaking_until')->nullable();
            $table->timestamp('feature_until')->nullable();
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
