<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Extends the users table to support OAuth (Google SSO) authentication.
     * - password is made nullable because OAuth users never set a password.
     * - google_id is unique to prevent duplicate OAuth-linked accounts.
     * - provider/provider_id allow future multi-provider support (GitHub, etc.)
     * - phone is added for checkout pre-fill (phone is not provided by Google).
     *
     * No existing columns are removed. Fully backward-compatible.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Make password nullable — OAuth users have no password
            $table->string('password')->nullable()->change();

            // Google-specific OAuth identifier (unique, prevents duplicates)
            $table->string('google_id')->nullable()->unique()->after('email');

            // Google profile picture URL
            $table->string('avatar')->nullable()->after('google_id');

            // Auth provider name (e.g. 'google') for future extensibility
            $table->string('provider')->nullable()->after('avatar');

            // Provider's user ID (same as google_id for Google, but kept generic)
            $table->string('provider_id')->nullable()->after('provider');

            // Phone number for checkout pre-fill; not available from Google
            $table->string('phone', 20)->nullable()->after('provider_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable(false)->change();
            $table->dropColumn(['google_id', 'avatar', 'provider', 'provider_id', 'phone']);
        });
    }
};
