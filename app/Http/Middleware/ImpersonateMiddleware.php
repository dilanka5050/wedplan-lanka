<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonateMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $impersonatingId = session('impersonating_user_id');

        if (!$impersonatingId) {
            return $next($request);
        }

        $impersonatedUser = User::find($impersonatingId);

        // If user no longer exists or has revoked access — kill session immediately
        if (!$impersonatedUser || !$impersonatedUser->wedding?->allow_admin_access) {
            session()->forget(['impersonating_user_id', 'impersonator_id']);
            return redirect()->route('admin.weddings')
                ->with('error', 'Access was revoked or the user no longer exists.');
        }

        // Swap the authenticated user for this request
        Auth::setUser($impersonatedUser);

        return $next($request);
    }
}
