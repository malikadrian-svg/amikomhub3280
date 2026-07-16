<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'logo_path',
        'banner_path',
        'description',
        'email',
        'phone',
        'website',
        'address',
        'social_media',
        'status',
        'rejection_reason',
        'commission_rate',
    ];

    protected function casts(): array
    {
        return [
            'social_media'  => 'array',
            'approved_at'   => 'datetime',
            'average_rating' => 'float',
            'commission_rate' => 'float',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * The user who owns/registered this organization.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * The super admin who approved this organization.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * All members of this organization (via organization_user pivot).
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    /**
     * All events this organization has created.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Only published events (visible on public catalog).
     */
    public function publishedEvents(): HasMany
    {
        return $this->hasMany(Event::class)
            ->where('status', 'published')
            ->orderBy('start_date', 'asc');
    }

    /**
     * Events that have already completed.
     */
    public function completedEvents(): HasMany
    {
        return $this->hasMany(Event::class)
            ->where('status', 'completed')
            ->orderBy('start_date', 'desc');
    }

    /**
     * All reviews across all org events (via events).
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'organization_id');
    }

    /**
     * Only approved (public) reviews across all org events.
     */
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'organization_id')
            ->where('is_approved', true);
    }

    /**
     * All orders placed for this organization's events.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Commission records for this organization.
     */
    public function commissions(): HasMany
    {
        return $this->hasMany(OrderCommission::class);
    }

    /**
     * Verification documents uploaded during registration.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(OrganizationDocument::class);
    }

    // =========================================================================
    // Status Helpers
    // =========================================================================

    public function isPending(): bool    { return $this->status === 'pending'; }
    public function isApproved(): bool   { return $this->status === 'approved'; }
    public function isRejected(): bool   { return $this->status === 'rejected'; }
    public function isSuspended(): bool  { return $this->status === 'suspended'; }
    public function isActive(): bool     { return $this->status === 'approved'; }

    // =========================================================================
    // Rating Aggregates (served from counter cache columns, not live queries)
    // =========================================================================

    /**
     * Average rating (from counter cache on organizations.average_rating).
     */
    public function averageRating(): ?float
    {
        return $this->average_rating > 0
            ? round($this->average_rating, 1)
            : null;
    }

    /**
     * Total approved reviews (from counter cache on organizations.total_reviews).
     */
    public function totalReviews(): int
    {
        return $this->total_reviews;
    }

    /**
     * Live rating distribution (computed query — only called on profile page).
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
    // Scopes
    // =========================================================================

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
