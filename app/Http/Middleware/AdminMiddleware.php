<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect('api/login'); // Redirect unauthenticated users to the login page
        }

        if (!auth()->user()->isAdmin()) {
            return abort(403, 'Unauthorized'); // Return a 403 Forbidden response for non-admin users
        }

        return $next($request);
    }
}
