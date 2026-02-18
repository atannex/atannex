<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add root_id to comments so we can distinguish:
     *   - parent_id → immediate parent (top-level comment OR a reply)
     *   - root_id   → always the top-level comment (for easy thread queries)
     *
     * Structure:
     *   Top-level comment:  parent_id = null,              root_id = null
     *   Direct reply:       parent_id = top-level id,      root_id = top-level id
     *   Reply to a reply:   parent_id = the reply's id,    root_id = top-level id
     */
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('root_id')
                ->nullable()
                ->after('parent_id')
                ->constrained('comments')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['root_id']);
            $table->dropColumn('root_id');
        });
    }
};
