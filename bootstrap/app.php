<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\ImpersonateMiddleware::class,
        ]);
        $middleware->alias([
            'admin'            => \App\Http\Middleware\AdminMiddleware::class,
            'check.feature'    => \App\Http\Middleware\CheckPlanFeature::class,
            'check.limit'      => \App\Http\Middleware\CheckPlanLimit::class,
            'requires.wedding' => \App\Http\Middleware\RequiresWedding::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login')
                ->with('error', 'Your session has expired. Please log in again.');
        });

        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Session expired. Please refresh and try again.'], 419);
            }
            return redirect()->route('login')
                ->with('error', 'Your session has expired. Please log in again.');
        });

        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

            if ($status === 419 && view()->exists('errors.419')) {
                return response()->view('errors.419', [], 419);
            }
            if ($status === 404 && view()->exists('errors.404')) {
                return response()->view('errors.404', [], 404);
            }
            if ($status >= 500 && view()->exists('errors.500')) {
                return response()->view('errors.500', [], $status);
            }

            return null;
        });
    })->create();
