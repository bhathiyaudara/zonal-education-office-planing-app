<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Super Admin has full control and access to all options
        if (auth()->user()->isSuperAdmin()) {
            return $next($request);
        }

        if (!in_array(auth()->user()->role, $roles)) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}
