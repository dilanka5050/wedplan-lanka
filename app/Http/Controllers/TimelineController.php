<?php

namespace App\Http\Controllers;

use App\Models\Timeline;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index(Request $request)
    {
        $wedding = $request->user()->wedding;
        $timelines = $wedding->timelines()->orderBy('sort_order')->get();

        return view('timelines.index', compact('timelines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'nekath_time' => 'nullable|date_format:H:i',
        ]);

        $wedding = $request->user()->wedding;
        $validated['sort_order'] = $wedding->timelines()->count();
        $wedding->timelines()->create($validated);

        return redirect()->back()->with('success', 'Timeline event added!');
    }

    public function update(Request $request, Timeline $timeline)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'nekath_time' => 'nullable|date_format:H:i',
            'sort_order' => 'sometimes|integer',
        ]);

        $timeline->update($validated);
        return redirect()->back()->with('success', 'Timeline updated!');
    }

    public function destroy(Timeline $timeline)
    {
        $timeline->delete();
        return redirect()->back()->with('success', 'Timeline event removed!');
    }
}
