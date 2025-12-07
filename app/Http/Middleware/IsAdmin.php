<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // IsAdmin middleware has been disabled project-wide per user request.
        // Kept as a no-op to avoid removing autoload mappings.
        return $next($request);
    }
}
