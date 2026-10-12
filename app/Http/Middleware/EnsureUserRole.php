<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! in_array($user->role, $roles, true)) {
            // BioTag Super Admin has oversight access
            if ($user->isBioTagSuperAdmin()) {
                return $next($request);
            }

            abort(403, 'Akses ditolak: Anda tidak memiliki kewenangan untuk area ini.');
        }

        return $next($request);
    }
}
