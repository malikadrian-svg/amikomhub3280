<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Immutable audit log of every status change on an event
        Schema::create('event_approval_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_id')
                ->constrained('events')
                ->cascadeOnDelete();

            $table->string('action', 30);       // "submitted", "approved", "rejected", etc.
            $table->string('from_status', 30);
            $table->string('to_status', 30);
            $table->text('reason')->nullable();

            $table->foreignId('performed_by')
                ->constrained('users')
                ->restrictOnDelete();

            // No updated_at — this record is immutable
            $table->timestamp('created_at')->useCurrent();

            $table->index('event_id', 'idx_approval_logs_event');
        });

        // Pre-aggregated revenue snapshots for dashboard charts
        // Running this as a scheduled command (app:aggregate-daily-revenue) avoids
        // expensive GROUP BY queries on the orders table at dashboard render time.
        Schema::create('daily_revenue_snapshots', function (Blueprint $table) {
            $table->id();

            // NULL = platform-wide aggregate row
            $table->foreignId('organization_id')
                ->nullable()
                ->constrained('organizations')
                ->cascadeOnDelete();

            $table->date('date');
            $table->unsignedInteger('total_orders')->default(0);
            $table->unsignedInteger('total_tickets')->default(0);
            $table->unsignedBigInteger('gross_revenue')->default(0);
            $table->unsignedBigInteger('commission_revenue')->default(0);
            $table->unsignedBigInteger('net_revenue')->default(0);

            $table->timestamps();

            $table->unique(['organization_id', 'date'], 'unique_org_daily_snapshot');
            $table->index('date', 'idx_snapshots_date');
        });

        // Key-value store for platform configuration (replaces hardcoded constants)
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('type', 20)->default('string'); // string, integer, decimal, boolean, json
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
        Schema::dropIfExists('daily_revenue_snapshots');
        Schema::dropIfExists('event_approval_logs');
    }
};
