<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The reviews table is the heart of the rating system.
     *
     * Design decisions:
     * - Combined rating + review in one table (no separate ratings table) because
     *   the two pieces of data are inseparable and always queried together.
     * - UNIQUE(user_id, event_id) enforces the "one review per user per event"
     *   business rule at the database level — this is the final safety net beyond
     *   application-level Policy checks.
     * - is_approved defaults to TRUE (auto-publish), but allows admin moderation.
     * - Composite indexes are placed to serve the most frequent query patterns:
     *   (a) fetching all approved reviews for an event (sorted by date)
     *   (b) computing average rating for an event or partner
     *   (c) fetching a user's review for a specific event
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // The user who wrote this review.
            // CASCADE: if user is deleted, their reviews are deleted (GDPR-friendly).
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // The event being reviewed.
            // CASCADE: if event is deleted, its reviews are deleted.
            $table->foreignId('event_id')
                ->constrained('events')
                ->cascadeOnDelete();

            // Star rating from 1 to 5.
            $table->tinyInteger('rating')->unsigned();

            // Optional short title for the review (e.g. "Luar biasa!", "Mengecewakan").
            $table->string('title', 100)->nullable();

            // The review body text. Min/max enforced at the application layer.
            $table->text('body');

            // Moderation flag. TRUE = visible to public. Defaults to auto-publish.
            // Admin can set to FALSE to hide inappropriate content without deleting.
            $table->boolean('is_approved')->default(true);

            // Admin can record why a review was hidden (internal only, not shown to user).
            $table->text('admin_notes')->nullable();

            $table->timestamps();

            // ─── Business Rule: One review per user per event ─────────────────────
            $table->unique(['user_id', 'event_id'], 'unique_user_event_review');

            // ─── Indexes for performance ──────────────────────────────────────────

            // Used by: event detail page listing (approved reviews, newest first)
            $table->index(
                ['event_id', 'is_approved', 'created_at'],
                'idx_reviews_event_approved_date'
            );

            // Used by: AVG(rating) and rating distribution aggregation per event
            $table->index(
                ['event_id', 'rating'],
                'idx_reviews_event_rating'
            );

            // Used by: checking if a user has already reviewed a specific event
            // Also powers: user's review history in "My Reviews" (future feature)
            $table->index(
                ['user_id', 'event_id'],
                'idx_reviews_user_event'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
