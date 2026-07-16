<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Links transactions to authenticated users.
     * - user_id is NULLABLE for full backward compatibility: all existing
     *   transactions (created anonymously before this feature) will keep
     *   user_id = NULL and continue to work without modification.
     * - ON DELETE SET NULL: if a user account is deleted, their transactions
     *   are preserved for record-keeping but de-linked (user_id becomes NULL).
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Add user_id after event_id, nullable for backward compatibility
            $table->foreignId('user_id')
                ->nullable()
                ->after('event_id')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
