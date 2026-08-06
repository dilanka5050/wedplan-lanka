<?php

namespace App\Http\Controllers;

use App\Models\SeatingAssignment;
use App\Models\SeatingTable;
use Illuminate\Http\Request;

class SeatingController extends Controller
{
    public function index(Request $request)
    {
        $wedding = $request->user()->wedding;
        $tables  = $wedding->seatingTables()->with(['guests.group', 'assignments'])->get();

        // Only accepted guests for assignment
        $acceptedGuests = $wedding->guests()
            ->with('group')
            ->where('rsvp_status', 'accepted')
            ->orderBy('name')
            ->get();

        // Guests not yet assigned to any table
        $unassigned = $acceptedGuests->filter(fn($g) => !$g->seatingAssignment);

        $stats = [
            'total_seats' => $tables->sum('capacity'),
            'seated'      => $tables->sum('seated_count'),
            'tables'      => $tables->count(),
            'unassigned'  => $unassigned->sum(fn($g) => max(1, (int) $g->rsvp_confirmed)),
        ];

        return view('seating.index', compact('tables', 'unassigned', 'stats'));
    }

    // Create a new table
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:100',
        ]);

        $request->user()->wedding->seatingTables()->create($validated);
        return redirect()->back()->with('success', 'Table added!');
    }

    // Update table name or capacity
    public function update(Request $request, SeatingTable $seatingTable)
    {
        $validated = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'capacity' => 'sometimes|integer|min:1|max:100',
        ]);

        // Prevent reducing capacity below current seated count
        if (isset($validated['capacity'])) {
            $seated = $seatingTable->assignments()->count();
            if ($validated['capacity'] < $seated) {
                return redirect()->back()->with('error', "Cannot reduce capacity below {$seated} (currently seated guests).");
            }
        }

        $seatingTable->update($validated);
        return redirect()->back()->with('success', 'Table updated!');
    }

    // Delete a table (unassigns all guests)
    public function destroy(SeatingTable $seatingTable)
    {
        $seatingTable->delete();
        return redirect()->back()->with('success', 'Table removed!');
    }

    // Assign a guest to a table
    public function assign(Request $request, SeatingTable $seatingTable)
    {
        $validated = $request->validate([
            'guest_id' => 'required|exists:guests,id',
        ]);

        $guest = \App\Models\Guest::findOrFail($validated['guest_id']);
        $partySize = max(1, (int) $guest->rsvp_confirmed);

        // Reload seated_count with guests relation
        $seatingTable->load('guests');

        if ($seatingTable->available_seats < $partySize) {
            return redirect()->back()->with('error', "Not enough seats. This guest has {$partySize} confirmed participant(s) but only {$seatingTable->available_seats} seat(s) available.");
        }

        // Remove existing assignment if any
        SeatingAssignment::where('guest_id', $validated['guest_id'])->delete();

        $seatingTable->assignments()->create(['guest_id' => $validated['guest_id']]);
        return redirect()->back()->with('success', 'Guest assigned!');
    }

    // Remove a guest from their table
    public function unassign(SeatingAssignment $seatingAssignment)
    {
        $seatingAssignment->delete();
        return redirect()->back()->with('success', 'Guest removed from table!');
    }
}
