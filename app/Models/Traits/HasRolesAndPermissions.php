<?php

namespace App\Models\Traits;

use App\Models\Role;
use App\Models\Permission;

/**
 * HasRolesAndPermissions trait for the User model.
 */
trait HasRolesAndPermissions
{
    /**
     * Check if user has a specific platform-level role.
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->roles->pluck('slug')->contains($roleSlug);
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roleSlugs): bool
    {
        return $this->roles->pluck('slug')->intersect($roleSlugs)->isNotEmpty();
    }

    /**
     * Check if user has a specific permission.
     * Checks both platform-level roles and organization-scoped roles (if tenant context is active).
     */
    public function hasPermission(string $permissionSlug): bool
    {
        // 1. Check platform roles (e.g. super_admin)
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permissionSlug)) {
                return true;
            }
        }

        // 2. Check organization role if TenantContext is resolved
        $tenantContext = app(\App\Services\TenantContext::class);
        if ($tenantContext->isResolved()) {
            $orgId = $tenantContext->getId();
            
            // Get the user's role in this organization
            $orgRoleSlug = $this->organizationRole($orgId);
            
            if ($orgRoleSlug) {
                // For instance, 'owner' -> 'organizer_owner'
                $mappedRoleSlug = 'organizer_' . $orgRoleSlug;
                
                // Fetch the platform role definition for this org role
                // Cache this in practice, but for now querying or loading is fine.
                // We'll load the role and check permissions
                $orgRole = Role::where('slug', $mappedRoleSlug)->with('permissions')->first();
                
                if ($orgRole && $orgRole->hasPermission($permissionSlug)) {
                    return true;
                }
            }
        }

        return false;
    }
}
