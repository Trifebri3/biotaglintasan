<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->status === 'pending_verification') {
            if (! $request->routeIs('user.pending') && ! $request->routeIs('logout') && ! $request->routeIs('user.submit-school-code')) {
                return redirect()->route('user.pending');
            }
        }

        return $next($request);
    }
}
