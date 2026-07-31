<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorAuthenticationIsEnabled
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->is_admin && ! $user->hasEnabledTwoFactorAuthentication()) {
            return redirect()->route('profile.edit')
                ->with('status', 'two-factor-required');
        }

        return $next($request);
    }
}
