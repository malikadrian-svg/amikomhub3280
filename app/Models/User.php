<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
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
