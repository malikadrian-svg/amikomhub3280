<?php

namespace App\Models\Traits;

use App\Models\Organization;
use App\Models\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * BelongsToOrganization trait.
 *
 * Automatically applies the OrganizationScope to the model, and
 * provides the organization() relationship.
 *
 * Use this on any model that should be tenant-isolated (e.g. Event, Order).
 */
trait BelongsToOrganization
{
    /**
     * Boot the trait and apply the global scope.
     */
    protected static function bootBelongsToOrganization(): void
    {
        static::addGlobalScope(new OrganizationScope());
    }

    /**
     * Relationship to the tenant organization.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
