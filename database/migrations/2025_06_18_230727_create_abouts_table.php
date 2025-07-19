<?php

use App\Enums\Flag;
use App\Enums\Image;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('subtitle', 255)->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->json('image')->nullable();
            $table->text('video')->nullable();
            $table->json('cta')->nullable();
            $table->string('cta_background', 255)->nullable();
            $table->string('slug', 255)->unique()->index('idx_abouts_slug');
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('flag', 50)->default(Flag::PENDING);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique()->index('idx_achievements_slug');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('image', 255)->nullable();
            $table->year('year')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamp('published_at')->nullable()->index('idx_achievements_published_at');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('flag', 50)->default(Flag::PENDING);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('original_name', 255)->nullable();
            $table->string('type', 255)->default(Image::LOGO)->index('idx_galleries_type');
            $table->string('image', 255);
            $table->text('description')->nullable();
            $table->string('flag', 50)->default(Flag::PENDING);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['type', 'flag'], 'idx_galleries_type_flag');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('abouts');
    }
};
