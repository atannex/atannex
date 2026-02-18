<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('views', function (Blueprint $table) {
            $table->id();

            $table->morphs('viewable');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('visitor_key');
            $table->string('session_id')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            $table->index(['viewable_type', 'viewable_id', 'visitor_key']);
            $table->unique([
                'viewable_type',
                'viewable_id',
                'visitor_key',
            ], 'unique_view_per_visitor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('views');
    }
};
