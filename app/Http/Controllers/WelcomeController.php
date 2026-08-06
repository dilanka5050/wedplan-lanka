<?php

namespace App\Http\Controllers;

use App\Models\Plan;

class WelcomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        $proPlan = Plan::where('slug', 'pro')->first();

        return view('welcome', compact('proPlan'));
    }
}
