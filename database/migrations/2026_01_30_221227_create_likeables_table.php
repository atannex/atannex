<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ReactionType;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('likeables', function (Blueprint $table) {
            $table->id();

            $table->morphs('likeable');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('guest_id')
                ->nullable()
                ->constrained('guests')
                ->cascadeOnDelete();

            $table->string('type')->default(ReactionType::LIKE);

            $table->timestamps();

            $table->unique(
                ['likeable_type', 'likeable_id', 'user_id'],
                'unique_likeable_user'
            );

            $table->unique(
                ['likeable_type', 'likeable_id', 'guest_id'],
                'unique_likeable_guest'
            );

            $table->index(['likeable_id', 'likeable_type', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likeables');
    }
};
