<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (! Auth::guard($guard)->check()) {
                continue;
            }

            $user = Auth::guard($guard)->user();

            if ($user->isSuperAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->isPelamar()) {
                return redirect()->route('pelamar.dashboard');
            }

            return redirect()->route('business.dashboard');
        }

        return $next($request);
    }
}
