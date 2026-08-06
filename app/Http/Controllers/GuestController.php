<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\GuestGroup;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $user    = $request->user();
        $wedding = $user->wedding;
        $groups  = GuestGroup::all();
        $hasSeparateLists = $user->canAccessFeature('separate_guest_lists');

        $allGuests   = $wedding->guests()->with('group')->orderBy('name')->get();
        $weddingPast = $wedding->wedding_date && $wedding->wedding_date->isPast();

        // Bride & Groom side groups (by slug)
        $brideGroupIds = GuestGroup::where('slug', 'bride-side')->pluck('id');
        $groomGroupIds = GuestGroup::where('slug', 'groom-side')->pluck('id');

        $brideGuests = $hasSeparateLists
            ? $allGuests->whereIn('guest_group_id', $brideGroupIds->toArray())
            : collect();

        $groomGuests = $hasSeparateLists
            ? $allGuests->whereIn('guest_group_id', $groomGroupIds->toArray())
            : collect();

        $stats = [
            'total_invitations' => $allGuests->count(),
            'total_invited'     => $allGuests->sum('total_invited'),
            'total_confirmed'   => $allGuests->sum('rsvp_confirmed'),
            'pending'           => $allGuests->filter(fn($g) => $g->rsvp_status->value === 'pending')->count(),
            'bride_count'       => $brideGuests->sum('total_invited'),
            'groom_count'       => $groomGuests->sum('total_invited'),
        ];

        $guestLimit = $user->getLimit('guests');
        $guestCount = $allGuests->count();
        $guestPct   = $guestLimit > 0 ? min(100, round(($guestCount / $guestLimit) * 100)) : 0;

        return view('guests.index', compact(
            'allGuests', 'brideGuests', 'groomGuests',
            'groups', 'wedding', 'stats', 'hasSeparateLists', 'weddingPast',
            'guestLimit', 'guestCount', 'guestPct'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'guest_group_id'  => 'required|exists:guest_groups,id',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:20',
            'invitation_type' => 'required|in:single,couple,family',
            'total_invited'   => 'required|integer|min:1|max:20',
            'notes'           => 'nullable|string|max:500',
        ]);

        $request->user()->wedding->guests()->create($validated);
        return redirect()->back()->with('success', 'Guest added successfully!');
    }

    public function update(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'rsvp_status'    => 'sometimes|in:pending,accepted,declined,maybe',
            'rsvp_confirmed'  => 'sometimes|integer|min:0|max:' . $guest->total_invited,
            'gifted_amount'   => 'sometimes|nullable|numeric|min:0|max:9999999',
            'name'            => 'sometimes|string|max:255',
            'total_invited'  => 'sometimes|integer|min:1|max:20',
            'notes'          => 'nullable|string|max:500',
        ]);

        if (isset($validated['rsvp_status']) && $validated['rsvp_status'] === 'declined') {
            $validated['rsvp_confirmed'] = 0;
        }

        // Single-type guests: auto-set confirmed=1 on accept, 0 on anything else
        if ($guest->invitation_type === 'single' && isset($validated['rsvp_status'])) {
            $validated['rsvp_confirmed'] = $validated['rsvp_status'] === 'accepted' ? 1 : 0;
        }

        $guest->update($validated);
        return redirect()->back()->with('success', 'Guest updated!');
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();
        return redirect()->back()->with('success', 'Guest removed!');
    }

    public function export(Request $request)
    {
        $wedding = $request->user()->wedding;
        $guests  = $wedding->guests()->with('group')->orderBy('name')->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="guest-list-' . str($wedding->bride_name . '-' . $wedding->groom_name)->slug() . '.csv"',
        ];

        $callback = function () use ($guests) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Side', 'Type', 'Total Invited', 'Confirmed', 'RSVP Status', 'Phone', 'Email', 'Notes']);
            foreach ($guests as $g) {
                fputcsv($handle, [
                    $g->name,
                    $g->group?->name ?? '—',
                    ucfirst($g->invitation_type),
                    $g->total_invited,
                    $g->rsvp_confirmed,
                    ucfirst($g->rsvp_status->value),
                    $g->phone ?? '',
                    $g->email ?? '',
                    $g->notes ?? '',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $wedding = $request->user()->wedding;
        $list    = $request->query('list', 'all'); // all | bride | groom

        $query = $wedding->guests()->with('group')->orderBy('guest_group_id')->orderBy('name');

        if ($list === 'bride') {
            $brideGroupIds = GuestGroup::where('slug', 'bride-side')->pluck('id');
            $query->whereIn('guest_group_id', $brideGroupIds);
            $listLabel = "Bride's List";
        } elseif ($list === 'groom') {
            $groomGroupIds = GuestGroup::where('slug', 'groom-side')->pluck('id');
            $query->whereIn('guest_group_id', $groomGroupIds);
            $listLabel = "Groom's List";
        } else {
            $listLabel = 'All Guests';
        }

        $guests = $query->get();
        $stats  = [
            'total_invitations' => $guests->count(),
            'total_invited'     => $guests->sum('total_invited'),
            'total_confirmed'   => $guests->sum('rsvp_confirmed'),
            'accepted'          => $guests->filter(fn($g) => $g->rsvp_status->value === 'accepted')->count(),
            'declined'          => $guests->filter(fn($g) => $g->rsvp_status->value === 'declined')->count(),
            'pending'           => $guests->filter(fn($g) => $g->rsvp_status->value === 'pending')->count(),
        ];

        return view('guests.print', compact('wedding', 'guests', 'stats', 'listLabel'));
    }
}
