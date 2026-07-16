<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_url',
    ];

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * All events organized by this partner.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * All reviews across all events organized by this partner.
     * Uses HasManyThrough: Partner → Events → Reviews
     */
    public function reviews(): HasManyThrough
    {
        return $this->hasManyThrough(Review::class, Event::class);
    }

    /**
     * Only approved reviews across all partner events.
     */
    public function approvedReviews(): HasManyThrough
    {
        return $this->hasManyThrough(Review::class, Event::class)
            ->where('reviews.is_approved', true);
    }

    // =========================================================================
    // Aggregate Helpers
    // =========================================================================

    /**
     * Average rating across all of this partner's events.
     * Returns a float rounded to 1 decimal, or null if no reviews.
     */
    public function averageRating(): ?float
    {
        $avg = $this->approvedReviews()->avg('reviews.rating');
        return $avg ? round((float) $avg, 1) : null;
    }

    /**
     * Total number of approved reviews across all partner events.
     */
    public function totalReviews(): int
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Rating distribution across all partner events.
     * Returns [5 => count, 4 => count, ..., 1 => count].
     */
    public function ratingDistribution(): array
    {
        $counts = $this->approvedReviews()
            ->selectRaw('reviews.rating, COUNT(*) as total')
            ->groupBy('reviews.rating')
            ->pluck('total', 'reviews.rating')
            ->toArray();

        $distribution = [];
        for ($star = 5; $star >= 1; $star--) {
            $distribution[$star] = $counts[$star] ?? 0;
        }

        return $distribution;
    }

    /**
     * Events that have already ended.
     */
    public function completedEvents(): HasMany
    {
        return $this->hasMany(Event::class)
            ->where('date', '<', now())
            ->orderBy('date', 'desc');
    }

    /**
     * Upcoming events (not yet happened).
     */
    public function upcomingEvents(): HasMany
    {
        return $this->hasMany(Event::class)
            ->where('date', '>=', now())
            ->orderBy('date', 'asc');
    }
}
