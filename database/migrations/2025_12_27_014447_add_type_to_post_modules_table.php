<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PostType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('post_modules', function (Blueprint $table) {
            $table
                ->enum('type', PostType::getValues())
                ->default(PostType::ARTICLE)
                ->after('id')
                ->index()
                ->comment('Type of post module: article, video, audio');

            $table
                ->json('video')
                ->nullable()
                ->after('type')
                ->comment('Video payload: { id, signature }');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_modules', function (Blueprint $table) {
            $table->dropColumn(['type', 'video']);
        });
    }
};
