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
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        if (empty($roles)) {
            return $next($request);
        }

        $roleList = array_values(array_filter(
            array_map(
                static fn (string $role): string => trim($role),
                array_filter(
                    array_merge(...array_map(
                        static fn (string $roleGroup): array => explode(',', $roleGroup),
                        $roles,
                    )),
                    static fn (string $role): bool => $role !== '',
                )
            ),
            static fn (string $role): bool => $role !== '',
        ));
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

