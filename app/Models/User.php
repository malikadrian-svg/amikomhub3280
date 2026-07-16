<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\HasRolesAndPermissions;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRolesAndPermissions;

    /**
     * Switched from PHP attribute syntax to $fillable property during OAuth feature.
     * OAuth fields (google_id, avatar, provider, provider_id) are intentionally
     * mass-assignable for the find-or-create flow in GoogleController.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'provider',
        'provider_id',
        'phone',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // =========================================================================
    // Relationships — Commerce
    // =========================================================================

    /**
     * All orders placed by this user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Digital tickets owned by this user.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Legacy transactions (pre-M6; kept for backward compat with My Tickets).
     * TODO: Migrate to orders() after M6 checkout refactor.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    // =========================================================================
    // Relationships — RBAC (M2: populated after RBAC middleware is live)
    // =========================================================================

    /**
     * Platform-level roles (super_admin, customer).
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Organizations this user is a member of.
     */
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_user')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    /**
     * Organizations where this user is the owner.
     */
    public function ownedOrganizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'owner_id');
    }

    // =========================================================================
    // Relationships — Reviews (existing ✅)
    // =========================================================================

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // =========================================================================
    // Review Helper Methods (existing ✅ — unchanged)
    // =========================================================================

    public function hasReviewedEvent(int $eventId): bool
    {
        return $this->reviews()->where('event_id', $eventId)->exists();
    }

    public function reviewForEvent(int $eventId): ?Review
    {
        return $this->reviews()->where('event_id', $eventId)->first();
    }

    /**
     * All conditions:
     *  1. Event is reviewable (ended + 1 day grace period)
     *  2. User has NOT already reviewed
     *  3. User has a paid order for this event
     */
    public function canReviewEvent(Event $event): bool
    {
        if (! $event->isReviewable()) {
            return false;
        }

        if ($this->hasReviewedEvent($event->id)) {
            return false;
        }

        return $this->orders()
            ->where('event_id', $event->id)
            ->whereIn('status', ['paid', 'completed'])
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
        return is_null($this->password) && ! is_null($this->google_id);
    }

    /**
     * Check org-scoped membership role.
     */
    public function organizationRole(int $organizationId): ?string
    {
        return $this->organizations()
            ->wherePivot('organization_id', $organizationId)
            ->first()
            ?->pivot
            ?->role;
    }

    /**
     * Is this user an owner, manager, or staff of any organization?
     */
    public function isOrganizer(): bool
    {
        return $this->organizations()->exists();
    }
}
