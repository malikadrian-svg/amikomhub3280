<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Role model for the platform RBAC system.
 *
 * Seeded roles (is_system = true — cannot be deleted):
 *   super_admin, organizer_owner, organizer_manager, organizer_staff, customer
 *
 * Note: Organization-scoped roles (owner/manager/staff) live in the
 * organization_user pivot. This table holds platform-level roles only.
 */
class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_system',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    /**
     * Check if this role has a specific permission slug.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        return $this->permissions->pluck('slug')->contains($permissionSlug);
    }
}
