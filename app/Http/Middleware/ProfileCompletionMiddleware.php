<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileCompletionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Skip profile completion check for admins
            if ($user->role === 'Administrator') {
                return $next($request);
            }
            
            // If profile is not complete and user is not already on profile page, redirect
            if (!$user->isProfileComplete() && !$request->routeIs('profile') && !$request->routeIs('profile.update')) {
                return redirect()->route('profile')->with('warning', 'Please complete your profile information before accessing this feature.');
            }
        }

        return $next($request);
    }
} 