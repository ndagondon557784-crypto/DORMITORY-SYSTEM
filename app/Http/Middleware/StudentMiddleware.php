<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    /**
     * Block anyone who is not a student.
     * Admins who try to access student-only routes will be blocked.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! $request->user()->isStudent()) {
            abort(403, 'Access denied. This area is for students only.');
        }

        return $next($request);
    }
}