<?php

use App\Enums\Flag;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('social_media', function (Blueprint $table) {
            $table->id();
            $table->string('label', 255);
            $table->nullableMorphs('owner');
            $table->string('url', 255);
            $table->string('platform', 255)->default('generic');
            $table->unsignedInteger('order')->default(0);
            $table->string('flag')->default(Flag::PENDING);
            $table->boolean('is_global')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['owner_id', 'owner_type', 'platform'], 'uniq_owner_platform');
        });

        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique()->index('idx_colors_name');
            $table->string('hex', 7);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colors');
        Schema::dropIfExists('social_media');
        Schema::dropIfExists('atannexes');
    }
};
