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
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,400&family=Great+Vibes&family=EB+Garamond:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
<style>
:root {
    --red:       #8B0000;
    --red-mid:   #A31515;
    --red-light: #C41E3A;
    --red-pale:  #F5E6E6;
    --gold:      #C9A84C;
    --gold-light:#E8C97A;
    --gold-pale: #FDF6E3;
    --cream:     #FAF3E8;
    --cream-dark:#F0E6D0;
    --dark:      #1A0A0A;
    --text-dark: #2C1010;
    --text-mid:  #5C3030;
    --text-light:#8B6060;
}
*{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{
    background:var(--cream);
    font-family:'Cormorant Garamond',serif;
    color:var(--text-dark);
    overflow-x:hidden;
}
</style>
</head>
<body>

<style>
/* ── Toolbar ── */
.toolbar{position:fixed;top:0;left:0;right:0;z-index:200;display:flex;align-items:center;justify-content:space-between;padding:10px 24px;background:rgba(26,10,10,0.85);backdrop-filter:blur(10px);border-bottom:1px solid rgba(201,168,76,0.2);}
.toolbar a,.toolbar button{font-family:sans-serif;font-size:11px;color:var(--gold-light);text-decoration:none;padding:5px 14px;border:1px solid rgba(201,168,76,0.35);border-radius:999px;background:transparent;cursor:pointer;}
.toolbar span{font-family:sans-serif;font-size:11px;color:var(--gold-light);letter-spacing:1px;}
.music-btn{display:flex;align-items:center;gap:6px;}
@media print{.toolbar{display:none;}}

/* ── Floating particles ── */
.particle{position:fixed;pointer-events:none;border-radius:50%;animation:floatUp linear infinite;}
@keyframes floatUp{0%{transform:translateY(100vh) rotate(0deg);opacity:0;}10%{opacity:1;}90%{opacity:0.4;}100%{transform:translateY(-10vh) rotate(360deg);opacity:0;}}

/* ── Envelope scene ── */
#envelope-scene{
    position:fixed;inset:0;z-index:150;
    display:flex;align-items:center;justify-content:center;
    background:radial-gradient(ellipse at center, #2a0808 0%, #0d0202 100%);
    transition:opacity 0.8s ease;
}
#envelope-scene.hide{opacity:0;pointer-events:none;}

.env-wrap{position:relative;width:340px;height:240px;cursor:pointer;}

/* envelope body */
.env-body{
    position:absolute;inset:0;
    background:linear-gradient(160deg,#7a0000,#4a0000);
    border-radius:4px;
    box-shadow:0 20px 60px rgba(0,0,0,0.7), inset 0 1px 0 rgba(201,168,76,0.3);
    border:1px solid rgba(201,168,76,0.4);
}
/* envelope bottom flap */
.env-bottom{
    position:absolute;bottom:0;left:0;right:0;height:50%;
    background:linear-gradient(to top,#3a0000,#5a0000);
    clip-path:polygon(0 100%,50% 0%,100% 100%);
    border-top:1px solid rgba(201,168,76,0.2);
}
/* envelope side flaps */
.env-left{position:absolute;top:0;left:0;width:50%;height:100%;background:linear-gradient(to right,#5a0000,#6a0000);clip-path:polygon(0 0,100% 50%,0 100%);border-right:1px solid rgba(201,168,76,0.15);}
.env-right{position:absolute;top:0;right:0;width:50%;height:100%;background:linear-gradient(to left,#5a0000,#6a0000);clip-path:polygon(100% 0,0 50%,100% 100%);border-left:1px solid rgba(201,168,76,0.15);}

/* envelope top flap — animates open */
.env-top{
    position:absolute;top:0;left:0;right:0;height:55%;
    background:linear-gradient(to bottom,#8a0000,#5a0000);
    clip-path:polygon(0 0,50% 100%,100% 0);
    transform-origin:top center;
    transform:rotateX(0deg);
    transition:transform 1.2s cubic-bezier(0.4,0,0.2,1);
    border-bottom:1px solid rgba(201,168,76,0.2);
    z-index:2;
}
.env-wrap.open .env-top{transform:rotateX(-180deg);}

/* wax seal */
.wax-seal{
    position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
    width:70px;height:70px;
    background:radial-gradient(circle at 35% 35%,#C41E3A,#8B0000 60%,#5a0000);
    border-radius:50%;
    box-shadow:0 4px 20px rgba(139,0,0,0.8), 0 0 0 3px rgba(201,168,76,0.5);
    display:flex;align-items:center;justify-content:center;
    z-index:3;
    transition:transform 0.5s ease, opacity 0.5s ease;
}
.wax-seal.break{transform:translate(-50%,-50%) scale(1.4);opacity:0;}
.wax-seal-text{color:var(--gold-light);font-family:'Great Vibes',cursive;font-size:22px;text-shadow:0 1px 3px rgba(0,0,0,0.5);}

/* card rising from envelope */
.env-card{
    position:absolute;left:10%;right:10%;
    bottom:10%;height:85%;
    background:linear-gradient(160deg,var(--cream),var(--cream-dark));
    border:1px solid rgba(201,168,76,0.4);
    border-radius:2px;
    transform:translateY(0);
    transition:transform 1.4s cubic-bezier(0.2,0.8,0.3,1);
    z-index:1;
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 -10px 40px rgba(0,0,0,0.4);
}
.env-wrap.open .env-card{transform:translateY(-110%);}
.env-card-inner{text-align:center;padding:16px;}
.env-card-inner p{font-family:'Great Vibes',cursive;font-size:22px;color:var(--red);line-height:1.4;}
.env-card-inner span{font-family:'Cormorant Garamond',serif;font-size:10px;letter-spacing:3px;text-transform:uppercase;color:var(--gold);display:block;margin-top:6px;}

.env-hint{position:absolute;bottom:-40px;left:50%;transform:translateX(-50%);color:rgba(201,168,76,0.7);font-family:sans-serif;font-size:11px;letter-spacing:2px;text-transform:uppercase;white-space:nowrap;animation:pulse 2s ease-in-out infinite;}
@keyframes pulse{0%,100%{opacity:0.5;}50%{opacity:1;}}

.env-gold-lines{position:absolute;inset:8px;border:1px solid rgba(201,168,76,0.2);pointer-events:none;border-radius:2px;}
</style>

<style>
/* ── Main content ── */
#main-content{opacity:0;transition:opacity 1s ease;}
#main-content.visible{opacity:1;}

/* ── Section base ── */
.section{padding:80px 20px;max-width:760px;margin:0 auto;text-align:center;}
.section-label{font-family:'Cormorant Garamond',serif;font-size:11px;letter-spacing:5px;text-transform:uppercase;color:var(--gold);margin-bottom:12px;display:block;}
.section-title{font-family:'Playfair Display',serif;font-size:clamp(28px,5vw,42px);color:var(--red);font-weight:600;line-height:1.2;}
.gold-divider{display:flex;align-items:center;justify-content:center;gap:12px;margin:20px auto;}
.gold-divider::before,.gold-divider::after{content:'';flex:1;max-width:120px;height:1px;background:linear-gradient(90deg,transparent,var(--gold));}
.gold-divider::after{background:linear-gradient(90deg,var(--gold),transparent);}
.gold-divider span{color:var(--gold);font-size:16px;}

/* ── Hero ── */
#hero{
    min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;
    background:linear-gradient(160deg,#1a0000 0%,#2d0505 40%,#1a0000 100%);
    position:relative;overflow:hidden;padding:100px 20px 60px;
}
.hero-arch{
    position:relative;width:min(480px,90vw);
    background:linear-gradient(170deg,var(--cream) 0%,var(--cream-dark) 100%);
    border:1px solid rgba(201,168,76,0.5);
    border-radius:50% 50% 4px 4px / 20% 20% 4px 4px;
    padding:60px 40px 50px;
    text-align:center;
    box-shadow:0 30px 80px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.5);
}
.hero-arch::before{
    content:'';position:absolute;inset:8px;
    border:1px solid rgba(201,168,76,0.25);
    border-radius:50% 50% 2px 2px / 20% 20% 2px 2px;
    pointer-events:none;
}
/* floral corners */
.floral{position:absolute;font-size:28px;opacity:0.7;line-height:1;}
.floral-tl{top:14px;left:14px;}
.floral-tr{top:14px;right:14px;transform:scaleX(-1);}
.floral-bl{bottom:14px;left:14px;transform:scaleY(-1);}
.floral-br{bottom:14px;right:14px;transform:scale(-1,-1);}

.hero-pre{font-size:11px;letter-spacing:5px;text-transform:uppercase;color:var(--gold);margin-bottom:16px;display:block;}
.hero-names{font-family:'Great Vibes',cursive;color:var(--red);font-size:clamp(48px,10vw,72px);line-height:1.15;text-shadow:0 2px 20px rgba(139,0,0,0.2);}
.hero-amp{display:block;font-size:clamp(32px,6vw,48px);color:var(--gold);margin:4px 0;}
.hero-date{font-family:'Playfair Display',serif;font-size:clamp(13px,2.5vw,16px);color:var(--text-mid);letter-spacing:3px;text-transform:uppercase;margin-top:20px;}
.hero-venue{font-size:13px;color:var(--text-light);letter-spacing:2px;margin-top:8px;}

/* scroll indicator */
.scroll-down{position:absolute;bottom:30px;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:6px;color:rgba(201,168,76,0.6);font-family:sans-serif;font-size:10px;letter-spacing:2px;text-transform:uppercase;animation:bounce 2s ease-in-out infinite;}
@keyframes bounce{0%,100%{transform:translateX(-50%) translateY(0);}50%{transform:translateX(-50%) translateY(6px);}}
.scroll-arrow{width:20px;height:20px;border-right:1px solid rgba(201,168,76,0.6);border-bottom:1px solid rgba(201,168,76,0.6);transform:rotate(45deg);}

/* ── Couple section ── */
#couple{background:var(--cream-dark);padding:80px 20px;}
.couple-grid{display:grid;grid-template-columns:1fr auto 1fr;gap:30px;align-items:center;max-width:700px;margin:40px auto 0;}
.couple-card{text-align:center;padding:30px 20px;background:var(--cream);border:1px solid rgba(201,168,76,0.3);border-radius:2px;}
.couple-emoji{font-size:48px;margin-bottom:12px;display:block;}
.couple-name{font-family:'Great Vibes',cursive;font-size:clamp(28px,5vw,38px);color:var(--red);}
.couple-surname{font-size:12px;letter-spacing:3px;text-transform:uppercase;color:var(--gold);margin-top:4px;}
.couple-parents{font-size:13px;color:var(--text-light);margin-top:10px;line-height:1.7;font-style:italic;}
.couple-and{font-family:'Great Vibes',cursive;font-size:52px;color:var(--gold);text-align:center;}

/* ── Event details ── */
#details{background:var(--cream);}
.details-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:24px;margin-top:40px;}
.detail-card{padding:28px 20px;background:var(--cream-dark);border:1px solid rgba(201,168,76,0.25);border-top:3px solid var(--red);text-align:center;}
.detail-icon{font-size:28px;margin-bottom:10px;display:block;}
.detail-label{font-size:10px;letter-spacing:3px;text-transform:uppercase;color:var(--gold);margin-bottom:6px;}
.detail-value{font-family:'Playfair Display',serif;font-size:16px;color:var(--text-dark);font-weight:600;}
.detail-sub{font-size:12px;color:var(--text-light);margin-top:4px;}

/* ── Countdown ── */
#countdown{background:linear-gradient(135deg,var(--red) 0%,#5a0000 100%);padding:80px 20px;}
#countdown .section-label{color:rgba(201,168,76,0.8);}
#countdown .section-title{color:var(--gold-light);}
#countdown .gold-divider::before{background:linear-gradient(90deg,transparent,rgba(201,168,76,0.5));}
#countdown .gold-divider::after{background:linear-gradient(90deg,rgba(201,168,76,0.5),transparent);}
#countdown .gold-divider span{color:rgba(201,168,76,0.8);}
.countdown-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;max-width:500px;margin:40px auto 0;}
.cd-box{background:rgba(0,0,0,0.3);border:1px solid rgba(201,168,76,0.3);padding:20px 10px;text-align:center;border-radius:2px;}
.cd-num{font-family:'Playfair Display',serif;font-size:clamp(32px,6vw,48px);color:var(--gold-light);font-weight:700;line-height:1;display:block;}
.cd-label{font-size:10px;letter-spacing:3px;text-transform:uppercase;color:rgba(201,168,76,0.6);margin-top:6px;display:block;}

/* ── Timeline ── */
#timeline{background:var(--cream-dark);}
.timeline-list{max-width:560px;margin:40px auto 0;position:relative;}
.timeline-list::before{content:'';position:absolute;left:50%;top:0;bottom:0;width:1px;background:linear-gradient(to bottom,transparent,var(--gold),transparent);transform:translateX(-50%);}
.tl-item{display:grid;grid-template-columns:1fr 40px 1fr;gap:0;margin-bottom:32px;align-items:center;}
.tl-left{text-align:right;padding-right:20px;}
.tl-right{text-align:left;padding-left:20px;}
.tl-dot{width:12px;height:12px;background:var(--red);border:2px solid var(--gold);border-radius:50%;margin:0 auto;position:relative;z-index:1;}
.tl-time{font-size:11px;letter-spacing:2px;text-transform:uppercase;color:var(--gold);font-weight:600;}
.tl-event{font-family:'Playfair Display',serif;font-size:15px;color:var(--text-dark);margin-top:2px;}
.tl-desc{font-size:12px;color:var(--text-light);margin-top:2px;font-style:italic;}

/* ── Venue ── */
#venue{background:var(--cream-dark);}
.venue-map{max-width:600px;margin:40px auto 0;border:1px solid rgba(201,168,76,0.3);overflow:hidden;}
.map-placeholder{height:260px;background:linear-gradient(135deg,#f0e0e0,#e8d0d0);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;}
.map-placeholder span{font-size:40px;}
.map-placeholder p{font-family:'Playfair Display',serif;font-size:16px;color:var(--red);}
.map-placeholder small{font-size:11px;letter-spacing:2px;text-transform:uppercase;color:var(--text-light);}
.venue-details{padding:24px;background:var(--cream);text-align:center;}
.venue-name{font-family:'Playfair Display',serif;font-size:20px;color:var(--red);font-weight:600;}
.venue-address{font-size:13px;color:var(--text-light);margin-top:6px;letter-spacing:1px;}
.venue-btn{display:inline-block;margin-top:16px;padding:10px 28px;background:var(--red);color:var(--gold-light);font-family:sans-serif;font-size:11px;letter-spacing:2px;text-transform:uppercase;text-decoration:none;border:1px solid rgba(201,168,76,0.4);}

/* ── RSVP ── */
#rsvp{background:linear-gradient(160deg,#1a0000,#2d0505);}
#rsvp .section-label{color:rgba(201,168,76,0.8);}
#rsvp .section-title{color:var(--gold-light);}
#rsvp .gold-divider::before{background:linear-gradient(90deg,transparent,rgba(201,168,76,0.4));}
#rsvp .gold-divider::after{background:linear-gradient(90deg,rgba(201,168,76,0.4),transparent);}
#rsvp .gold-divider span{color:rgba(201,168,76,0.7);}
.rsvp-box{max-width:480px;margin:40px auto 0;background:rgba(255,255,255,0.05);border:1px solid rgba(201,168,76,0.25);padding:40px 32px;text-align:center;}
.rsvp-guest{font-family:'Great Vibes',cursive;font-size:clamp(32px,6vw,44px);color:var(--gold-light);margin-bottom:8px;}
.rsvp-msg{font-size:14px;color:rgba(240,200,160,0.7);letter-spacing:1px;line-height:1.8;margin-bottom:28px;}
.rsvp-choices{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-bottom:28px;}
.rsvp-btn{padding:12px 24px;border:1px solid rgba(201,168,76,0.35);background:transparent;color:rgba(240,200,160,0.7);font-family:'Cormorant Garamond',serif;font-size:15px;letter-spacing:2px;cursor:pointer;border-radius:2px;transition:all 0.25s ease;min-width:110px;}
.rsvp-btn:hover{border-color:var(--gold);color:var(--gold-light);background:rgba(201,168,76,0.08);}
.rsvp-btn.selected-accept{background:rgba(34,197,94,0.15);border-color:#22c55e;color:#86efac;}
.rsvp-btn.selected-decline{background:rgba(239,68,68,0.15);border-color:#ef4444;color:#fca5a5;}
.rsvp-btn.selected-maybe{background:rgba(234,179,8,0.15);border-color:#eab308;color:#fde047;}
.rsvp-whatsapp{display:inline-flex;align-items:center;gap:10px;padding:14px 32px;background:linear-gradient(135deg,#25D366,#128C7E);color:#fff;font-family:sans-serif;font-size:13px;font-weight:600;letter-spacing:1px;text-decoration:none;border-radius:2px;box-shadow:0 4px 20px rgba(37,211,102,0.3);transition:opacity 0.2s;}
.rsvp-whatsapp.disabled{opacity:0.35;pointer-events:none;}
.rsvp-hint{font-size:11px;color:rgba(201,168,76,0.45);letter-spacing:1px;margin-bottom:20px;}
.rsvp-no-contact{color:rgba(201,168,76,0.5);font-size:12px;letter-spacing:1px;}

/* ── Footer ── */
#footer{background:#0d0202;padding:40px 20px;text-align:center;}
#footer p{font-family:'Great Vibes',cursive;font-size:28px;color:var(--gold);margin-bottom:8px;}
#footer small{font-size:11px;letter-spacing:2px;text-transform:uppercase;color:rgba(201,168,76,0.4);}

/* ── Scroll reveal ── */
.reveal{opacity:0;transform:translateY(30px);transition:opacity 0.8s ease, transform 0.8s ease;}
.reveal.visible{opacity:1;transform:translateY(0);}

/* ── Mobile ── */
@media(max-width:600px){
    .couple-grid{grid-template-columns:1fr;gap:16px;}
    .couple-and{font-size:36px;}
    .timeline-list::before{left:20px;}
    .tl-item{grid-template-columns:20px 1fr;grid-template-rows:auto auto;}
    .tl-left{display:none;}
    .tl-dot{margin:0;}
    .tl-right{padding-left:16px;}
    .gallery-grid{grid-template-columns:repeat(2,1fr);}
    .gallery-item:first-child{grid-column:span 2;}
    .countdown-grid{grid-template-columns:repeat(2,1fr);}
}
</style>

<!-- Toolbar -->
<div class="toolbar">
    @if(isset($token))
        <span style="font-family:sans-serif;font-size:11px;color:var(--gold-light);letter-spacing:1px;">💌 Wedding Invitation</span>
    @elseif(isset($guest))
        <a href="{{ route('guests.index') }}">← Back to Guests</a>
        <span>💌 {{ $guest->name }}</span>
    @else
        <a href="{{ route('digital-invitations.index') }}">← Templates</a>
        <span>Crimson Elegance</span>
    @endif
    @if($sec['music'] ?? true)
    <button class="music-btn" onclick="toggleMusic()" id="music-btn">🎵 Music Off</button>
    @endif
</div>

<!-- Floating particles -->
@for($i = 0; $i < 25; $i++)
<div class="particle" style="
    left:{{ rand(0,100) }}%;
    width:{{ rand(3,7) }}px;
    height:{{ rand(3,7) }}px;
    background:{{ rand(0,1) ? 'rgba(201,168,76,0.4)' : 'rgba(196,30,58,0.3)' }};
    animation-duration:{{ rand(12,22) }}s;
    animation-delay:{{ rand(0,15000)/1000 }}s;
"></div>
@endfor

@if($sec['music'] ?? true)
<audio id="bg-music" loop>
    <source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-9.mp3" type="audio/mpeg">
</audio>
@endif

@if($isPreviewPane)
<style>#hero{min-height:auto !important;padding-top:80px !important;}</style>
@endif

<!-- ══════════════════════════════════════
     ENVELOPE SCENE
══════════════════════════════════════ -->
@if(!$isPreviewPane)
<div id="envelope-scene">
    <div style="text-align:center;">
        <div class="env-wrap" id="env-wrap" onclick="openEnvelope()">
            <div class="env-body">
                <div class="env-gold-lines"></div>
            </div>
            <div class="env-left"></div>
            <div class="env-right"></div>
            <div class="env-bottom"></div>
            <div class="env-card">
                <div class="env-card-inner">
                    <p>{{ $invitation->bride_name }}<br>&amp;<br>{{ $invitation->groom_name }}</p>
                    <span>Wedding Invitation</span>
                </div>
            </div>
            <div class="wax-seal" id="wax-seal">
                <span class="wax-seal-text">♥</span>
            </div>
            <div class="env-top" id="env-top"></div>
            <div class="env-hint">Tap to open</div>
        </div>
    </div>
</div>
@endif

<!-- ══════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════ -->
<div id="main-content" class="{{ $isPreviewPane ? 'visible' : '' }}">

<!-- HERO -->
<section id="hero">
    <div class="hero-arch reveal">
        <div class="floral floral-tl">🌹</div>
        <div class="floral floral-tr">🌹</div>
        <div class="floral floral-bl">🌿</div>
        <div class="floral floral-br">🌿</div>

        <span class="hero-pre">Together with their families</span>

        <div class="hero-names">
            {{ $invitation->bride_name }}
            <span class="hero-amp">&</span>
            {{ $invitation->groom_name }}
        </div>

        <div class="gold-divider" style="margin:16px auto;"><span>✦</span></div>

        <div class="hero-date">{{ $d['dayOfWeek'] }}, {{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }} {{ $d['year'] }}</div>
        <div class="hero-venue">{{ $d['venueName'] }}@if($d['venueCity']), {{ $d['venueCity'] }}@endif</div>
    </div>
    <div class="scroll-down">
        <div class="scroll-arrow"></div>
        <span>Scroll</span>
    </div>
</section>

<!-- COUPLE -->
@if($sec['couple'] ?? true)
<section id="couple">
    <div class="section">
        <span class="section-label reveal">The Couple</span>
        <h2 class="section-title reveal">Two Hearts, One Story</h2>
        <div class="gold-divider reveal"><span>🌹</span></div>

        <div class="couple-grid reveal">
            <div class="couple-card">
                <span class="couple-emoji">👰</span>
                <div class="couple-name">{{ $invitation->bride_name }}</div>
                <div class="couple-surname">{{ $invitation->bride_surname }}</div>
                <div class="couple-parents">Daughter of<br>Mr. &amp; Mrs. {{ $invitation->bride_surname }}</div>
            </div>
            <div class="couple-and">&</div>
            <div class="couple-card">
                <span class="couple-emoji">🤵</span>
                <div class="couple-name">{{ $invitation->groom_name }}</div>
                <div class="couple-surname">{{ $invitation->groom_surname }}</div>
                <div class="couple-parents">Son of<br>Mr. &amp; Mrs. {{ $invitation->groom_surname }}</div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- INVITEE -->
@if(isset($guest))
<section id="invitee" style="background:var(--red);padding:60px 20px;">
    <div style="max-width:560px;margin:0 auto;text-align:center;">
        <span class="section-label reveal" style="color:rgba(201,168,76,0.8);">You Are Invited</span>
        <div class="gold-divider reveal" style="margin:16px auto;"><span style="color:rgba(201,168,76,0.8);">✦</span></div>
        <p class="reveal" style="font-family:'Cormorant Garamond',serif;font-size:15px;letter-spacing:3px;text-transform:uppercase;color:rgba(240,210,160,0.75);margin-bottom:16px;">Together with their families, we request the honour of the presence of</p>
        <div class="reveal" style="font-family:'Great Vibes',cursive;font-size:clamp(38px,8vw,58px);color:var(--gold-light);line-height:1.2;margin-bottom:16px;">{{ $guest->name }}</div>
        @if($guest->total_invited > 1)
        <p class="reveal" style="font-family:'Cormorant Garamond',serif;font-size:13px;letter-spacing:3px;text-transform:uppercase;color:rgba(201,168,76,0.6);">& {{ $guest->total_invited - 1 }} {{ $guest->total_invited - 1 === 1 ? 'guest' : 'guests' }}</p>
        @endif
        <div class="gold-divider reveal" style="margin:20px auto;"><span style="color:rgba(201,168,76,0.8);">🌹</span></div>
        <p class="reveal" style="font-family:'Cormorant Garamond',serif;font-size:15px;letter-spacing:3px;text-transform:uppercase;color:rgba(240,210,160,0.75);">on the occasion of their marriage ceremony</p>
    </div>
</section>
@endif

<!-- EVENT DETAILS -->
@if($sec['details'] ?? true)
<section id="details">
    <div class="section">
        <span class="section-label reveal">The Celebration</span>
        <h2 class="section-title reveal">Event Details</h2>
        <div class="gold-divider reveal"><span>✦</span></div>

        <div class="details-grid reveal">
            <div class="detail-card">
                <span class="detail-icon">📅</span>
                <div class="detail-label">Date</div>
                <div class="detail-value">{{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }}</div>
                <div class="detail-sub">{{ $d['dayOfWeek'] }}, {{ $d['year'] }}</div>
            </div>
            <div class="detail-card">
                <span class="detail-icon">⏰</span>
                <div class="detail-label">Poruwa Ceremony</div>
                <div class="detail-value">{{ strtoupper($invitation->poruwa_time) }}</div>
                <div class="detail-sub">Auspicious time</div>
            </div>
            <div class="detail-card">
                <span class="detail-icon">🕐</span>
                <div class="detail-label">Reception</div>
                <div class="detail-value">{{ strtoupper($invitation->duration_from) }}</div>
                <div class="detail-sub">Until {{ strtoupper($invitation->duration_to) }}</div>
            </div>
            <div class="detail-card">
                <span class="detail-icon">📍</span>
                <div class="detail-label">Venue</div>
                <div class="detail-value">{{ $d['venueName'] }}</div>
                <div class="detail-sub">{{ $d['venueCity'] }}</div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- COUNTDOWN -->
@if($sec['countdown'] ?? true)
<section id="countdown">
    <div class="section">
        <span class="section-label reveal">Counting Down</span>
        <h2 class="section-title reveal">The Big Day</h2>
        <div class="gold-divider reveal"><span>✦</span></div>
        <div class="countdown-grid reveal" id="countdown-grid">
            <div class="cd-box"><span class="cd-num" id="cd-days">--</span><span class="cd-label">Days</span></div>
            <div class="cd-box"><span class="cd-num" id="cd-hours">--</span><span class="cd-label">Hours</span></div>
            <div class="cd-box"><span class="cd-num" id="cd-mins">--</span><span class="cd-label">Minutes</span></div>
            <div class="cd-box"><span class="cd-num" id="cd-secs">--</span><span class="cd-label">Seconds</span></div>
        </div>
    </div>
</section>
@endif

<!-- TIMELINE -->
@if($sec['timeline'] ?? true)
<section id="timeline">
    <div class="section">
        <span class="section-label reveal">The Day's Journey</span>
        <h2 class="section-title reveal">Order of Events</h2>
        <div class="gold-divider reveal"><span>🌹</span></div>

        <div class="timeline-list">
            @foreach($timelineEvents as $i => $ev)
            <div class="tl-item reveal">
                @if($i % 2 === 0)
                <div class="tl-left"><div class="tl-time">{{ strtoupper($ev['time']) }}</div><div class="tl-event">{{ $ev['event'] }}</div>@if($ev['desc'])<div class="tl-desc">{{ $ev['desc'] }}</div>@endif</div>
                <div class="tl-dot"></div>
                <div class="tl-right"></div>
                @else
                <div class="tl-left"></div>
                <div class="tl-dot"></div>
                <div class="tl-right"><div class="tl-time">{{ strtoupper($ev['time']) }}</div><div class="tl-event">{{ $ev['event'] }}</div>@if($ev['desc'])<div class="tl-desc">{{ $ev['desc'] }}</div>@endif</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- VENUE -->
@if($sec['venue'] ?? true)
<section id="venue">
    <div class="section">
        <span class="section-label reveal">Find Us</span>
        <h2 class="section-title reveal">The Venue</h2>
        <div class="gold-divider reveal"><span>📍</span></div>

        <div class="venue-map reveal">
            @if($invitation->venue_map_url)
            <iframe
                src="{{ $invitation->venue_map_url }}"
                width="100%" height="260"
                style="border:0;display:block;"
                allowfullscreen loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
            @else
            <div class="map-placeholder">
                <span>🗺️</span>
                <p>{{ $d['venueName'] }}</p>
                @if($d['venueCity'])<small>{{ $d['venueCity'] }}</small>@endif
            </div>
            @endif
            <div class="venue-details">
                <div class="venue-name">{{ $d['venueName'] }}</div>
                @if($d['venueCity'])<div class="venue-address">{{ $d['venueCity'] }}</div>@endif
                @if($invitation->venue_map_url)
                <a href="{{ $invitation->venue_map_url }}" target="_blank" class="venue-btn">📍 Open in Maps</a>
                @else
                <a href="https://maps.google.com/?q={{ urlencode($invitation->wedding_location) }}" target="_blank" class="venue-btn">📍 Search on Maps</a>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

<!-- RSVP -->
@if($sec['rsvp'] ?? true)
<section id="rsvp">
    <div class="section">
        <span class="section-label reveal">You're Invited</span>
        <h2 class="section-title reveal">RSVP</h2>
        <div class="gold-divider reveal"><span>✦</span></div>

@php
    $guestSide  = isset($guest) ? ($guest->group?->slug ?? '') : '';
    $waNumber   = ($guestSide === 'bride-side' && $invitation->bride_phone)
                    ? $invitation->bride_phone
                    : ($invitation->groom_phone ?? $invitation->bride_phone ?? '');
    $waNumber   = preg_replace('/\D/', '', $waNumber);
    $guestLabel = isset($guestName) ? $guestName : 'A Guest';
    $coupleName = $invitation->bride_name . ' & ' . $invitation->groom_name;
    $isTokenView = isset($token);
    $savedRsvp   = session('rsvp_saved');
    $savedWaNum  = session('rsvp_wa_number', '');
    $savedLabel  = session('rsvp_guest_label', $guestLabel);
    $savedCouple = session('rsvp_couple', $coupleName);
    $savedDay    = session('rsvp_wedding_day', $invitation->wedding_day ?? '');
@endphp
        <div class="rsvp-box reveal">
            @if(isset($guestName))
            <div class="rsvp-guest">Dear {{ $guestName }},</div>
            @endif
            <p class="rsvp-msg">
                We joyfully request the honour of your presence<br>
                to celebrate our union on this blessed day.
            </p>

            @if($waNumber)
            {{-- Single RSVP UI for both token and preview views --}}
            <div class="rsvp-choices">
                <button type="button" class="rsvp-btn" id="btn-accepted" onclick="selectRsvp('accepted')">✓ Accept</button>
                <button type="button" class="rsvp-btn" id="btn-declined" onclick="selectRsvp('declined')">✕ Decline</button>
                <button type="button" class="rsvp-btn" id="btn-maybe" onclick="selectRsvp('maybe')">? Maybe</button>
            </div>
            <p class="rsvp-hint" id="rsvp-hint">Select your response, then send via WhatsApp</p>
            <a id="rsvp-wa-btn" href="#" class="rsvp-whatsapp disabled" onclick="return sendRsvp(event)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                <span id="rsvp-wa-label">Send Response</span>
            </a>
            @else
            <p class="rsvp-no-contact">Contact details not yet added.</p>
            @endif
        </div>

        <script>
        const waNumber   = '{{ $waNumber }}';
        const guestLabel = '{{ addslashes($guestLabel) }}';
        const coupleName = '{{ addslashes($coupleName) }}';
        const weddingDay = '{{ addslashes($invitation->wedding_day ?? '') }}';
        const rsvpUrl    = '{{ isset($token) ? route('invite.rsvp', $token) : '' }}';
        const csrfToken  = '{{ csrf_token() }}';
        let selectedRsvp = null;

        const messages = {
            accepted: `Hi! This is ${guestLabel}. I am happy to confirm that I will be ATTENDING the wedding of ${coupleName} on ${weddingDay}. 🌹`,
            declined: `Hi! This is ${guestLabel}. I regret to inform you that I will NOT be able to attend the wedding of ${coupleName} on ${weddingDay}. Wishing you a wonderful day! 🙏`,
            maybe:    `Hi! This is ${guestLabel}. Regarding the wedding of ${coupleName} on ${weddingDay} — I am UNSURE if I can attend and will confirm as soon as possible. 🌸`,
        };
        const btnLabels = {
            accepted: '✓ Send Acceptance',
            declined: '✕ Send Decline',
            maybe:    '? Send Maybe',
        };

        function selectRsvp(choice) {
            selectedRsvp = choice;
            ['accepted','declined','maybe'].forEach(c => {
                const btn = document.getElementById('btn-' + c);
                const map = { accepted: 'accept', declined: 'decline', maybe: 'maybe' };
                btn.className = 'rsvp-btn' + (c === choice ? ' selected-' + map[c] : '');
            });
            const waBtn = document.getElementById('rsvp-wa-btn');
            waBtn.classList.remove('disabled');
            waBtn.href = 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent(messages[choice]);
            document.getElementById('rsvp-wa-label').textContent = btnLabels[choice];
        }

        function sendRsvp(e) {
            if (!selectedRsvp || !rsvpUrl) return true; // preview mode: just open WA
            // Save to DB in background, then open WhatsApp
            fetch(rsvpUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ rsvp: selectedRsvp }),
            }).catch(() => {}); // fire and forget
            // Let the href (WhatsApp link) open normally
            return true;
        }
        </script>
    </div>
</section>
@endif

<!-- FOOTER -->
<footer id="footer">
    <p>{{ $invitation->bride_name }} &amp; {{ $invitation->groom_name }}</p>
    <small>{{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }} {{ $d['year'] }} &nbsp;·&nbsp; With Love</small>
</footer>

</div><!-- end #main-content -->

<script>
// ── Envelope open ──
function openEnvelope() {
    const wrap = document.getElementById('env-wrap');
    const seal = document.getElementById('wax-seal');
    const scene = document.getElementById('envelope-scene');
    const main = document.getElementById('main-content');

    // Break wax seal
    seal.classList.add('break');

    setTimeout(() => {
        wrap.classList.add('open');
    }, 400);

    setTimeout(() => {
        scene.classList.add('hide');
        main.classList.add('visible');
        document.body.style.overflow = 'auto';
        triggerReveal();
        if (window.parent !== window) window.parent.postMessage('envelope-opened', '*');
    }, 2200);
}

// ── Scroll reveal ──
function triggerReveal() {
    const els = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver((entries) => {
        entries.forEach((e, i) => {
            if (e.isIntersecting) {
                setTimeout(() => e.target.classList.add('visible'), i * 80);
                io.unobserve(e.target);
            }
        });
    }, { threshold: 0.15 });
    els.forEach(el => io.observe(el));
}

// ── Countdown ──
@php
    // Parse wedding date from invitation
    $wDate = $invitation->wedding->wedding_date;
    $weddingTs = $wDate ? $wDate->timestamp * 1000 : 0;
@endphp
const weddingTime = {{ $weddingTs }};

function updateCountdown() {
    const now = Date.now();
    const diff = weddingTime - now;
    if (diff <= 0) {
        document.getElementById('cd-days').textContent  = '0';
        document.getElementById('cd-hours').textContent = '0';
        document.getElementById('cd-mins').textContent  = '0';
        document.getElementById('cd-secs').textContent  = '0';
        return;
    }
    const days  = Math.floor(diff / 86400000);
    const hours = Math.floor((diff % 86400000) / 3600000);
    const mins  = Math.floor((diff % 3600000)  / 60000);
    const secs  = Math.floor((diff % 60000)    / 1000);
    document.getElementById('cd-days').textContent  = String(days).padStart(2,'0');
    document.getElementById('cd-hours').textContent = String(hours).padStart(2,'0');
    document.getElementById('cd-mins').textContent  = String(mins).padStart(2,'0');
    document.getElementById('cd-secs').textContent  = String(secs).padStart(2,'0');
}
updateCountdown();
setInterval(updateCountdown, 1000);

// ── Music toggle ──
@if($sec['music'] ?? true)
let musicOn = false;
const audio = document.getElementById('bg-music');
const musicBtn = document.getElementById('music-btn');
function toggleMusic() {
    if (musicOn) {
        audio.pause();
        musicBtn.textContent = '🎵 Music Off';
        musicOn = false;
    } else {
        audio.play().catch(() => {});
        musicBtn.textContent = '🎵 Music On';
        musicOn = true;
    }
}
@else
function toggleMusic() {}
@endif

// ── Prevent scroll until envelope opened ──
@if(!$isPreviewPane)
document.body.style.overflow = 'hidden';
@else
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.reveal').forEach(el => el.classList.add('visible'));
});
@endif
</script>
</body>
</html>
