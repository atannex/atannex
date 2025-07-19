<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('liked_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['post_id', 'user_id'], 'uniq_post_likes_post_user');
        });

        Schema::create('post_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('rating')->unsigned();
            $table->text('review')->nullable();
            $table->string('rating_source', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['post_id', 'user_id'], 'uniq_post_ratings_post_user');
        });

        Schema::create('post_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('platform', 255)->nullable();
            $table->string('share_url', 255)->nullable();
            $table->unsignedInteger('share_count')->default(1);
            $table->timestamp('shared_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['post_id', 'platform'], 'idx_post_shares_post_platform');
        });

        Schema::create('post_engagements', function (Blueprint $table) {
            $table->foreignId('post_id')->primary()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('total_views')->default(0);
            $table->unsignedBigInteger('total_likes')->default(0);
            $table->unsignedBigInteger('total_shares')->default(0);
            $table->unsignedBigInteger('total_comments')->default(0);
            $table->decimal('avg_rating', 3, 2)->default(0.00);
            $table->decimal('engagement_score', 10, 2)->default(0.00);
            $table->timestamp('last_engagement_at')->nullable();
            $table->timestamps();
        });

        Schema::create('post_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->string('session_id', 255)->nullable();
            $table->string('referer_url', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['post_id', 'user_id'], 'idx_post_views_post_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_views');
        Schema::dropIfExists('post_engagements');
        Schema::dropIfExists('post_shares');
        Schema::dropIfExists('post_ratings');
        Schema::dropIfExists('post_likes');
    }
};
