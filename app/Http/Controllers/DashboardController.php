<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $wedding = $user->wedding;

        $data = [
            'wedding' => $wedding,
            'progress' => $wedding?->progress ?? 0,
            'upcomingTasks' => $wedding?->tasks()
                ->where('status', '!=', 'completed')
                ->orderBy('due_date')
                ->limit(5)
                ->get() ?? collect(),
            'budgetSummary' => $wedding?->budget_summary ?? ['estimated' => 0, 'actual' => 0, 'remaining' => 0],
            'guestCount' => $wedding?->guests()->count() ?? 0,
            'daysUntil' => $wedding?->days_until_wedding,
        ];

        return view('dashboard', $data);
    }
}
