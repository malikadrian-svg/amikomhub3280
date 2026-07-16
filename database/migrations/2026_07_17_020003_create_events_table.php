<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Full redesign of the events table for SaaS:
     *  - organization_id replaces partner_id (tenant isolation key).
     *  - price/stock removed — moved to ticket_types (supports multiple tiers).
     *  - start_date replaces date (renamed for clarity; end_date added for multi-day).
     *  - status lifecycle: draft → pending_review → approved → published → completed.
     *  - Composite index on (status, start_date) is the hot path for public catalog queries.
     *  - Unique slug scoped per organization (not globally unique).
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Tenant isolation key — all organizer queries filter by this
            $table->foreignId('organization_id')
                ->nullable()
                ->constrained('organizations')
                ->nullOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            // Super Admin who approved this event
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('short_description', 500)->nullable();

            // Renamed from `date`; supports multi-day events
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();

            $table->string('location');
            $table->string('venue_name')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('poster_path')->nullable();

            // Publication lifecycle
            $table->enum('status', [
                'draft',
                'pending_review',
                'approved',
                'published',
                'completed',
                'archived',
                'rejected',
            ])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('max_attendees')->nullable();

            // Counter caches (maintained by model observers)
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->unsignedInteger('total_reviews')->default(0);

            $table->timestamps();

            // Slug must be unique within an organization (not globally)
            $table->unique(['organization_id', 'slug'], 'unique_org_event_slug');

            // Hot path: public catalog — "show published upcoming events"
            $table->index(['status', 'start_date'], 'idx_events_status_date');
            $table->index('organization_id', 'idx_events_org');
            $table->index('category_id', 'idx_events_category');
            $table->index('is_featured', 'idx_events_featured');
            $table->index('start_date', 'idx_events_start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
