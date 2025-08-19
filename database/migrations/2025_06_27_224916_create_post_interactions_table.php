<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->morphs('likeable');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('liked_at')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['likeable_id', 'likeable_type', 'user_id'], 'uniq_likes_user');
        });

        Schema::create('views', function (Blueprint $table) {
            $table->id();
            $table->morphs('viewable');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('viewed_at')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['viewable_id', 'viewable_type', 'user_id'], 'idx_views_user');
        });

        Schema::create('shares', function (Blueprint $table) {
            $table->id();
            $table->morphs('shareable');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('platform', 100)->nullable();
            $table->unsignedInteger('share_count')->default(1);
            $table->timestamp('shared_at')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['shareable_id', 'shareable_type', 'platform'], 'idx_shares_platform');
        });

        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->morphs('rateable');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('rating');
            $table->timestamp('rated_at')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['rateable_id', 'rateable_type', 'user_id'], 'uniq_rateable_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shares');
        Schema::dropIfExists('views');
        Schema::dropIfExists('likes');
    }
};
