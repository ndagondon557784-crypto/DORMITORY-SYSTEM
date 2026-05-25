<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check() && $request->isMethod('post', 'put', 'patch', 'delete')) {
            // Handled per-action in services/controllers
        }

        return $response;
    }
}