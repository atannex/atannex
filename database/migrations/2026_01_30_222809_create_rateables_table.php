<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rateables', function (Blueprint $table) {
            $table->id();

            $table->morphs('rateable');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('guest_id')
                ->nullable()
                ->constrained('guests')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('rating_hash', 64)->nullable()->unique();

            $table->softDeletes();
            $table->timestamps();

            $table->unique(
                ['rateable_type', 'rateable_id', 'user_id'],
                'unique_user_rateable'
            );

            $table->unique(
                ['rateable_type', 'rateable_id', 'guest_id'],
                'unique_guest_rateable'
            );

            $table->index(['rateable_type', 'rateable_id', 'rating_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rateables');
    }
};
