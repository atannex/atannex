<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->string('email');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->ipAddress('verified_ip')->nullable();
            $table->string('verification_token')->nullable()->index();
            $table->timestamp('verification_expires_at')->nullable();

            $table->boolean('is_unsubscribed')->default(false);
            $table->timestamp('unsubscribed_at')->nullable();
            $table->ipAddress('unsubscribed_ip')->nullable();
            $table->string('unsubscribe_reason')->nullable();
            $table->string('unsubscribe_token')->nullable()->index();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_verified', 'is_unsubscribed']);
        });

        /**
         * Partial unique index (email + deleted_at)
         * Prevents duplicate active subscriptions
         * while allowing re-subscribe after soft delete.
         */
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unique(
                ['email', 'deleted_at'],
                'subscriptions_email_deleted_at_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
