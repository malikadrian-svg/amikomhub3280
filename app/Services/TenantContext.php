<?php

namespace App\Services;

use App\Models\Organization;

/**
 * TenantContext — request-scoped singleton that carries the current organization.
 *
 * Registered as a singleton in AppServiceProvider so every injection
 * within the same HTTP request receives the same instance.
 *
 * Resolution lifecycle:
 *  - Public routes:       not set → getId() returns null
 *  - Admin routes:        not set → super admin sees all data (no scope applied)
 *  - Organizer routes:    EnsureOrganization middleware calls set() → org queries scoped
 *
 * The OrganizationScope global scope reads getId() and only applies a WHERE clause
 * when a non-null value is present. This makes the scoping entirely opt-in per route.
 */
class TenantContext
{
    private ?int          $organizationId   = null;
    private ?Organization $organization     = null;

    // =========================================================================
    // Setters
    // =========================================================================

    /**
     * Set the current tenant using a fully-loaded Organization model.
     */
    public function set(Organization $organization): void
    {
        $this->organization   = $organization;
        $this->organizationId = $organization->id;
    }

    /**
     * Set the current tenant by ID only (model loaded lazily on first get()).
     */
    public function setById(int $organizationId): void
    {
        $this->organizationId = $organizationId;
        $this->organization   = null; // will be lazy-loaded
    }

    /**
     * Clear the tenant context (useful for testing or super-admin impersonation).
     */
    public function clear(): void
    {
        $this->organizationId = null;
        $this->organization   = null;
    }

    // =========================================================================
    // Getters
    // =========================================================================

    /**
     * Get the current organization ID, or null if no tenant is active.
     * This is the primary value read by OrganizationScope.
     */
    public function getId(): ?int
    {
        return $this->organizationId;
    }

    /**
     * Get the full Organization model (lazy-loads if only ID was set).
     */
    public function get(): ?Organization
    {
        if ($this->organization === null && $this->organizationId !== null) {
            $this->organization = Organization::find($this->organizationId);
        }

        return $this->organization;
    }

    /**
     * Returns true only when a tenant has been explicitly resolved.
     */
    public function isResolved(): bool
    {
        return $this->organizationId !== null;
    }
}
