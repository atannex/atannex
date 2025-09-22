<?php

use App\Enums\Gender;
use App\Enums\Status;
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
        // Create users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable()->index();
            $table->string('slug', 100)->nullable()->unique();
            $table->string('email', 150)->unique()->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->string('image')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->default(Gender::MALE)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('status')->default(Status::PENDING);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create password reset tokens table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 150)->primary();
            $table->string('token', 255);
            $table->timestamp('created_at')->nullable();
            $table->index(['email', 'created_at']); // Composite index for faster lookups
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade'); // Added foreign key
        });

        // Create sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Improved foreign key
            $table->string('ip_address', 45)->nullable()->index(); // Added index
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
            $table->timestamp('created_at')->nullable(); // Added for tracking session creation
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
