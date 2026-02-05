<?php

use App\Enums\Flag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->morphs('reviewable');

            $table->text('content');
            $table->string('reviewer_name')->nullable();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('guest_id')
                ->nullable()
                ->constrained('guests')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('reviewer_rating');
            $table->string('flag')->default(Flag::PENDING_REVIEW);

            $table->ipAddress('ip_address')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->unique(
                ['reviewable_type', 'reviewable_id', 'user_id'],
                'unique_user_review_per_reviewable'
            );

            $table->unique(
                ['reviewable_type', 'reviewable_id', 'guest_id'],
                'unique_guest_review_per_reviewable'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
