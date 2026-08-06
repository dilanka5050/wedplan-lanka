<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $notes = $request->user()->wedding
            ->notes()
            ->latest()
            ->get();

        return view('notes.index', compact('notes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string|max:5000',
        ]);

        $request->user()->wedding->notes()->create($validated);

        return redirect()->back()->with('success', 'Note added!');
    }

    public function update(Request $request, Note $note)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string|max:5000',
        ]);

        $note->update($validated);

        return redirect()->back()->with('success', 'Note updated!');
    }

    public function destroy(Note $note)
    {
        $note->delete();

        return redirect()->back()->with('success', 'Note deleted!');
    }
}
