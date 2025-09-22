<?php

use App\Enums\Flag;
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
            // Add column if it doesn't exist
            if (!Schema::hasColumn('posts', 'flag')) {
                $table->string('flag')->default(Flag::PUBLISHED)->after('id');
            } else {
                // Update the default value if the column already exists
                $table->string('flag')->default(Flag::PUBLISHED)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('flag');
        });
    }
};
