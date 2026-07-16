<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * @param  array<string>  $roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        if (empty($roles)) {
            return $next($request);
        }

        // Spatie role checking
        if (!$user->hasAnyRole($roles)) {
            abort(403, 'Forbidden.');
        }

        return $next($request);
    }
}

