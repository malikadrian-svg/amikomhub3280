<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('organizers.approve'); // super admin
    }

    public function view(User $user, Organization $organization): bool
    {
        return $user->hasPermission('organizers.approve') || $user->organizations->contains($organization);
    }

    public function create(User $user): bool
    {
        return true; // Any authenticated user can submit a registration
    }

    public function update(User $user, Organization $organization): bool
    {
        // Only if tenant context matches or super admin
        return $user->hasPermission('organizers.approve') || 
               ($user->organizations->contains($organization) && $user->hasPermission('organization.settings'));
    }

    public function delete(User $user, Organization $organization): bool
    {
        return $user->hasPermission('organizers.suspend');
    }
}
