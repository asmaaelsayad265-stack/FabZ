<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Authenticate via Sanctum personal access token
        if (!$request->user()) {
            abort(401, 'Unauthenticated.');
        }

        return $next($request);
    }
}

