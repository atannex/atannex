<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
