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

            /* Polymorphic relation (auto-indexed) */
            $table->morphs('commentable');

            /* Threading */
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('comments')
                ->cascadeOnDelete();

            /* Ownership */
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /* Guest support */
            $table->boolean('is_guest')->default(false);
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_token', 64)->nullable()->index();

            /* Content */
            $table->text('comment');

            /* Moderation & spam */
            $table->ipAddress('ip_address')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamp('edited_at')->nullable();

            $table->timestamps();

            /* Additional useful indexes */
            $table->index('parent_id');
            $table->index('is_approved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
