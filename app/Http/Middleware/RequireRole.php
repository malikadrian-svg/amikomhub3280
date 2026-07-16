<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Split roles if passed as a pipe-separated string (e.g., 'super_admin|customer')
        $rolesArray = [];
        foreach ($roles as $role) {
            $rolesArray = array_merge($rolesArray, explode('|', $role));
        }

        if (!$user->hasAnyRole($rolesArray)) {
            abort(Response::HTTP_FORBIDDEN, 'You do not have the required role to access this resource.');
        }

        return $next($request);
    }
}
