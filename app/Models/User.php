<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * Switched from PHP attribute syntax to $fillable property because
     * we are adding OAuth fields that need to be mass-assigned during
     * the find-or-create flow in GoogleController.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'avatar',
        'provider',
        'provider_id',
        'phone',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * A user can own many transactions (tickets they purchased).
     * Used in "My Tickets" to retrieve all tickets belonging to this user.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * All reviews written by this user.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // =========================================================================
    // Review Helper Methods
    // =========================================================================

    /**
     * Check whether this user has already reviewed a specific event.
     *
     * @param int $eventId
     */
    public function hasReviewedEvent(int $eventId): bool
    {
        return $this->reviews()->where('event_id', $eventId)->exists();
    }

    /**
     * Retrieve this user's review for a specific event (or null if none).
     *
     * @param int $eventId
     */
    public function reviewForEvent(int $eventId): ?Review
    {
        return $this->reviews()->where('event_id', $eventId)->first();
    }

    /**
     * Determine whether this user is eligible to leave a review for an event.
     *
     * All conditions must be true:
     *  1. User has a paid transaction for the event
     *  2. The event has ended AND 1 day has passed (isReviewable)
     *  3. User has not already reviewed the event
     *
     * @param Event $event
     */
    public function canReviewEvent(Event $event): bool
    {
        if (! $event->isReviewable()) {
            return false;
        }

        if ($this->hasReviewedEvent($event->id)) {
            return false;
        }

        return $this->transactions()
            ->where('event_id', $event->id)
            ->whereIn('status', ['success', 'settlement', 'capture'])
            ->exists();
    }

    // =========================================================================
    // Helper Methods
    // =========================================================================

    /**
     * Determine if this user authenticated via OAuth (has no password).
     */
    public function isOAuthUser(): bool
    {
        return is_null($this->password) && !is_null($this->google_id);
    }

    /**
     * Determine if this user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
