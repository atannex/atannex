<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique();
            $table->string('title', 255);
            $table->foreignId('parent_id')->nullable()->constrained('pages')->cascadeOnUpdate()->cascadeOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique();
            $table->string('type');
            $table->string('name');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('page_section', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->json('config')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('widget_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('widget_id')->constrained('widgets')->cascadeOnDelete();
            $table->json('config')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('widget_sections');
        Schema::dropIfExists('page_section');
        Schema::dropIfExists('widget_revisions');
        Schema::dropIfExists('widgets');
        Schema::dropIfExists('section_revisions');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('page_revisions');
        Schema::dropIfExists('pages');
    }
};
