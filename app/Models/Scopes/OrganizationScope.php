<?php

namespace App\Models\Scopes;

use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * OrganizationScope — global Eloquent scope for tenant data isolation.
 *
 * Applied to models that use the BelongsToOrganization trait.
 * The scope is conditional: it ONLY adds a WHERE clause when TenantContext
 * has been explicitly resolved (i.e., on organizer routes).
 *
 * Behavior by route context:
 *  - Public routes (no EnsureOrganization middleware):
 *      TenantContext::getId() = null → NO WHERE added → all records visible
 *
 *  - Super Admin routes (no EnsureOrganization middleware):
 *      TenantContext::getId() = null → NO WHERE added → all records visible
 *
 *  - Organizer routes (EnsureOrganization middleware sets context):
 *      TenantContext::getId() = 3 → WHERE organization_id = 3 applied automatically
 *
 * This means organizers CANNOT see other organizations' data even if they
 * manually craft URLs — the scope enforces it at the Eloquent layer.
 */
class OrganizationScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $orgId = app(TenantContext::class)->getId();

        if ($orgId !== null) {
            $builder->where(
                $model->getTable() . '.organization_id',
                $orgId
            );
        }
    }
}
