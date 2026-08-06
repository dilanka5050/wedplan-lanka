<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequiresWedding
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()) {
            return $next($request);
        }

        if (!$request->user()->wedding) {
            return redirect()->route('wedding.setup')
                ->with('error', 'Please set up your wedding workspace first.');
        }

        return $next($request);
    }
}
