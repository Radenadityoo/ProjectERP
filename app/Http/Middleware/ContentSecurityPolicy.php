<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Add/override CSP header for responses that support headers
        if (method_exists($response, 'header')) {
            $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-eval' 'unsafe-inline' https:; connect-src 'self' http://localhost:5173 ws://localhost:5173; style-src 'self' 'unsafe-inline' https:; img-src 'self' data:; font-src 'self' data:");
        }

        return $response;
    }
}
