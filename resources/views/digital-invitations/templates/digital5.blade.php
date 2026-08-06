@php
    $d              = $invitation->parsedDay();
    $cfg            = $invitation->resolvedConfig();
    $sec            = $cfg['sections'];
    $timelineEvents = $cfg['timeline_events'] ?? [];
    $isPreviewPane  = request()->boolean('preview');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $invitation->bride_name }} & {{ $invitation->groom_name }} — Wedding Invitation</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,400&family=Great+Vibes&family=Cinzel:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/digital5.css') }}">
</head>
<body>

<!-- ── Toolbar ── -->
<div class="toolbar">
    @if(isset($token))
        <span>💌 Wedding Invitation</span>
    @elseif(isset($guest))
        <a href="{{ route('guests.index') }}">← Back to Guests</a>
        <span>💌 {{ $guest->name }}</span>
    @else
        <a href="{{ route('digital-invitations.index') }}">← Templates</a>
        <span>Grand Entrance</span>
    @endif
    @if($sec['music'] ?? true)
    <button onclick="toggleMusic()" id="music-btn">🎵 Music Off</button>
    @endif
</div>

@if($sec['music'] ?? true)
<audio id="bg-music" loop>
    <source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-9.mp3" type="audio/mpeg">
</audio>
@endif

@if($isPreviewPane)
<style>#hero{min-height:auto !important;padding-top:80px !important;}</style>
@endif

<!-- ══════════════════════════════════════════════════════════
     ENTRANCE SCENE (fixed, full screen)
     ══════════════════════════════════════════════════════════ -->
@if(!$isPreviewPane)
<div id="scene">

    <div id="bg-gradient"></div>
    <div id="ambient-glow"></div>

    <!-- Bloom layers -->
    <div id="bloom-wrap">
        <div id="bloom-outer"></div>
        <div id="bloom-core"></div>
    </div>

    <!-- ── Entrance / Door ── -->
    <div id="entrance-wrap">
        <div id="door-arch">
            <div class="arch-floral">❧ ✦ ❧</div>
        </div>
        <div id="door-panels">
            <div id="door-light"></div>
            <div id="light-rays"></div>
            <div id="door-left">
                <div class="door-panel-inner"></div>
                <div class="door-rose">✦</div>
                <div class="door-knob"></div>
            </div>
            <div id="door-right">
                <div class="door-panel-inner"></div>
                <div class="door-rose">✦</div>
                <div class="door-knob"></div>
            </div>
        </div>
        <div id="door-step"></div>
        <div class="pillar pillar-left"><div class="pillar-cap"></div></div>
        <div class="pillar pillar-right"><div class="pillar-cap"></div></div>
    </div>

    <!-- ── Couple SVG ── -->
    <div id="couple-wrap">
        <svg id="couple-svg"
             viewBox="0 0 1023 1537"
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink">
            <defs>
                <clipPath id="clip-groom-head"><ellipse cx="200" cy="110" rx="110" ry="120"/></clipPath>
                <clipPath id="clip-groom-body"><rect x="80" y="195" width="280" height="420"/></clipPath>
                <clipPath id="clip-groom-arm-l"><rect x="20" y="210" width="90" height="380" rx="20"/></clipPath>
                <clipPath id="clip-groom-arm-r"><rect x="330" y="210" width="90" height="340" rx="20"/></clipPath>
                <clipPath id="clip-groom-leg-l"><rect x="90" y="760" width="130" height="777"/></clipPath>
                <clipPath id="clip-groom-leg-r"><rect x="230" y="760" width="130" height="777"/></clipPath>
                <clipPath id="clip-bride-head"><ellipse cx="800" cy="105" rx="105" ry="115"/></clipPath>
                <clipPath id="clip-bride-veil"><rect x="840" y="0" width="183" height="430" rx="10"/></clipPath>
                <clipPath id="clip-bride-body"><rect x="680" y="195" width="220" height="400"/></clipPath>
                <clipPath id="clip-bride-arm"><rect x="900" y="210" width="85" height="320" rx="20"/></clipPath>
                <clipPath id="clip-bride-skirt"><rect x="490" y="660" width="533" height="877"/></clipPath>
            </defs>
            <g id="g-groom-leg-l"  clip-path="url(#clip-groom-leg-l)"><image href="{{ asset('couple1.png') }}" width="1023" height="1537"/></g>
            <g id="g-groom-leg-r"  clip-path="url(#clip-groom-leg-r)"><image href="{{ asset('couple1.png') }}" width="1023" height="1537"/></g>
            <g id="g-groom-body"   clip-path="url(#clip-groom-body)"><image href="{{ asset('couple1.png') }}" width="1023" height="1537"/></g>
            <g id="g-groom-arm-l"  clip-path="url(#clip-groom-arm-l)"><image href="{{ asset('couple1.png') }}" width="1023" height="1537"/></g>
            <g id="g-groom-arm-r"  clip-path="url(#clip-groom-arm-r)"><image href="{{ asset('couple1.png') }}" width="1023" height="1537"/></g>
            <g id="g-groom-head"   clip-path="url(#clip-groom-head)"><image href="{{ asset('couple1.png') }}" width="1023" height="1537"/></g>
            <g id="g-bride-skirt"  clip-path="url(#clip-bride-skirt)"><image href="{{ asset('couple1.png') }}" width="1023" height="1537"/></g>
            <g id="g-bride-body"   clip-path="url(#clip-bride-body)"><image href="{{ asset('couple1.png') }}" width="1023" height="1537"/></g>
            <g id="g-bride-arm"    clip-path="url(#clip-bride-arm)"><image href="{{ asset('couple1.png') }}" width="1023" height="1537"/></g>
            <g id="g-bride-veil"   clip-path="url(#clip-bride-veil)"><image href="{{ asset('couple1.png') }}" width="1023" height="1537"/></g>
            <g id="g-bride-head"   clip-path="url(#clip-bride-head)"><image href="{{ asset('couple1.png') }}" width="1023" height="1537"/></g>
        </svg>
        <div class="couple-shadow"></div>
    </div>

</div><!-- end #scene -->
@endif

<!-- Flash overlay -->
@if(!$isPreviewPane)
<div id="flash"></div>
@endif

<!-- ══════════════════════════════════════════════════════════
     MAIN CONTENT — revealed after flash
     ══════════════════════════════════════════════════════════ -->
<div id="main-content" class="{{ $isPreviewPane ? 'visible' : '' }}">

<!-- ── HERO ── -->
<section id="hero">

    <!-- decorative corner florals -->
    <div class="hero-corner hero-corner-tl">❧</div>
    <div class="hero-corner hero-corner-tr">❧</div>
    <div class="hero-corner hero-corner-bl">❧</div>
    <div class="hero-corner hero-corner-br">❧</div>

    <div class="hero-inner reveal">

        <div class="hero-top-ornament">
            <span class="hero-top-line"></span>
            <span class="hero-top-diamond">◆</span>
            <span class="hero-top-line"></span>
        </div>

        <p class="hero-pre">With the blessings of their families</p>

        <p class="hero-invite-line">joyfully invite you to witness the union of</p>

        <h1 class="hero-names">
            {{ $invitation->bride_name }}
            <span class="hero-amp">&</span>
            {{ $invitation->groom_name }}
        </h1>

        <div class="hero-divider"><span>✦</span></div>

        <p class="hero-date">
            {{ $d['dayOfWeek'] }}, the {{ $d['dayNumber'] }}{{ $d['daySuffix'] }} of {{ $d['month'] }}, {{ $d['year'] }}
        </p>

        <div class="hero-venue-wrap">
            <span class="hero-venue-icon">📍</span>
            <p class="hero-venue">{{ $d['venueName'] }}@if($d['venueCity']), {{ $d['venueCity'] }}@endif</p>
        </div>

        <div class="hero-bottom-ornament">
            <span>❧</span><span class="hero-ornament-line"></span>
            <span class="hero-monogram">{{ mb_substr($invitation->bride_name,0,1) }} &amp; {{ mb_substr($invitation->groom_name,0,1) }}</span>
            <span class="hero-ornament-line"></span><span>❧</span>
        </div>

    </div>

    <div class="scroll-hint">
        <div class="scroll-chevron"></div>
        <span>Discover More</span>
    </div>
</section>

<!-- ── COUPLE ── -->
@if($sec['couple'] ?? true)
<section id="couple">
    <div class="section">
        <span class="section-label reveal">— The Beloved Couple —</span>
        <h2 class="section-title reveal">Bound by Destiny,<br><em>United in Love</em></h2>
        <div class="gold-divider reveal"><span>✦</span></div>
        <p class="section-intro reveal">
            Two hearts that found each other across the journey of life,<br>
            now choosing to walk every path together, forever.
        </p>

        <div class="couple-grid reveal">
            <div class="couple-card">
                <div class="couple-photo-ring">
                    <div class="couple-photo-inner">👰</div>
                    <div class="couple-ring-ornament">✦</div>
                </div>
                <h3 class="couple-name">{{ $invitation->bride_name }}</h3>
                <p class="couple-surname">{{ $invitation->bride_surname }}</p>
                <div class="couple-divider"></div>
                <p class="couple-parents">
                    Beloved daughter of<br>
                    <strong>Mr. &amp; Mrs. {{ $invitation->bride_surname }}</strong>
                </p>
            </div>

            <div class="couple-and-wrap">
                <div class="couple-and">&</div>
                <div class="couple-and-line"></div>
                <p class="couple-and-sub">together<br>forever</p>
            </div>

            <div class="couple-card">
                <div class="couple-photo-ring">
                    <div class="couple-photo-inner">🤵</div>
                    <div class="couple-ring-ornament">✦</div>
                </div>
                <h3 class="couple-name">{{ $invitation->groom_name }}</h3>
                <p class="couple-surname">{{ $invitation->groom_surname }}</p>
                <div class="couple-divider"></div>
                <p class="couple-parents">
                    Beloved son of<br>
                    <strong>Mr. &amp; Mrs. {{ $invitation->groom_surname }}</strong>
                </p>
            </div>
        </div>
    </div>
</section>
@endif

<!-- ── INVITEE (guest-specific) ── -->
@if(isset($guest))
<section id="invitee">
    <div class="invitee-inner">
        <div class="invitee-top-ornament">
            <span class="invitee-line"></span>
            <span>✦ ❧ ✦</span>
            <span class="invitee-line"></span>
        </div>
        <p class="invitee-label reveal">A Personal Invitation</p>
        <p class="invitee-pre reveal">With great joy and warmth, we extend this invitation to</p>
        <div class="invitee-name reveal">{{ $guest->name }}</div>
        @if($guest->total_invited > 1)
        <p class="invitee-plus reveal">
            along with {{ $guest->total_invited - 1 }} {{ $guest->total_invited - 1 === 1 ? 'cherished guest' : 'cherished guests' }}
        </p>
        @endif
        <div class="gold-divider reveal" style="margin:28px auto;"><span>🌸</span></div>
        <p class="invitee-msg reveal">
            Your presence would grace our celebration and fill our hearts<br>
            with immeasurable joy on this most sacred of days.
        </p>
        <p class="invitee-quote reveal">
            &ldquo; Where there is love, there is life. &rdquo;
        </p>
    </div>
</section>
@endif

<!-- ── EVENT DETAILS ── -->
@if($sec['details'] ?? true)
<section id="details">
    <div class="section">
        <span class="section-label reveal">— Mark Your Calendar —</span>
        <h2 class="section-title reveal">The Sacred Celebration</h2>
        <div class="gold-divider reveal"><span>✦</span></div>
        <p class="section-intro reveal">
            Every detail has been lovingly arranged to create<br>
            a day of beauty, tradition and unforgettable memories.
        </p>

        <div class="details-grid reveal">
            <div class="detail-card">
                <div class="detail-icon-wrap">📅</div>
                <div class="detail-label">Auspicious Date</div>
                <div class="detail-value">{{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }}</div>
                <div class="detail-sub">{{ $d['dayOfWeek'] }}, {{ $d['year'] }}</div>
            </div>
            <div class="detail-card">
                <div class="detail-icon-wrap">⏰</div>
                <div class="detail-label">Poruwa Ceremony</div>
                <div class="detail-value">{{ strtoupper($invitation->poruwa_time) }}</div>
                <div class="detail-sub">The sacred auspicious hour</div>
            </div>
            <div class="detail-card">
                <div class="detail-icon-wrap">🍾</div>
                <div class="detail-label">Reception Begins</div>
                <div class="detail-value">{{ strtoupper($invitation->duration_from) }}</div>
                <div class="detail-sub">Celebrations until {{ strtoupper($invitation->duration_to) }}</div>
            </div>
            <div class="detail-card">
                <div class="detail-icon-wrap">🏩</div>
                <div class="detail-label">Venue</div>
                <div class="detail-value">{{ $d['venueName'] }}</div>
                <div class="detail-sub">{{ $d['venueCity'] }}</div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- ── COUNTDOWN ── -->
@if($sec['countdown'] ?? true)
<section id="countdown">
    <div class="countdown-bg-text">FOREVER</div>
    <div class="section">
        <span class="section-label reveal">— The Anticipation —</span>
        <h2 class="section-title reveal">The Moment Draws Near</h2>
        <div class="gold-divider reveal"><span>✦</span></div>
        <p class="section-intro reveal" style="color:rgba(232,201,122,0.6);">
            Every second that passes brings us closer<br>
            to the most beautiful day of our lives.
        </p>
        <div class="countdown-grid reveal" id="countdown-grid">
            <div class="cd-box"><span class="cd-num" id="cd-days">--</span><span class="cd-label">Days</span></div>
            <div class="cd-sep">:</div>
            <div class="cd-box"><span class="cd-num" id="cd-hours">--</span><span class="cd-label">Hours</span></div>
            <div class="cd-sep">:</div>
            <div class="cd-box"><span class="cd-num" id="cd-mins">--</span><span class="cd-label">Minutes</span></div>
            <div class="cd-sep">:</div>
            <div class="cd-box"><span class="cd-num" id="cd-secs">--</span><span class="cd-label">Seconds</span></div>
        </div>
        <p class="countdown-footer reveal">
            {{ $d['dayOfWeek'] }}, {{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }} {{ $d['year'] }}
        </p>
    </div>
</section>
@endif

<!-- ── TIMELINE ── -->
@if($sec['timeline'] ?? true)
<section id="timeline">
    <div class="section">
        <span class="section-label reveal">— A Day to Remember —</span>
        <h2 class="section-title reveal">The Journey of Our Day</h2>
        <div class="gold-divider reveal"><span>✦</span></div>
        <p class="section-intro reveal">
            From the first welcome to the final farewell,<br>
            every moment has been crafted with love.
        </p>

        <div class="timeline-wrap">
            <div class="tl-line"></div>
            @foreach($timelineEvents as $i => $ev)
            <div class="tl-item reveal">
                @if($i % 2 === 0)
                <div class="tl-content tl-left"><div class="tl-time">{{ strtoupper($ev['time']) }}</div><div class="tl-event">{{ $ev['event'] }}</div>@if($ev['desc'])<div class="tl-desc">{{ $ev['desc'] }}</div>@endif</div>
                <div class="tl-node"><div class="tl-dot"></div></div>
                <div class="tl-content tl-right"></div>
                @else
                <div class="tl-content tl-left"></div>
                <div class="tl-node"><div class="tl-dot"></div></div>
                <div class="tl-content tl-right"><div class="tl-time">{{ strtoupper($ev['time']) }}</div><div class="tl-event">{{ $ev['event'] }}</div>@if($ev['desc'])<div class="tl-desc">{{ $ev['desc'] }}</div>@endif</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ── VENUE ── -->
@if($sec['venue'] ?? true)
<section id="venue">
    <div class="section">
        <span class="section-label reveal">— Where Love Unfolds —</span>
        <h2 class="section-title reveal">The Celebration Venue</h2>
        <div class="gold-divider reveal"><span>📍</span></div>
        <p class="section-intro reveal">
            A place chosen with care, where the beauty of the setting<br>
            mirrors the beauty of the occasion.
        </p>

        <div class="venue-card reveal">
            <div class="venue-map-wrap">
                @if($invitation->venue_map_url)
                <iframe src="{{ $invitation->venue_map_url }}"
                    width="100%" height="280" style="border:0;display:block;"
                    allowfullscreen loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                @else
                <div class="map-placeholder">
                    <span>🗺️</span>
                    <p>{{ $d['venueName'] }}</p>
                    @if($d['venueCity'])<small>{{ $d['venueCity'] }}</small>@endif
                </div>
                @endif
            </div>
            <div class="venue-info">
                <div class="venue-name">{{ $d['venueName'] }}</div>
                @if($d['venueCity'])<div class="venue-address">📍 {{ $d['venueCity'] }}</div>@endif
                <p class="venue-note">We look forward to welcoming you at our venue</p>
                @if($invitation->venue_map_url)
                <a href="{{ $invitation->venue_map_url }}" target="_blank" class="venue-btn">📍 Get Directions</a>
                @else
                <a href="https://maps.google.com/?q={{ urlencode($invitation->wedding_location) }}" target="_blank" class="venue-btn">📍 Search on Maps</a>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

<!-- ── RSVP ── -->
@if($sec['rsvp'] ?? true)
@php
    $guestSide  = isset($guest) ? ($guest->group?->slug ?? '') : '';
    $waNumber   = ($guestSide === 'bride-side' && $invitation->bride_phone)
                    ? $invitation->bride_phone
                    : ($invitation->groom_phone ?? $invitation->bride_phone ?? '');
    $waNumber   = preg_replace('/\D/', '', $waNumber);
    $guestLabel = isset($guestName) ? $guestName : 'A Guest';
    $coupleName = $invitation->bride_name . ' & ' . $invitation->groom_name;
@endphp
<section id="rsvp">
    <div class="rsvp-bg-ornament">✦</div>
    <div class="section">
        <span class="section-label reveal">— Your Presence Matters —</span>
        <h2 class="section-title reveal">Kindly Respond</h2>
        <div class="gold-divider reveal"><span>✦</span></div>
        <p class="section-intro reveal" style="color:rgba(232,201,122,0.6);">
            Your presence would be the greatest gift you could offer.<br>
            Please let us know so we may prepare to welcome you.
        </p>

        <div class="rsvp-card reveal">
            @if(isset($guestName))
            <div class="rsvp-dear">Dear {{ $guestName }},</div>
            @endif
            <p class="rsvp-msg">
                We humbly request the honour of your presence<br>
                as we exchange our vows and begin our forever.
            </p>

            @if($waNumber)
            <div class="rsvp-choices">
                <button class="rsvp-btn" id="btn-accepted" onclick="selectRsvp('accepted')">✓ Joyfully Accept</button>
                <button class="rsvp-btn" id="btn-declined" onclick="selectRsvp('declined')">✕ Regretfully Decline</button>
                <button class="rsvp-btn" id="btn-maybe"    onclick="selectRsvp('maybe')">◌ Yet to Confirm</button>
            </div>
            <p class="rsvp-hint" id="rsvp-hint">Choose your response, then send it to us via WhatsApp</p>
            <a id="rsvp-wa-btn" href="#" class="rsvp-whatsapp disabled" onclick="return sendRsvp(event)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                <span id="rsvp-wa-label">Send Your Response</span>
            </a>
            @else
            <p class="rsvp-no-contact">Contact details not yet available.</p>
            @endif
        </div>
    </div>
</section>
@endif

<!-- ── FOOTER ── -->
<footer id="footer">
    <div class="footer-top-ornament">
        <span class="footer-line"></span>
        <span>✦ ❧ ✦</span>
        <span class="footer-line"></span>
    </div>
    <div class="footer-names">{{ $invitation->bride_name }} &amp; {{ $invitation->groom_name }}</div>
    <p class="footer-tagline">&ldquo; And they lived happily ever after &rdquo;</p>
    <div class="footer-divider"><span>✦</span></div>
    <div class="footer-date">{{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }} {{ $d['year'] }}</div>
    <p class="footer-venue">{{ $d['venueName'] }}@if($d['venueCity']), {{ $d['venueCity'] }}@endif</p>
    <div class="footer-bottom">
        <span>Crafted with</span>
        <span style="color:var(--gold);"> ♥ </span>
        <span>for a lifetime of love</span>
    </div>
</footer>

</div><!-- end #main-content -->

<!-- ── RSVP JS ── -->
<script>
@if($sec['music'] ?? true)
let musicOn = false;
const audio = document.getElementById('bg-music');
const musicBtn = document.getElementById('music-btn');
function toggleMusic() {
    if (musicOn) { audio.pause(); musicBtn.textContent = '🎵 Music Off'; musicOn = false; }
    else { audio.play().catch(() => {}); musicBtn.textContent = '🎵 Music On'; musicOn = true; }
}
@else
function toggleMusic() {}
@endif

@if($isPreviewPane)
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.reveal').forEach(el => el.classList.add('visible'));
});
@endif

const waNumber   = '{{ $waNumber ?? '' }}';
const guestLabel = '{{ addslashes($guestLabel ?? 'A Guest') }}';
const coupleName = '{{ addslashes($coupleName ?? '') }}';
const weddingDay = '{{ addslashes($invitation->wedding_day ?? '') }}';
const rsvpUrl    = '{{ isset($token) ? route('invite.rsvp', $token) : '' }}';
const csrfToken  = '{{ csrf_token() }}';
let selectedRsvp = null;

const rsvpMessages = {
    accepted: `Hi! This is ${guestLabel}. I am happy to confirm that I will be ATTENDING the wedding of ${coupleName} on ${weddingDay}. 🌹`,
    declined: `Hi! This is ${guestLabel}. I regret to inform you that I will NOT be able to attend the wedding of ${coupleName} on ${weddingDay}. Wishing you a wonderful day! 🙏`,
    maybe:    `Hi! This is ${guestLabel}. Regarding the wedding of ${coupleName} on ${weddingDay} — I am UNSURE if I can attend and will confirm soon. 🌸`,
};
const rsvpBtnLabels = {
    accepted: '✓ Send Acceptance',
    declined: '✕ Send Decline',
    maybe:    '? Send Maybe',
};

function selectRsvp(choice) {
    selectedRsvp = choice;
    ['accepted','declined','maybe'].forEach(c => {
        const map = { accepted:'accept', declined:'decline', maybe:'maybe' };
        document.getElementById('btn-' + c).className =
            'rsvp-btn' + (c === choice ? ' selected-' + map[c] : '');
    });
    const waBtn = document.getElementById('rsvp-wa-btn');
    waBtn.classList.remove('disabled');
    waBtn.href = 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent(rsvpMessages[choice]);
    document.getElementById('rsvp-wa-label').textContent = rsvpBtnLabels[choice];
}

function sendRsvp(e) {
    if (!selectedRsvp || !rsvpUrl) return true;
    fetch(rsvpUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ rsvp: selectedRsvp }),
    }).catch(() => {});
    return true;
}
</script>

<!-- ── Countdown JS ── -->
<script>
@php
    $wDate     = $invitation->wedding->wedding_date ?? null;
    $weddingTs = $wDate ? $wDate->timestamp * 1000 : 0;
@endphp
const weddingTime = {{ $weddingTs }};

function updateCountdown() {
    const diff = weddingTime - Date.now();
    if (diff <= 0) {
        ['cd-days','cd-hours','cd-mins','cd-secs'].forEach(id =>
            document.getElementById(id).textContent = '00');
        return;
    }
    document.getElementById('cd-days').textContent  = String(Math.floor(diff / 86400000)).padStart(2,'0');
    document.getElementById('cd-hours').textContent = String(Math.floor((diff % 86400000) / 3600000)).padStart(2,'0');
    document.getElementById('cd-mins').textContent  = String(Math.floor((diff % 3600000) / 60000)).padStart(2,'0');
    document.getElementById('cd-secs').textContent  = String(Math.floor((diff % 60000) / 1000)).padStart(2,'0');
}
updateCountdown();
setInterval(updateCountdown, 1000);
</script>

<!-- GSAP primary CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<!-- GSAP fallback CDN -->
<script>window.gsap || document.write('<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"><\/script>');</script>
<script src="{{ asset('js/digital5.js') }}"></script>

</body>
</html>
