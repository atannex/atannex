<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->string('slug', 100)->nullable()->unique()->after('name');
            $table->string('image')->nullable()->after('slug');
            $table->date('date_of_birth')->nullable()->after('image');
            $table->string('gender')->default('male')->nullable()->after('date_of_birth');
            $table->string('phone', 20)->nullable()->after('gender');
            $table->string('status')->default('pending')->after('phone');
            $table->string('locale')->default('en')->nullable()->after('email');
            $table->string('timezone')->default(config('app.timezone'))->after('locale');

            $table->string('address')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->nullable()->after('state');
            $table->string('zip_code', 20)->nullable()->after('country');
            $table->text('bio')->nullable()->after('image');
            $table->json('metadata')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'slug',
                'image',
                'date_of_birth',
                'gender',
                'phone',
                'status',
                'locale',
                'timezone',
                'address',
                'city',
                'state',
                'country',
                'zip_code',
                'bio',
                'metadata',
            ]);
        });
    }
};
