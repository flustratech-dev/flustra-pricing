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
        // Check if user is authenticated and is an admin
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        return abort(403, 'Unauthorized. Admins only.');
    }
}
