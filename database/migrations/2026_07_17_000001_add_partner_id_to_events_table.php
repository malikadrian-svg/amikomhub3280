<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Links events to their organizer (Partner).
     * - partner_id is NULLABLE so existing events without a partner are unaffected.
     * - ON DELETE SET NULL: deleting a Partner de-links events rather than
     *   cascading the delete — events and their reviews survive partner deletion.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('partner_id')
                ->nullable()
                ->after('category_id')
                ->constrained('partners')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->dropColumn('partner_id');
        });
    }
};
