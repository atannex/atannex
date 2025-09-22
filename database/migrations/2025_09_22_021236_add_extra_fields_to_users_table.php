<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Gender;
use App\Enums\Status;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Modify only the column size/nullability, don't re-add index
            if (Schema::hasColumn('users', 'name')) {
                $table->string('name', 100)->nullable()->change();
            }

            if (!Schema::hasColumn('users', 'slug')) {
                $table->string('slug', 100)->nullable()->unique()->after('name');
            }

            if (Schema::hasColumn('users', 'email')) {
                $table->string('email', 150)->change(); // already unique/indexed
            }

            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }

            if (Schema::hasColumn('users', 'password')) {
                $table->string('password', 255)->change();
            }

            if (!Schema::hasColumn('users', 'image')) {
                $table->string('image')->nullable()->after('password');
            }

            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('image');
            }

            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender')->default(Gender::MALE)->nullable()->after('date_of_birth');
            }

            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('gender');
            }

            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default(Status::PENDING)->after('phone');
            }

            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes()->after('timestamps');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['slug', 'image', 'date_of_birth', 'gender', 'phone', 'status', 'deleted_at'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }

            if (Schema::hasColumn('users', 'name')) {
                $table->string('name')->nullable(false)->change();
            }

            if (Schema::hasColumn('users', 'email')) {
                $table->string('email')->change(); // don’t touch unique/index here
            }

            if (Schema::hasColumn('users', 'password')) {
                $table->string('password')->change();
            }
        });
    }
};

