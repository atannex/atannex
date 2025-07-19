<?php

use App\Enums\Flag;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('type', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('author_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('flag')->default(Flag::PENDING);
            $table->timestamp('published_at')->nullable();
        });

        Schema::create('document_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->text('content')->nullable();
            $table->string('flag')->default(Flag::PENDING);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_modules');
        Schema::dropIfExists('documents');
    }
};
