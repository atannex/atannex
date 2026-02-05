<?php

use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->string('session_id')->nullable()->index();

            $table->ipAddress('ip_address');
            $table->string('user_agent');

            $table->string('device_type')->nullable()->index();
            $table->string('platform')->nullable()->index();
            $table->string('browser')->nullable()->index();

            $table->string('locale', 10)->nullable()->index();
            $table->string('timezone', 50)->nullable();

            $table->string('referrer')->nullable();
            $table->string('landing_url')->nullable();

            $table->string('fingerprint', 64)->unique();

            $table->timestamp('first_seen_at')->nullable()->index();
            $table->timestamp('last_activity_at')->nullable()->index();
            $table->unsignedInteger('visit_count')->default(1);

            $table->string('status')->default(Status::PENDING)->index();
            $table->unsignedTinyInteger('trust_score')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['ip_address', 'user_agent'], 'ip_ua_idx');
            $table->index(['fingerprint', 'last_activity_at']);
            $table->index(['status', 'trust_score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
