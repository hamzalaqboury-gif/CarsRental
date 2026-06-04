<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('address')->nullable()->after('phone');
            $table->string('driver_license')->nullable()->after('address');
            $table->string('avatar')->nullable()->after('driver_license');
            $table->boolean('is_active')->default(true)->after('avatar');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'driver_license', 'avatar', 'is_active']);
            $table->dropSoftDeletes();
        });
    }
};
