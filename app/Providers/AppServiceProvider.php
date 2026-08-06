<?php

namespace App\Providers;

use App\Models\Plan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Share Pro plan data globally with all views
        View::composer('layouts.app', function ($view) {
            $proPlan = Plan::where('slug', 'pro')->first();
            $view->with('proPlan', $proPlan);
        });
    }
}
