<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Models\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $fillable = [
        'organization_id',
        'category_id',
        'approved_by',
        'title',
        'slug',
        'description',
        'short_description',
        'start_date',
        'end_date',
        'location',
        'venue_name',
        'poster_path',
        'status',
        'rejection_reason',
        'published_at',
        'approved_at',
        'is_featured',
        'max_attendees',
    ];

    protected function casts(): array
    {
        return [
            'start_date'   => 'datetime',
            'end_date'     => 'datetime',
            'published_at' => 'datetime',
            'approved_at'  => 'datetime',
            'is_featured'  => 'boolean',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * The organization that created this event (tenant owner).
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Backward-compat alias for views/controllers still using ->partner.
     * Remove after M3/M4 when organizer UI is fully live.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Pricing tiers for this event (replaces single price/stock).
     */
    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class)->orderBy('sort_order');
    }

    /**
     * Active ticket types available for purchase.
     */
    public function activeTicketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * All orders for this event.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Legacy: transactions (M1 compat — remove after M6 checkout refactor).
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * All reviews for this event.
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

    /**
     * Approval audit log.
     */
    public function approvalLogs(): HasMany
    {
        return $this->hasMany(EventApprovalLog::class)->orderBy('created_at', 'desc');
    }

    /**
     * Digital tickets issued for this event.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // =========================================================================
    // Rating Helpers (served from counter cache — no live aggregate query)
    // =========================================================================

    /**
     * Returns cached average rating, or null if no reviews.
     */
    public function averageRating(): ?float
    {
        return $this->average_rating > 0
            ? round((float) $this->average_rating, 1)
            : null;
    }

    /**
     * Returns cached review count.
     */
    public function reviewCount(): int
    {
        return $this->total_reviews;
    }

    /**
     * Live rating distribution (computed on event detail page only).
     */
    public function ratingDistribution(): array
    {
        $counts = $this->approvedReviews()
            ->selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        $distribution = [];
        for ($star = 5; $star >= 1; $star--) {
            $distribution[$star] = $counts[$star] ?? 0;
        }

        return $distribution;
    }

    // =========================================================================
    // Status Helpers
    // =========================================================================

    public function isDraft(): bool           { return $this->status === 'draft'; }
    public function isPendingReview(): bool   { return $this->status === 'pending_review'; }
    public function isApproved(): bool        { return $this->status === 'approved'; }
    public function isPublished(): bool       { return $this->status === 'published'; }
    public function isCompleted(): bool       { return $this->status === 'completed'; }
    public function isArchived(): bool        { return $this->status === 'archived'; }
    public function isRejected(): bool        { return $this->status === 'rejected'; }

    /**
     * Has this event already ended?
     */
    public function isFinished(): bool
    {
        return Carbon::parse($this->start_date)->isPast();
    }

    /**
     * Can reviews be submitted for this event?
     * Reviews open ONE DAY after the event ends.
     */
    public function isReviewable(): bool
    {
        return now()->greaterThanOrEqualTo(
            Carbon::parse($this->start_date)->addDay()->startOfDay()
        );
    }

    // =========================================================================
    // Pricing Helpers (for backward compat during M1 — remove after M6)
    // =========================================================================

    /**
     * Returns the lowest active ticket price.
     * Used during M1 where views may still reference event->price.
     */
    public function getLowestPrice(): ?int
    {
        return $this->activeTicketTypes()->min('price');
    }

    /**
     * Total remaining tickets across all active types.
     */
    public function getTotalStock(): int
    {
        return $this->activeTicketTypes()
            ->get()
            ->sum(fn (TicketType $t) => $t->remaining());
    }

    /**
     * Is the event completely sold out?
     */
    public function isSoldOut(): bool
    {
        return $this->getTotalStock() <= 0;
    }

    // =========================================================================
    // Static Helpers
    // =========================================================================

    /**
     * Generate a URL-safe slug from a title.
     */
    public static function generateSlug(string $title): string
    {
        return Str::slug($title);
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
