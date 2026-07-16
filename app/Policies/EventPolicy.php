<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('events.view');
    }

    public function view(User $user, Event $event): bool
    {
        return $user->hasPermission('events.view') && $this->belongsToActiveTenant($event);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('events.create');
    }

    public function update(User $user, Event $event): bool
    {
        return $user->hasPermission('events.edit') && $this->belongsToActiveTenant($event);
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->hasPermission('events.delete') && $this->belongsToActiveTenant($event);
    }
    
    public function approve(User $user, ?Event $event = null): bool
    {
        return $user->hasPermission('events.approve');
    }

    /**
     * Helper to ensure the event belongs to the currently active tenant context.
     * Prevents Horizontal Privilege Escalation.
     */
    private function belongsToActiveTenant(Event $event): bool
    {
        $tenantId = app(\App\Services\TenantContext::class)->getId();
        return $tenantId !== null && $event->organization_id === $tenantId;
    }
}
