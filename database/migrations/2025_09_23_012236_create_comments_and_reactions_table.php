<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Comments Table
        |--------------------------------------------------------------------------
        */
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            $table->morphs('commentable');

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('comments')
                ->cascadeOnDelete();

            $table->unsignedInteger('reply_count')->default(0);

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->boolean('is_guest')->default(false);
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();

            $table->text('comment');
            $table->string('comment_hash', 64)->nullable()->unique();

            $table->ipAddress('ip_address')->nullable();
            $table->timestamp('edited_at')->nullable();

            $table->unsignedInteger('like_count')->default(0);
            $table->unsignedInteger('dislike_count')->default(0);

            $table->softDeletes();
            $table->timestamps();

            $table->index(['parent_id', 'created_at']);
        });

        /*
        |--------------------------------------------------------------------------
        | Comment Reactions Table
        |--------------------------------------------------------------------------
        */
        Schema::create('comment_reaction', function (Blueprint $table) {
            $table->id();

            $table->foreignId('comment_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('type', ['like', 'dislike']);

            $table->timestamps();

            $table->unique(['comment_id', 'user_id'], 'comment_reactions_unique_user');

            $table->index(['comment_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_reaction');
        Schema::dropIfExists('comments');
    }
};
