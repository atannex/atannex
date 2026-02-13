<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();

            $table->morphs('rateable');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('visitor_key');

            $table->string('session_id')->nullable();
            $table->ipAddress('ip_address')->nullable();

            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();

            $table->timestamps();

            $table->unique([
                'rateable_type',
                'rateable_id',
                'visitor_key'
            ], 'unique_rating_per_visitor');

            $table->index(['rateable_type', 'rateable_id', 'visitor_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
