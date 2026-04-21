<?php

use App\Enums\Flag;
use App\Enums\Image;
use App\Enums\TailwindColor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->string('type', 50)
                ->default(Image::LOGO)
                ->index();

            $table->string('image', 255);

            $table->text('description')->nullable();

            $table->string('flag', 50)
                ->default(Flag::PENDING_REVIEW)
                ->index();

            $table->string('color', 50)
                ->default(TailwindColor::BLUE);

            $table->unsignedInteger('order')
                ->default(0)
                ->index();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
