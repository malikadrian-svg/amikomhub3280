<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * Security: 'is_approved', 'admin_notes', 'user_id', and 'event_id' are
     * deliberately excluded from $fillable. They are set explicitly in the
     * controller or by admins only — never from user request data.
     */
    protected $fillable = [
        'rating',
        'title',
        'body',
    ];

    /**
     * The attributes that should be cast.
     */
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
     * Determine if this review can still be edited by its author.
     *
     * Reviews may be edited up to 7 days after creation. After that,
     * the review is considered final to maintain integrity.
     */
    public function isEditable(): bool
    {
        return $this->created_at->diffInDays(now()) <= 7;
    }

    /**
     * Return a human-readable relative time string.
     */
    public function timeAgo(): string
    {
        return $this->created_at->diffForHumans();
    }
}
