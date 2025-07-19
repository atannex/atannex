<?php

use App\Enums\Flag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rulers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image');
            $table->string('traditional_title')->nullable();
            $table->foreignId('region_id')->constrained('regions')->cascadeOnDelete()->cascadeOnUpdate();       // Village or fondom
            $table->enum('rank', ['1st Class', '2nd Class', '3rd Class', 'Unclassified'])
                ->default('Unclassified');
            $table->date('reign_start')->nullable();
            $table->date('reign_end')->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->string('flag')->default(Flag::PENDING);
            $table->timestamps();
            $table->json('metadata')->nullable();
            $table->string('dynasty')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rulers');
    }
};
