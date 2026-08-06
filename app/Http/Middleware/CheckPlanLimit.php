<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPlanLimit
{
    public function handle(Request $request, Closure $next, string $type)
    {
        $user = $request->user();

        if ($user && $user->reachedLimit($type)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => "You've reached your plan limit for {$type}. Upgrade to Pro."], 403);
            }
            return redirect()->back()->with('error', "You've reached your plan limit for {$type}. Upgrade to Pro.");
        }

        return $next($request);
    }
}
