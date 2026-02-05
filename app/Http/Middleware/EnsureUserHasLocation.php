<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasLocation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if ($request->user()) {
            $user = $request->user();
            
            // Check if user has location set
            if (empty($user->latitude) || empty($user->longitude)) {
                // Redirect to profile with a session flash to show location modal
                if (!$request->is('*/profile*')) {
                    return redirect()->route('profile.index')->with('show_location_modal', true);
                }
            }
        }
        
        return $next($request);
    }
}
