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

            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();

            $table->text('comment');
            $table->string('status', 255)->default('pending');

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->ipAddress('ip_address')->nullable();
            $table->string('ip_country', 100)->nullable();
            $table->text('user_agent')->nullable();

            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('dislikes_count')->default(0);
            $table->unsignedInteger('replies_count')->default(0);

            $table->timestamp('edited_at')->nullable();
            $table->string('edited_reason', 255)->nullable();
            $table->foreignId('edited_by')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();

            $table->text('moderation_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
