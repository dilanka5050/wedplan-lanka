<?php

namespace App\Http\Controllers;

use App\Enums\RsvpStatus;
use App\Models\Guest;
use Illuminate\Http\Request;

class GuestRsvpController extends Controller
{
    // Show the digital invitation via unique token (no auth required)
    public function show(string $token)
    {
        $guest      = Guest::where('invite_token', $token)->firstOrFail();
        $invitation = $guest->wedding->invitation;

        abort_if(!$invitation, 404);

        $template = request()->query('template', 'digital1');
        abort_unless(array_key_exists($template, DigitalInvitationController::TEMPLATES), 404);

        $guestName = match ($guest->invitation_type) {
            'couple' => 'Mr. & Mrs. ' . $guest->name,
            'family' => 'The ' . $guest->name . ' Family',
            default  => $guest->name,
        };

        return view("digital-invitations.templates.{$template}", [
            'invitation' => $invitation,
            'guestName'  => $guestName,
            'guest'      => $guest,
            'template'   => $template,
            'token'      => $token,
            'rsvpSaved'  => false,
        ]);
    }

    // Handle RSVP — update DB only (called via fetch, no redirect)
    public function rsvp(Request $request, string $token)
    {
        $request->validate(['rsvp' => 'required|in:accepted,declined,maybe']);

        $guest  = Guest::where('invite_token', $token)->firstOrFail();
        $status = RsvpStatus::from($request->rsvp);

        $guest->rsvp_status = $status;
        if ($guest->invitation_type === 'single') {
            $guest->rsvp_confirmed = $status === RsvpStatus::ACCEPTED ? 1 : 0;
        }
        $guest->save();

        return response()->json(['ok' => true]);
    }
}
