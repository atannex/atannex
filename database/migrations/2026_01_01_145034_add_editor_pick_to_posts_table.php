<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add editor-pick columns to the posts table.
     *
     * Adds the following columns to support editor-pick functionality:
     * - `is_editor_pick` (boolean) default false, placed after `is_breaking`.
     * - `editor_pick_at` (timestamp) nullable, placed after `is_editor_pick`.
     * - `editor_pick_expires` (timestamp) nullable, placed after `editor_pick_at`.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('is_editor_pick')->default(false)->after('is_breaking');
            $table->timestamp('editor_pick_at')->nullable()->after('is_editor_pick');
            $table->timestamp('editor_pick_expires')->nullable()->after('editor_pick_at');
        });
    }

    /**
     * Revert the posts table schema by removing editor-pick fields.
     *
     * Removes the `is_editor_pick`, `editor_pick_at`, and `editor_pick_expires` columns from the `posts` table.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['is_editor_pick', 'editor_pick_at', 'editor_pick_expires']);
        });
    }
};