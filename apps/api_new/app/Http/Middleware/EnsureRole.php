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
    public function handle(Request $request, Closure $next, string $roles = ''): Response
    {
        $user = $request->user();
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        if (empty($roles)) {
            return $next($request);
        }

        $roleList = array_values(array_filter(explode(',', $roles), fn ($r) => !is_null($r) && $r !== ''));
        if (empty($roleList)) {
            return $next($request);
        }

        \Illuminate\Support\Facades\Log::info('EnsureRole middleware', ['roles' => $roleList, 'user_id' => $user->id]);

        // Spatie role checking
        if (!$user->hasAnyRole($roleList)) {
            abort(403, 'Forbidden.');
        }

        return $next($request);
    }
}

