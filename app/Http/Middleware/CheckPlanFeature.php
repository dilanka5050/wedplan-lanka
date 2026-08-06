<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPlanFeature
{
    public function handle(Request $request, Closure $next, string $feature)
    {
        $user = $request->user();

        if (!$user || !$user->canAccessFeature($feature)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Upgrade to Pro to access this feature.'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'Upgrade to Pro to access this feature.');
        }

        return $next($request);
    }
}
