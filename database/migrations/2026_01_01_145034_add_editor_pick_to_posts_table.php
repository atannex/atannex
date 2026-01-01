<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
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
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['is_editor_pick', 'editor_pick_at', 'editor_pick_expires']);
        });
    }
};
