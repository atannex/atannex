<?php

use App\Enums\Status;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191)->nullable();
            $table->string('email', 191)->nullable()->index();
            $table->string('phone', 50)->nullable();
            $table->string('subject', 191);
            $table->text('message');
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('source')->nullable();
            $table->timestamp('read_at')->nullable()->index();
            $table->timestamp('responded_at')->nullable()->index();
            $table->string('flag')->default(Status::PENDING)->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['flag', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
