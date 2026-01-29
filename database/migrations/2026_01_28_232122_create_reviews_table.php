<?php

use App\Enums\Flag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->morphs('reviewable');
            $table->text('content');
            $table->string('reviewer_name')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('reviewer_rating');
            $table->string('flag')->default(Flag::PENDING_REVIEW);
            $table->ipAddress('ip_address')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
