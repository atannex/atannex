<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Add feature_priority (default 0)
            if (!Schema::hasColumn('posts', 'feature_priority')) {
                $table->integer('feature_priority')->default(0)->after('flag');
            }

            // Add featured_until (nullable)
            if (!Schema::hasColumn('posts', 'featured_until')) {
                $table->dateTime('featured_until')->nullable()->after('feature_priority');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'featured_until')) {
                $table->dropColumn('featured_until');
            }
            if (Schema::hasColumn('posts', 'feature_priority')) {
                $table->dropColumn('feature_priority');
            }
        });
    }
};
