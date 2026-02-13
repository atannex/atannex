<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ReactionType;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();

            $table->morphs('reactable');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('visitor_key');

            $table->string('session_id')->nullable();
            $table->ipAddress('ip_address')->nullable();

            $table->string('type')->default(ReactionType::LIKE);

            $table->timestamps();

            $table->unique([
                'reactable_type',
                'reactable_id',
                'visitor_key',
                'type'
            ], 'unique_reaction_per_visitor');

            $table->index(['reactable_type', 'reactable_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
