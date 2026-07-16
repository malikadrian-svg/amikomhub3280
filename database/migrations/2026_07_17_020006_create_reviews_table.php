<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The reviews table is extended from the existing implementation with one
     * addition: organization_id is denormalized for efficient organizer dashboard
     * queries ("show all reviews for my org") without a JOIN through events.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // CASCADE: GDPR-friendly — deleting user removes their reviews
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('event_id')
                ->constrained('events')
                ->cascadeOnDelete();

            // Denormalized from events.organization_id for organizer dashboard queries
            $table->foreignId('organization_id')
                ->nullable()
                ->constrained('organizations')
                ->cascadeOnDelete();

            $table->tinyInteger('rating')->unsigned();     // 1–5
            $table->string('title', 100)->nullable();
            $table->text('body');

            // Moderation: true = public, false = hidden by admin
            $table->boolean('is_approved')->default(true);
            $table->text('admin_notes')->nullable();

            $table->timestamps();

            // Enforce one review per user per event at the database level
            $table->unique(['user_id', 'event_id'], 'unique_user_event_review');

            // Hot path: approved reviews for an event, sorted by date
            $table->index(['event_id', 'is_approved', 'created_at'], 'idx_reviews_event_approved_date');

            // AVG(rating) aggregation per event
            $table->index(['event_id', 'rating'], 'idx_reviews_event_rating');

            // Has user reviewed this event? + user review history
            $table->index(['user_id', 'event_id'], 'idx_reviews_user_event');

            // Organizer dashboard: all reviews for this org
            $table->index(['organization_id', 'is_approved'], 'idx_reviews_org');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
