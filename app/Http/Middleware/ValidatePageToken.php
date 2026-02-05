<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ValidatePageToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->query('token');

        if (!$token || !Cache::has($token)) {
            return response('Unauthorized', Response::HTTP_FORBIDDEN);
        }

        $tokenData = Cache::get($token);

        // Verify token data
        if (
            $tokenData['user_id'] !== auth()->id() ||
            $tokenData['page'] !== (int) $request->route('page') ||
            $tokenData['expires_at'] < now()
        ) {
            return response('Unauthorized', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
