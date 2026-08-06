@php
    $d       = $invitation->parsedDay();
    $isGuest = isset($guestLine);
    // Allow ?template= override for iframe previews
    if (request()->has('template')) {
        $invitation->template = request('template');
    }
    $guestLine = $guestLine ?? null;
@endphp

@include('invitations.templates.' . $invitation->template)
