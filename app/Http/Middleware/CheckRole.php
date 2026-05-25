<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (empty($roles)) {
            return $next($request);
        }

        $userRole = Auth::user()->role ?? 'staff';

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized.');
    }
}