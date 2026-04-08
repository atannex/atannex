<?php

use App\Enums\ReactionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();

            $table->morphs('reactable');

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('type')->default(ReactionType::LIKE);

            $table->timestamps();

            $table->unique([
                'reactable_type',
                'reactable_id',
                'user_id',
                'type',
            ], 'unique_reaction_per_user');

            $table->index(['reactable_type', 'reactable_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
