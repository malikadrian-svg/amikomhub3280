<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\BelongsToOrganization;

class Review extends Model
{
    use BelongsToOrganization;

    /**
     * Security: 'is_approved', 'admin_notes', 'user_id', 'event_id',
     * and 'organization_id' are excluded from $fillable.
     * They are set explicitly in the controller — never from user payload.
     */
    protected $fillable = [
        'rating',
        'title',
        'body',
    ];

    protected function casts(): array
    {
        return [
            'rating'      => 'integer',
            'is_approved' => 'boolean',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * The user who wrote this review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The event being reviewed.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Denormalized organization link for efficient organizer dashboard queries.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    /**
     * Only return reviews that are publicly visible.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // =========================================================================
    // Helper Methods
    // =========================================================================

    /**
     * Reviews can be edited up to 7 days after submission.
     */
    public function isEditable(): bool
    {
        return $this->created_at->diffInDays(now()) <= 7;
    }

    /**
     * Human-readable relative time string.
     */
    public function timeAgo(): string
    {
        return $this->created_at->diffForHumans();
    }
}
