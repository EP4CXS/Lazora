<?php

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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            $table->string('phone_otp_hash', 255)->nullable()->after('phone_verified_at');
            $table->timestamp('phone_otp_expires_at')->nullable()->after('phone_otp_hash');
            $table->unsignedSmallInteger('phone_otp_attempts')->default(0)->after('phone_otp_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_verified_at',
                'phone_otp_hash',
                'phone_otp_expires_at',
                'phone_otp_attempts',
            ]);
        });
    }
};
