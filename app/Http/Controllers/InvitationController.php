<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    // Templates available per wedding type.
    // Add new card keys here when new templates are designed for each type.
    private const TEMPLATES = [
        'sinhala'   => [
            'card1' => ['label' => 'Template 1', 'sub' => 'Classic Red',    'bg' => 'bg-red-50 border-red-200',    'tc' => 'text-red-700',    'color' => '#b32017'],
            'card2' => ['label' => 'Template 2', 'sub' => 'Elegant Green',  'bg' => 'bg-green-50 border-green-200','tc' => 'text-green-700',  'color' => '#15744e'],
            'card3' => ['label' => 'Template 3', 'sub' => 'Modern Pink',    'bg' => 'bg-pink-50 border-pink-200',  'tc' => 'text-pink-700',   'color' => '#c0176b'],
            'card4' => ['label' => 'Template 4', 'sub' => 'Rose Floral',    'bg' => 'bg-rose-50 border-rose-200',  'tc' => 'text-rose-700',   'color' => '#c44762'],
            'card5' => ['label' => 'Template 5', 'sub' => 'Gold Elegant',   'bg' => 'bg-yellow-50 border-yellow-200','tc' => 'text-yellow-700','color' => '#b08d57'],
            'card6' => ['label' => 'Template 6', 'sub' => 'Modern Minimal', 'bg' => 'bg-gray-50 border-gray-200',  'tc' => 'text-gray-700',   'color' => '#333333'],
        ],
        'tamil'     => [], // coming soon
        'muslim'    => [], // coming soon
        'christian' => [], // coming soon
    ];

    public function index(Request $request)
    {
        $wedding            = $request->user()->wedding;
        $invitation         = $wedding->invitation;
        $weddingType        = $wedding->wedding_type->value;
        $availableTemplates = self::TEMPLATES[$weddingType] ?? [];

        return view('invitations.index', compact('invitation', 'wedding', 'availableTemplates', 'weddingType'));
    }

    public function store(Request $request)
    {
        $wedding     = $request->user()->wedding;
        $weddingType = $wedding->wedding_type->value;
        $validKeys   = array_keys(self::TEMPLATES[$weddingType] ?? []);

        $validated = $request->validate([
            'bride_name'       => 'required|string|max:255',
            'groom_name'       => 'required|string|max:255',
            'bride_surname'    => 'required|string|max:255',
            'groom_surname'    => 'required|string|max:255',
            'wedding_day'      => 'required|string|max:255',
            'wedding_location' => 'required|string|max:255',
            'venue_map_url'    => 'nullable|url|max:500',
            'duration_from'    => 'required|string|max:50',
            'duration_to'      => 'required|string|max:50',
            'poruwa_time'      => 'required|string|max:50',
            'bride_phone'      => 'nullable|string|max:20',
            'groom_phone'      => 'nullable|string|max:20',
            'template'         => ['required', 'in:' . implode(',', $validKeys)],
        ]);

        $wedding->invitation()->updateOrCreate([], $validated);

        return redirect()->route('invitations.preview')
            ->with('success', 'Invitation saved!');
    }

    public function preview(Request $request)
    {
        $invitation = $request->user()->wedding->invitation;

        if (!$invitation) {
            return redirect()->route('invitations.index')
                ->with('error', 'Please fill in your invitation details first.');
        }

        return view('invitations.preview', compact('invitation'));
    }

    public function guestCard(Request $request, Guest $guest)
    {
        $invitation = $request->user()->wedding->invitation;

        if (!$invitation) {
            return redirect()->route('invitations.index')
                ->with('error', 'Please create your invitation template first.');
        }

        $guestLine = match ($guest->invitation_type) {
            'couple' => 'MR. & MRS. ' . strtoupper($guest->name),
            'family' => 'FAMILY OF ' . strtoupper($guest->name),
            default  => strtoupper($guest->name),
        };

        return view('invitations.preview', compact('invitation', 'guest', 'guestLine'));
    }
}
