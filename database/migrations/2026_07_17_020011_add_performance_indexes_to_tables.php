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
        Schema::table('events', function (Blueprint $table) {
            $table->index(['organization_id', 'status', 'start_date'], 'idx_org_status_date');
            $table->index(['status', 'start_date'], 'idx_status_date');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['organization_id', 'status', 'created_at'], 'idx_org_status_created');
            $table->index(['event_id', 'status'], 'idx_event_status');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['event_id', 'is_approved', 'rating'], 'idx_event_approved_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('idx_org_status_date');
            $table->dropIndex('idx_status_date');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_org_status_created');
            $table->dropIndex('idx_event_status');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('idx_event_approved_rating');
        });
    }
};
