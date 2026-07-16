<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Partner — backward-compatibility alias for Organization.
 *
 * The `partners` table has been renamed to `organizations`.
 * This model bridges all existing code that still references `Partner`
 * (HomeController, PartnerProfileController, routes, views) so they
 * continue to work without modification during M1.
 *
 * Scheduled for removal in Milestone 3 once the Organizer Dashboard
 * and Organization Registration are live and all references are migrated.
 *
 * @deprecated Use Organization instead. Remove after M3.
 */
class Partner extends Organization
{
    use HasFactory;

    /**
     * Explicitly point to the organizations table.
     * (Inherited from Organization, but stated here for clarity.)
     */
    protected $table = 'organizations';

    /**
     * The route key for implicit model binding.
     * GET /partners/{partner} resolves by `id` from organizations table.
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
