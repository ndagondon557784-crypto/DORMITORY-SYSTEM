<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Block anyone who is not logged in OR does not have role = 'admin'.
     *
     * How it works:
     * 1. Check if a user is authenticated at all
     * 2. Check if that user's role column equals 'admin'
     * 3. If either check fails → 403 Forbidden
     * 4. If both pass → allow the request through
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Not logged in at all
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Logged in but not an admin
        if (! $request->user()->isAdmin()) {
            abort(403, 'Access denied. This area is for administrators only.');
        }

        return $next($request);
    }
}