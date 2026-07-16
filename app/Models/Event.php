<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'category_id',
        'partner_id',   // Added: links event to its organizer
        'title',
        'description',
        'date',
        'location',
        'price',
        'stock',
        'poster_path',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The partner (organizer) hosting this event.
     * Nullable — not all events have an assigned partner.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * All tickets (transactions) sold for this event.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * All reviews left for this event.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Only approved (publicly visible) reviews.
     */
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    // =========================================================================
    // Rating Helpers
    // =========================================================================

    /**
     * Compute the average rating for this event from approved reviews.
     * Returns a float rounded to 1 decimal, or null if no reviews exist.
     */
    public function averageRating(): ?float
    {
        $avg = $this->approvedReviews()->avg('rating');
        return $avg ? round((float) $avg, 1) : null;
    }

    /**
     * Count of approved reviews for this event.
     */
    public function reviewCount(): int
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Return the rating distribution as an array [5 => count, 4 => count, ...].
     * Used to render the rating breakdown bar chart.
     */
    public function ratingDistribution(): array
    {
        $counts = $this->approvedReviews()
            ->selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        // Ensure all 5 stars are represented (even if count is 0)
        $distribution = [];
        for ($star = 5; $star >= 1; $star--) {
            $distribution[$star] = $counts[$star] ?? 0;
        }

        return $distribution;
    }

    // =========================================================================
    // Status Helpers
    // =========================================================================

    /**
     * Has this event already ended (past its date)?
     */
    public function isFinished(): bool
    {
        return Carbon::parse($this->date)->isPast();
    }

    /**
     * Can reviews be submitted for this event?
     * Reviews open ONE DAY after the event ends.
     *
     * Example:
     *   Event date: 20 July 2026
     *   Review opens: 21 July 2026 (at any time)
     */
    public function isReviewable(): bool
    {
        return now()->greaterThanOrEqualTo(
            Carbon::parse($this->date)->addDay()->startOfDay()
        );
    }
}
