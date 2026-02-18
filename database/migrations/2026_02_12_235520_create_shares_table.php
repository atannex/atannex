<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shares', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Polymorphic Relation
            |--------------------------------------------------------------------------
            */
            $table->morphs('shareable');

            /*
            |--------------------------------------------------------------------------
            | Visitor Identity
            |--------------------------------------------------------------------------
            */
            $table->string('visitor_key'); // NOT NULL

            /*
            |--------------------------------------------------------------------------
            | Platform + Frequency
            |--------------------------------------------------------------------------
            */
            $table->string('platform');
            $table->unsignedInteger('share_count')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Optional Tracking Metadata
            |--------------------------------------------------------------------------
            */
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('session_id')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Unique Per Visitor Per Platform
            |--------------------------------------------------------------------------
            | Ensures:
            | - One row per visitor per platform per post
            | - Prevents row duplication
            */
            $table->unique([
                'shareable_type',
                'shareable_id',
                'visitor_key',
                'platform',
            ], 'unique_share_per_visitor_platform');

            /*
            |--------------------------------------------------------------------------
            | Performance Indexes
            |--------------------------------------------------------------------------
            */
            $table->index(['shareable_type', 'shareable_id', 'share_count']);
            $table->index(['platform']);
            $table->index(['visitor_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shares');
    }
};
