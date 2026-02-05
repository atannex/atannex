<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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

            $table->foreignId('guest_id')
                ->nullable()
                ->constrained('guests')
                ->cascadeOnDelete();

            $table->text('comment');
            $table->string('comment_hash', 64)->unique();

            $table->ipAddress('ip_address')->nullable();
            $table->timestamp('edited_at')->nullable();

            $table->unsignedInteger('like_count')->default(0);
            $table->unsignedInteger('dislike_count')->default(0);

            $table->softDeletes();
            $table->timestamps();

            $table->unique(
                ['commentable_type', 'commentable_id', 'guest_id', 'parent_id'],
                'unique_guest_comment_per_thread'
            );

            $table->unique(
                ['commentable_type', 'commentable_id', 'user_id', 'parent_id'],
                'unique_user_comment_per_thread'
            );

            $table->index(['parent_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
