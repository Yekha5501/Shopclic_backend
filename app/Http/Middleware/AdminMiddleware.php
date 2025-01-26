<?php

// app/Http/Middleware/AdminMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and is an admin
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request); // Allow access
        }

        // Redirect non-admin users to a different route (e.g., home)
        return redirect('/')->with('error', 'You do not have permission to access this page.');
    }
}