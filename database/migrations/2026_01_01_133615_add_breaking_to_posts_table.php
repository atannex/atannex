<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add breaking-related columns and indexes to the posts table.
     *
     * Adds a boolean `is_breaking` (default false, indexed) and two nullable timestamp columns
     * `breaking_at` and `breaking_expires`, each indexed.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('is_breaking')
                ->default(false)
                ->index()
                ->after('flag');

            $table->timestamp('breaking_at')
                ->nullable()
                ->index()
                ->after('is_breaking');

            $table->timestamp('breaking_expires')
                ->nullable()
                ->index()
                ->after('breaking_at');
        });
    }

    /**
     * Reverts the migration by removing breaking-related indexes and columns from the posts table.
     *
     * Removes the indexes and drops the `is_breaking`, `breaking_at`, and `breaking_expires` columns.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['is_breaking']);
            $table->dropIndex(['breaking_at']);
            $table->dropIndex(['breaking_expires']);

            $table->dropColumn([
                'is_breaking',
                'breaking_at',
                'breaking_expires',
            ]);
        });
    }
};