<?php

use Illuminate\Database\Migrations\Migration;
use App\Enums\Flag;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Check if column exists before adding/updating
            if (!Schema::hasColumn('categories', 'flag')) {
                $table->string('flag')->default(Flag::PUBLISHED)->after('id');
            } else {
                // Update the default value if column exists
                $table->string('flag')->default(Flag::PUBLISHED)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('flag');
        });
    }
};
