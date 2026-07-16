<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Organization;
use App\Services\TenantContext;

class EnsureOrganization
{
    /**
     * Handle an incoming request.
     *
     * This middleware sets up the TenantContext for organizer routes.
     * It expects an `organization` route parameter (ID or slug depending on binding).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $organization = $request->route('organization');

        if (!$organization) {
            abort(Response::HTTP_BAD_REQUEST, 'Organization route parameter missing.');
        }

        // If it's passed as an ID or Slug and implicit binding didn't resolve it yet
        if (!($organization instanceof Organization)) {
            $organization = Organization::where('id', $organization)
                ->orWhere('slug', $organization)
                ->firstOrFail();
        }

        // Check if the organization is active (approved)
        if (!$organization->isActive()) {
            abort(Response::HTTP_FORBIDDEN, 'This organization is not active.');
        }

        // Verify ownership (only the owner can access the organizer dashboard)
        if (\Illuminate\Support\Facades\Auth::id() !== $organization->owner_id) {
            abort(Response::HTTP_FORBIDDEN, 'You do not have permission to access this organization.');
        }

        // Set the context
        app(TenantContext::class)->set($organization);

        return $next($request);
    }
}
