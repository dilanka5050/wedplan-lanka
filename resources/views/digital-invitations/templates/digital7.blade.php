@php
    $d              = $invitation->parsedDay();
    $cfg            = $invitation->resolvedConfig();
    $sec            = $cfg['sections'];
    $heroVideo      = $cfg['hero_video']
        ? asset('hero_video/' . $cfg['hero_video'] . '.mp4')
        : asset('hero_video/video_hero1.mp4');
    $timelineEvents = $cfg['timeline_events'] ?? [];
    $loveStoryItems = $cfg['love_story_items'] ?? [];
    $isPreviewPane  = request()->boolean('preview');
    $galleryPhotos  = $invitation->wedding->files()->where('usage', 'invitation_gallery')->get();
    $showGallery    = ($sec['gallery'] ?? true) && $galleryPhotos->count() > 0;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $invitation->bride_name }} & {{ $invitation->groom_name }} — Midnight Wedding</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,400&family=Great+Vibes&family=Cinzel:wght@400;600&display=swap" rel="stylesheet">
<style>
:root{
  --night:#04060f;--night-mid:#080d1e;--night-deep:#020408;
  --navy:#0b1230;--navy-light:#111d3d;
  --silver:#c8d8f0;--silver-light:#e8f0ff;--silver-pale:#f0f4ff;
  --moon:#d4e4ff;--moon-glow:rgba(180,210,255,0.6);
  --star:#ffffff;
  --text-silver:rgba(200,216,240,0.8);--text-moon:rgba(212,228,255,0.7);
}
*{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{background:var(--night);font-family:'Cormorant Garamond',serif;color:var(--silver-pale);overflow-x:hidden;}

/* ── Stars background ── */
.star-field{position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden;}
.star-dot{position:absolute;background:#fff;border-radius:50%;animation:twinkle ease-in-out infinite;}
@keyframes twinkle{0%,100%{opacity:0.15;transform:scale(0.8);}50%{opacity:1;transform:scale(1.2);}}
.shooting-star{position:fixed;width:1px;height:60px;background:linear-gradient(to bottom,transparent,rgba(255,255,255,0.9));transform:rotate(-45deg);pointer-events:none;animation:shoot linear infinite;opacity:0;}
@keyframes shoot{0%{transform:translate(0,0) rotate(-45deg);opacity:0;}15%{opacity:1;}100%{transform:translate(280px,280px) rotate(-45deg);opacity:0;}}

/* ── Loading Screen ── */
#loading-screen{
  position:fixed;inset:0;z-index:9999;
  background:radial-gradient(ellipse at center,#0b1230 0%,#04060f 70%);
  display:flex;flex-direction:column;align-items:center;justify-content:center;gap:28px;
  transition:opacity 1.2s ease,visibility 1.2s ease;
}
#loading-screen.fade-out{opacity:0;visibility:hidden;}
.loading-moon{font-size:52px;animation:moonFloat 3s ease-in-out infinite;}
@keyframes moonFloat{0%,100%{transform:translateY(0);}50%{transform:translateY(-10px);}}
.loading-ornament{
  font-family:'Great Vibes',cursive;font-size:clamp(34px,7vw,52px);
  color:var(--moon);text-shadow:0 0 40px var(--moon-glow);
  animation:loadPulse 2.5s ease-in-out infinite;
}
@keyframes loadPulse{0%,100%{opacity:0.5;}50%{opacity:1;}}
.loading-title{font-family:'Cinzel',serif;font-size:clamp(10px,1.8vw,13px);letter-spacing:6px;text-transform:uppercase;color:var(--text-silver);}
.loading-bar-wrap{width:min(240px,55vw);height:1px;background:rgba(180,210,255,0.15);position:relative;overflow:hidden;}
.loading-bar{position:absolute;left:-100%;top:0;height:100%;width:100%;background:linear-gradient(90deg,transparent,var(--moon),transparent);animation:loadBar 2s ease-in-out infinite;}
@keyframes loadBar{0%{left:-100%;}100%{left:100%;}}
.loading-sub{font-size:11px;letter-spacing:3px;text-transform:uppercase;color:rgba(180,210,255,0.35);}

/* ── Toolbar ── */
.toolbar{
  position:fixed;top:0;left:0;right:0;z-index:500;
  display:flex;align-items:center;justify-content:space-between;
  padding:10px 24px;
  background:rgba(4,6,15,0.82);backdrop-filter:blur(14px);
  border-bottom:1px solid rgba(180,210,255,0.12);
}
.toolbar a,.toolbar button{
  font-family:sans-serif;font-size:11px;color:var(--moon);
  text-decoration:none;padding:5px 14px;
  border:1px solid rgba(180,210,255,0.25);border-radius:999px;
  background:transparent;cursor:pointer;transition:background 0.2s;
}
.toolbar a:hover,.toolbar button:hover{background:rgba(180,210,255,0.08);}
.toolbar span{font-family:sans-serif;font-size:11px;color:var(--moon);letter-spacing:1px;}
@media print{.toolbar{display:none;}}
</style>
</head>
<body>

<!-- ── Star field ── -->
<div class="star-field" id="star-field"></div>
@for($i=0;$i<5;$i++)
<div class="shooting-star" style="top:{{ rand(5,25) }}%;left:{{ rand(-10,40) }}%;animation-duration:{{ rand(8,16) }}s;animation-delay:{{ rand(0,12000)/1000 }}s;"></div>
@endfor

@if(!$isPreviewPane)
<!-- ══ LOADING SCREEN ══ -->
<div id="loading-screen">
  <div class="loading-moon">🌙</div>
  <div class="loading-ornament">A Night to Remember…</div>
  <div class="loading-title">Preparing Your Invitation</div>
  <div class="loading-bar-wrap"><div class="loading-bar"></div></div>
  <div class="loading-sub">Please wait a moment</div>
</div>
@endif

<!-- ══ TOOLBAR ══ -->
<div class="toolbar">
  @if(isset($token))
    <span>💌 Wedding Invitation</span>
  @elseif(isset($guest))
    <a href="{{ route('guests.index') }}">← Back to Guests</a>
    <span>💌 {{ $guest->name }}</span>
  @else
    <a href="{{ route('digital-invitations.index') }}">← Templates</a>
    <span>Midnight Wedding</span>
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
<style>body{overflow:auto !important;} #main-content{opacity:1 !important;}</style>
@endif

<style>
/* ── Hero Video ── */
#hero-video-wrap{position:relative;width:100%;height:100vh;overflow:hidden;background:var(--night);z-index:1;}
#hero-video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;}
#hero-video-wrap::before,#hero-video-wrap::after{
  content:'';position:absolute;left:0;right:0;height:6vh;
  background:var(--night);z-index:2;pointer-events:none;transition:height 1.5s ease;
}
#hero-video-wrap::before{top:0;}
#hero-video-wrap::after{bottom:0;}
#hero-video-wrap.bars-gone::before,#hero-video-wrap.bars-gone::after{height:0;}
.hero-vignette{position:absolute;inset:0;z-index:3;pointer-events:none;background:radial-gradient(ellipse at center,transparent 35%,rgba(4,6,15,0.75) 100%);}
.hero-fade-bottom{position:absolute;bottom:0;left:0;right:0;height:40%;z-index:4;pointer-events:none;background:linear-gradient(to bottom,transparent,var(--night));}

/* Flash — night version: deep blue-white */
#flash{position:fixed;inset:0;z-index:800;background:radial-gradient(ellipse at center,#c8e0ff,#ffffff);opacity:0;pointer-events:none;transition:opacity 0.15s ease;}
#flash.flash-on{opacity:1;}
#flash.flash-fade{opacity:0;transition:opacity 2s ease;}

/* Hero text */
#hero-text{
  position:absolute;inset:0;z-index:6;
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  text-align:center;padding:80px 20px 60px;
  opacity:0;pointer-events:none;transition:opacity 1.5s ease;
  background:linear-gradient(to bottom,rgba(4,6,15,0.45) 0%,rgba(4,6,15,0.65) 50%,rgba(4,6,15,0.88) 100%);
}
#hero-text.visible{opacity:1;pointer-events:auto;}
.hero-text-card{
  position:relative;
  background:rgba(4,6,15,0.65);
  backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);
  border:1px solid rgba(180,210,255,0.2);
  padding:44px 48px 40px;width:min(580px,92vw);
  box-shadow:0 8px 60px rgba(0,0,0,0.8),0 0 80px rgba(100,160,255,0.06),inset 0 1px 0 rgba(180,210,255,0.1);
}
.hero-text-card::before{content:'';position:absolute;inset:7px;border:1px solid rgba(180,210,255,0.07);pointer-events:none;}
.hero-pre-line{
  font-family:'Cinzel',serif;font-size:clamp(9px,1.4vw,11px);
  letter-spacing:6px;text-transform:uppercase;color:#b8d4ff;
  margin-bottom:20px;animation:fadeUp 1.2s ease both;animation-delay:0.3s;
}
.hero-names-wrap{animation:fadeUp 1.4s ease both;animation-delay:0.6s;}
.hero-name{
  font-family:'Great Vibes',cursive;font-size:clamp(48px,11vw,88px);
  color:#eef4ff;line-height:1.1;
  text-shadow:0 0 40px rgba(140,190,255,0.9),0 0 80px rgba(100,160,255,0.4),0 2px 8px rgba(0,0,0,0.95);
}
.hero-amp{display:block;font-size:clamp(28px,5vw,48px);color:#8ab4ff;margin:2px 0;text-shadow:0 0 30px rgba(100,160,255,0.8),0 2px 6px rgba(0,0,0,0.9);}
.hero-invite-line{
  font-family:'Cormorant Garamond',serif;font-size:clamp(13px,2.2vw,17px);
  letter-spacing:3px;color:#d8e8ff;margin-top:18px;font-style:italic;
  text-shadow:0 1px 6px rgba(0,0,0,0.95);animation:fadeUp 1.4s ease both;animation-delay:0.9s;
}
.hero-date-line{
  font-family:'Cinzel',serif;font-size:clamp(12px,2vw,15px);
  letter-spacing:4px;color:#b8d4ff;margin-top:12px;
  text-shadow:0 0 20px rgba(100,160,255,0.7),0 1px 6px rgba(0,0,0,0.95);
  animation:fadeUp 1.4s ease both;animation-delay:1.1s;
}
.hero-venue-line{
  font-size:12px;letter-spacing:2px;color:#8aa8d8;margin-top:8px;
  text-shadow:0 1px 4px rgba(0,0,0,0.95);animation:fadeUp 1.4s ease both;animation-delay:1.3s;
}
.hero-divider{
  display:flex;align-items:center;justify-content:center;gap:12px;
  margin:16px auto;width:min(260px,70vw);animation:fadeUp 1.4s ease both;animation-delay:0.8s;
}
.hero-divider::before,.hero-divider::after{content:'';flex:1;height:1px;background:linear-gradient(90deg,transparent,rgba(140,190,255,0.6));}
.hero-divider::after{background:linear-gradient(90deg,rgba(140,190,255,0.6),transparent);}
.hero-divider span{color:#8ab4ff;font-size:13px;}
@keyframes fadeUp{from{opacity:0;transform:translateY(24px);}to{opacity:1;transform:translateY(0);}}
.scroll-hint{
  position:absolute;bottom:28px;left:50%;transform:translateX(-50%);
  display:flex;flex-direction:column;align-items:center;gap:6px;
  color:rgba(140,190,255,0.6);font-family:sans-serif;font-size:10px;
  letter-spacing:2px;text-transform:uppercase;
  animation:bounce 2s ease-in-out infinite;opacity:0;transition:opacity 1s ease;
}
.scroll-hint.visible{opacity:1;}
@keyframes bounce{0%,100%{transform:translateX(-50%) translateY(0);}50%{transform:translateX(-50%) translateY(7px);}}
.scroll-chevron{width:18px;height:18px;border-right:1px solid rgba(140,190,255,0.5);border-bottom:1px solid rgba(140,190,255,0.5);transform:rotate(45deg);}
</style>

@if(!$isPreviewPane)
<!-- ══ FLASH ══ -->
<div id="flash"></div>
@endif

<!-- ══ HERO VIDEO ══ -->
@if(!$isPreviewPane)
<section id="hero-video-wrap">
  <video id="hero-video" muted playsinline preload="auto" poster="{{ asset('back2.png') }}">
    <source src="{{ $heroVideo }}" type="video/mp4">
  </video>
  <div class="hero-vignette"></div>
  <div class="hero-fade-bottom"></div>
  <div id="hero-text">
    <div class="hero-text-card">
      <p class="hero-pre-line">Together With Their Families</p>
      <div class="hero-names-wrap">
        <div class="hero-name">{{ $invitation->bride_name }}</div>
        <span class="hero-amp">&</span>
        <div class="hero-name">{{ $invitation->groom_name }}</div>
      </div>
      <div class="hero-divider"><span>✦</span></div>
      <p class="hero-invite-line">Invite You To Celebrate Their Wedding</p>
      <p class="hero-date-line">{{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }} {{ $d['year'] }}</p>
      <p class="hero-venue-line">{{ $d['venueName'] }}@if($d['venueCity']), {{ $d['venueCity'] }}@endif</p>
    </div>
  </div>
  <div class="scroll-hint" id="scroll-hint"><div class="scroll-chevron"></div><span>Scroll</span></div>
</section>
@else
<section style="background:radial-gradient(ellipse at center,#0b1230 0%,#04060f 70%);padding:60px 20px;text-align:center;">
  <div class="hero-text-card" style="margin:0 auto;opacity:1;position:relative;">
    <p class="hero-pre-line">Together With Their Families</p>
    <div class="hero-names-wrap">
      <div class="hero-name">{{ $invitation->bride_name }}</div>
      <span class="hero-amp">&</span>
      <div class="hero-name">{{ $invitation->groom_name }}</div>
    </div>
    <div class="hero-divider"><span>✦</span></div>
    <p class="hero-invite-line">Invite You To Celebrate Their Wedding</p>
    <p class="hero-date-line">{{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }} {{ $d['year'] }}</p>
    <p class="hero-venue-line">{{ $d['venueName'] }}@if($d['venueCity']), {{ $d['venueCity'] }}@endif</p>
  </div>
</section>
@endif

<style>
/* ── Shared ── */
#main-content{opacity:0;transition:opacity 1s ease;}
#main-content.visible{opacity:1;}
.section{padding:90px 20px;max-width:800px;margin:0 auto;text-align:center;position:relative;z-index:1;}
.section-label{font-family:'Cinzel',serif;font-size:11px;letter-spacing:5px;text-transform:uppercase;color:var(--silver);margin-bottom:14px;display:block;}
.section-title{font-family:'Playfair Display',serif;font-size:clamp(28px,5vw,44px);color:var(--silver-pale);font-weight:600;line-height:1.2;}
.section-intro{font-size:15px;color:var(--text-moon);line-height:1.9;margin-top:14px;font-style:italic;}
.silver-divider{display:flex;align-items:center;justify-content:center;gap:12px;margin:22px auto;}
.silver-divider::before,.silver-divider::after{content:'';flex:1;max-width:120px;height:1px;background:linear-gradient(90deg,transparent,var(--silver));}
.silver-divider::after{background:linear-gradient(90deg,var(--silver),transparent);}
.silver-divider span{color:var(--silver);font-size:15px;}
.reveal{opacity:0;transform:translateY(28px);transition:opacity 0.9s ease,transform 0.9s ease;}
.reveal.visible{opacity:1;transform:translateY(0);}

/* ── Invitation ── */
#invitation{background:linear-gradient(180deg,var(--night) 0%,var(--navy) 50%,var(--night) 100%);padding:80px 20px;text-align:center;position:relative;overflow:hidden;}
#invitation::before{content:'🌙';position:absolute;top:30px;right:8%;font-size:48px;opacity:0.12;pointer-events:none;}
.floral-top,.floral-bottom{text-align:center;font-size:16px;color:rgba(180,210,255,0.25);letter-spacing:8px;padding:12px 0;}
.floral-row{font-size:20px;letter-spacing:10px;margin-top:28px;}
.inv-quote{
  font-family:'Playfair Display',serif;font-size:clamp(15px,2.8vw,21px);
  font-style:italic;color:var(--moon);
  border-left:2px solid rgba(180,210,255,0.3);padding-left:20px;
  max-width:560px;margin:28px auto;text-align:left;line-height:1.8;
}
.inv-body{font-size:16px;color:var(--text-moon);line-height:2;max-width:500px;margin:0 auto;}
.inv-names{font-family:'Great Vibes',cursive;font-size:clamp(28px,5vw,38px);color:var(--moon);display:block;margin:6px 0;}
.inv-details{display:flex;align-items:center;justify-content:center;gap:24px;margin-top:32px;flex-wrap:wrap;}
.inv-detail-item{display:flex;flex-direction:column;align-items:center;gap:6px;padding:20px 28px;background:rgba(180,210,255,0.04);border:1px solid rgba(180,210,255,0.14);}
.inv-detail-icon{font-size:28px;}
.inv-detail-label{font-size:10px;letter-spacing:3px;text-transform:uppercase;color:var(--silver);}
.inv-detail-val{font-family:'Playfair Display',serif;font-size:16px;color:var(--silver-pale);}
.inv-detail-sep{font-size:24px;color:rgba(180,210,255,0.25);}

/* ── Couple ── */
#couple{background:linear-gradient(180deg,var(--night) 0%,var(--night-mid) 100%);}
.couple-grid{display:grid;grid-template-columns:1fr auto 1fr;gap:24px;align-items:center;max-width:720px;margin:44px auto 0;}
.couple-card{padding:36px 24px;background:rgba(180,210,255,0.03);border:1px solid rgba(180,210,255,0.14);position:relative;}
.couple-card::before{content:'';position:absolute;inset:6px;border:1px solid rgba(180,210,255,0.05);pointer-events:none;}
.couple-emoji{font-size:44px;margin-bottom:14px;display:block;}
.couple-name{font-family:'Great Vibes',cursive;font-size:clamp(28px,5vw,40px);color:var(--moon);}
.couple-surname{font-size:11px;letter-spacing:3px;text-transform:uppercase;color:var(--text-silver);margin-top:5px;}
.couple-parents{font-size:13px;color:rgba(200,216,240,0.35);margin-top:12px;line-height:1.8;font-style:italic;}
.couple-and{font-family:'Great Vibes',cursive;font-size:56px;color:var(--silver);text-align:center;}

/* ── Invitee ── */
#invitee{background:linear-gradient(135deg,var(--navy),var(--night));padding:80px 20px;}
.invitee-inner{max-width:600px;margin:0 auto;text-align:center;}
.invitee-label{font-family:'Cinzel',serif;font-size:11px;letter-spacing:5px;text-transform:uppercase;color:var(--silver);margin-bottom:16px;display:block;}
.invitee-pre{font-size:14px;letter-spacing:2px;text-transform:uppercase;color:var(--text-moon);margin-bottom:20px;}
.invitee-name{font-family:'Great Vibes',cursive;font-size:clamp(40px,9vw,64px);color:var(--moon);line-height:1.2;margin-bottom:12px;text-shadow:0 0 40px rgba(140,190,255,0.4);}
.invitee-plus{font-size:13px;letter-spacing:2px;color:var(--text-silver);}
.invitee-msg{font-size:14px;color:var(--text-moon);line-height:1.9;font-style:italic;margin-top:8px;}
.invitee-quote{font-family:'Playfair Display',serif;font-size:16px;font-style:italic;color:var(--silver);margin-top:20px;}
.ornament-row{display:flex;align-items:center;justify-content:center;gap:10px;margin:20px auto;color:var(--silver);font-size:13px;}
.ornament-line{flex:1;max-width:80px;height:1px;background:rgba(180,210,255,0.25);}
</style>

<!-- ══ MAIN CONTENT ══ -->
<div id="main-content" class="{{ $isPreviewPane ? 'visible' : '' }}">

<!-- ── INVITATION ── -->
<section id="invitation">
  <div class="floral-top">✦ ☽ ✦ ☽ ✦</div>
  <div class="section">
    <span class="section-label reveal">— A Sacred Union —</span>
    <h2 class="section-title reveal">With Great Joy &amp; Love</h2>
    <div class="silver-divider reveal"><span>✦</span></div>
    <blockquote class="inv-quote reveal">
      &ldquo; Two souls, one heart — bound by love, blessed by the stars above. &rdquo;
    </blockquote>
    <p class="inv-body reveal">
      Together with their beloved families,<br>
      <strong class="inv-names">{{ $invitation->bride_name }} &amp; {{ $invitation->groom_name }}</strong><br>
      joyfully invite you to witness and celebrate<br>the sacred union of their marriage.
    </p>
    <div class="inv-details reveal">
      <div class="inv-detail-item"><span class="inv-detail-icon">👰</span><span class="inv-detail-label">Bride</span><span class="inv-detail-val">{{ $invitation->bride_name }} {{ $invitation->bride_surname }}</span></div>
      <div class="inv-detail-sep">✦</div>
      <div class="inv-detail-item"><span class="inv-detail-icon">🤵</span><span class="inv-detail-label">Groom</span><span class="inv-detail-val">{{ $invitation->groom_name }} {{ $invitation->groom_surname }}</span></div>
    </div>
    <div class="floral-row reveal">🌙 ✦ ⭐ ✦ 🌙</div>
  </div>
  <div class="floral-bottom">✦ ☽ ✦ ☽ ✦</div>
</section>

<!-- ── COUPLE ── -->
<section id="couple">
  <div class="section">
    <span class="section-label reveal">The Beloved Couple</span>
    <h2 class="section-title reveal">Two Hearts, One Destiny</h2>
    <div class="silver-divider reveal"><span>✦</span></div>
    <p class="section-intro reveal">Two souls who found each other beneath the same sky, now choosing to walk every path together, forever.</p>
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

<!-- ── INVITEE ── -->
@if(isset($guest))
<section id="invitee">
  <div class="invitee-inner">
    <div class="ornament-row reveal"><span class="ornament-line"></span><span>✦ ☽ ✦</span><span class="ornament-line"></span></div>
    <span class="invitee-label reveal">A Personal Invitation</span>
    <p class="invitee-pre reveal">With great joy, we extend this invitation to</p>
    <div class="invitee-name reveal">{{ $guest->name }}</div>
    @if($guest->total_invited > 1)
    <p class="invitee-plus reveal">& {{ $guest->total_invited - 1 }} {{ $guest->total_invited - 1 === 1 ? 'cherished guest' : 'cherished guests' }}</p>
    @endif
    <div class="silver-divider reveal" style="margin:24px auto;"><span>🌙</span></div>
    <p class="invitee-msg reveal">Your presence would grace our celebration and fill our hearts with immeasurable joy on this most sacred of nights.</p>
    <p class="invitee-quote reveal">&ldquo; Where there is love, there is life. &rdquo;</p>
  </div>
</section>
@endif

<style>
/* ── Love Story ── */
#love-story{background:var(--night);padding:90px 20px;position:relative;overflow:hidden;}
#love-story::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 20% 50%,rgba(60,100,200,0.06) 0%,transparent 60%),radial-gradient(ellipse at 80% 50%,rgba(60,100,200,0.06) 0%,transparent 60%);pointer-events:none;}
.ls-timeline{max-width:700px;margin:50px auto 0;position:relative;}
.ls-line{position:absolute;left:50%;top:0;bottom:0;width:1px;background:linear-gradient(to bottom,transparent,var(--silver) 15%,var(--silver) 85%,transparent);transform:translateX(-50%);}
.ls-item{display:grid;grid-template-columns:1fr 60px 1fr;gap:0;margin-bottom:48px;align-items:center;}
.ls-icon-wrap{display:flex;align-items:center;justify-content:center;position:relative;z-index:1;}
.ls-icon{width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-light));border:2px solid var(--silver);box-shadow:0 0 20px rgba(140,190,255,0.35);display:flex;align-items:center;justify-content:center;font-size:20px;}
.ls-card-left{padding-right:24px;text-align:right;}
.ls-card-right{padding-left:24px;text-align:left;}
.ls-year{font-family:'Cinzel',serif;font-size:10px;letter-spacing:3px;text-transform:uppercase;color:var(--silver);margin-bottom:6px;}
.ls-title{font-family:'Playfair Display',serif;font-size:18px;color:var(--silver-pale);font-weight:600;margin-bottom:6px;}
.ls-desc{font-size:13px;color:var(--text-moon);line-height:1.8;font-style:italic;}

/* ── Details ── */
#details{background:var(--night-mid);}
.details-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:20px;margin-top:44px;}
.detail-card{padding:30px 18px;background:rgba(180,210,255,0.03);border:1px solid rgba(180,210,255,0.12);border-top:2px solid var(--silver);text-align:center;transition:transform 0.3s ease,box-shadow 0.3s ease;}
.detail-card:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(100,160,255,0.1);}
.detail-icon{font-size:26px;margin-bottom:10px;display:block;}
.detail-label{font-size:10px;letter-spacing:3px;text-transform:uppercase;color:var(--silver);margin-bottom:7px;}
.detail-value{font-family:'Playfair Display',serif;font-size:17px;color:var(--silver-pale);font-weight:600;}
.detail-sub{font-size:12px;color:var(--text-moon);margin-top:5px;}

/* ── Countdown ── */
#countdown{background:linear-gradient(135deg,var(--navy),var(--night));position:relative;overflow:hidden;}
#countdown::before{content:'FOREVER';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-family:'Cinzel',serif;font-size:clamp(60px,15vw,140px);font-weight:600;color:rgba(180,210,255,0.03);letter-spacing:10px;white-space:nowrap;pointer-events:none;}
.countdown-grid{display:grid;grid-template-columns:1fr auto 1fr auto 1fr auto 1fr;align-items:center;gap:8px;max-width:520px;margin:44px auto 0;}
.cd-box{background:rgba(0,0,0,0.4);border:1px solid rgba(180,210,255,0.15);padding:22px 10px;text-align:center;}
.cd-num{font-family:'Playfair Display',serif;font-size:clamp(32px,6vw,52px);color:var(--moon);font-weight:700;line-height:1;display:block;}
.cd-label{font-size:10px;letter-spacing:3px;text-transform:uppercase;color:rgba(180,210,255,0.45);margin-top:6px;display:block;}
.cd-sep{font-family:'Playfair Display',serif;font-size:clamp(24px,4vw,40px);color:rgba(180,210,255,0.35);text-align:center;padding-bottom:16px;}
.countdown-footer{font-size:12px;letter-spacing:3px;text-transform:uppercase;color:rgba(180,210,255,0.35);margin-top:24px;}

/* ── Timeline ── */
#timeline{background:var(--night);}
.timeline-wrap{max-width:600px;margin:44px auto 0;position:relative;}
.tl-line{position:absolute;left:50%;top:0;bottom:0;width:1px;background:linear-gradient(to bottom,transparent,var(--silver) 20%,var(--silver) 80%,transparent);transform:translateX(-50%);}
.tl-item{display:grid;grid-template-columns:1fr 44px 1fr;gap:0;margin-bottom:36px;align-items:center;}
.tl-content{padding:0 20px;}
.tl-left{text-align:right;}
.tl-right{text-align:left;}
.tl-node{display:flex;align-items:center;justify-content:center;position:relative;z-index:1;}
.tl-dot{width:14px;height:14px;border-radius:50%;background:var(--navy);border:2px solid var(--silver);box-shadow:0 0 12px rgba(140,190,255,0.5);}
.tl-time{font-size:11px;letter-spacing:2px;text-transform:uppercase;color:var(--silver);font-weight:600;}
.tl-event{font-family:'Playfair Display',serif;font-size:16px;color:var(--silver-pale);margin-top:3px;}
.tl-desc{font-size:12px;color:var(--text-moon);margin-top:3px;font-style:italic;line-height:1.6;}
</style>

<!-- ── LOVE STORY ── -->
<section id="love-story">
  <div class="section">
    <span class="section-label reveal">— How It All Began —</span>
    <h2 class="section-title reveal">Our Love Story</h2>
    <div class="silver-divider reveal"><span>🌙</span></div>
    <p class="section-intro reveal">Every great love story has a beginning. Here is ours.</p>
    <div class="ls-timeline">
      <div class="ls-line"></div>
      <div class="ls-item reveal">
        <div class="ls-card-left"><div class="ls-year">The First Meeting</div><div class="ls-title">When Eyes Met</div><p class="ls-desc">A chance encounter that felt written in the stars. Two strangers who somehow felt like home.</p></div>
        <div class="ls-icon-wrap"><div class="ls-icon">👋</div></div>
        <div></div>
      </div>
      <div class="ls-item reveal">
        <div></div>
        <div class="ls-icon-wrap"><div class="ls-icon">❤️</div></div>
        <div class="ls-card-right"><div class="ls-year">The Journey</div><div class="ls-title">Growing Together</div><p class="ls-desc">Through laughter, adventures and quiet moonlit moments, our bond grew deeper with every passing day.</p></div>
      </div>
      <div class="ls-item reveal">
        <div class="ls-card-left"><div class="ls-year">The Proposal</div><div class="ls-title">Will You?</div><p class="ls-desc">A perfect moment beneath the stars, a question from the heart, and a joyful &ldquo;Yes&rdquo; that changed everything.</p></div>
        <div class="ls-icon-wrap"><div class="ls-icon">💍</div></div>
        <div></div>
      </div>
      <div class="ls-item reveal">
        <div></div>
        <div class="ls-icon-wrap"><div class="ls-icon">💏</div></div>
        <div class="ls-card-right"><div class="ls-year">{{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }} {{ $d['year'] }}</div><div class="ls-title">The Wedding Day</div><p class="ls-desc">The night we say &ldquo;I do&rdquo; before our families and begin our forever together.</p></div>
      </div>
    </div>
  </div>
</section>

<!-- ── EVENT DETAILS ── -->
<section id="details">
  <div class="section">
    <span class="section-label reveal">Mark Your Calendar</span>
    <h2 class="section-title reveal">The Sacred Celebration</h2>
    <div class="silver-divider reveal"><span>✦</span></div>
    <p class="section-intro reveal">Every detail has been lovingly arranged to create a night of beauty, tradition and unforgettable memories.</p>
    <div class="details-grid reveal">
      <div class="detail-card"><span class="detail-icon">📅</span><div class="detail-label">Auspicious Date</div><div class="detail-value">{{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }}</div><div class="detail-sub">{{ $d['dayOfWeek'] }}, {{ $d['year'] }}</div></div>
      <div class="detail-card"><span class="detail-icon">⏰</span><div class="detail-label">Poruwa Ceremony</div><div class="detail-value">{{ strtoupper($invitation->poruwa_time) }}</div><div class="detail-sub">The sacred auspicious hour</div></div>
      <div class="detail-card"><span class="detail-icon">🍾</span><div class="detail-label">Reception Begins</div><div class="detail-value">{{ strtoupper($invitation->duration_from) }}</div><div class="detail-sub">Until {{ strtoupper($invitation->duration_to) }}</div></div>
      <div class="detail-card"><span class="detail-icon">📍</span><div class="detail-label">Venue</div><div class="detail-value">{{ $d['venueName'] }}</div><div class="detail-sub">{{ $d['venueCity'] }}</div></div>
    </div>
  </div>
</section>

<!-- ── COUNTDOWN ── -->
<section id="countdown">
  <div class="section">
    <span class="section-label reveal">The Anticipation</span>
    <h2 class="section-title reveal">The Moment Draws Near</h2>
    <div class="silver-divider reveal"><span>✦</span></div>
    <div class="countdown-grid reveal">
      <div class="cd-box"><span class="cd-num" id="cd-days">--</span><span class="cd-label">Days</span></div>
      <div class="cd-sep">:</div>
      <div class="cd-box"><span class="cd-num" id="cd-hours">--</span><span class="cd-label">Hours</span></div>
      <div class="cd-sep">:</div>
      <div class="cd-box"><span class="cd-num" id="cd-mins">--</span><span class="cd-label">Mins</span></div>
      <div class="cd-sep">:</div>
      <div class="cd-box"><span class="cd-num" id="cd-secs">--</span><span class="cd-label">Secs</span></div>
    </div>
    <p class="countdown-footer reveal">{{ $d['dayOfWeek'] }}, {{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }} {{ $d['year'] }}</p>
  </div>
</section>

<!-- ── TIMELINE ── -->
<section id="timeline">
  <div class="section">
    <span class="section-label reveal">A Night to Remember</span>
    <h2 class="section-title reveal">Order of Events</h2>
    <div class="silver-divider reveal"><span>✦</span></div>
    <div class="timeline-wrap">
      <div class="tl-line"></div>
      <div class="tl-item reveal">
        <div class="tl-content tl-left"><div class="tl-time">{{ strtoupper($invitation->duration_from) }}</div><div class="tl-event">Doors Open</div><div class="tl-desc">Guests welcomed with refreshments &amp; floral arrangements</div></div>
        <div class="tl-node"><div class="tl-dot"></div></div>
        <div class="tl-content tl-right"></div>
      </div>
      <div class="tl-item reveal">
        <div class="tl-content tl-left"></div>
        <div class="tl-node"><div class="tl-dot"></div></div>
        <div class="tl-content tl-right"><div class="tl-time">{{ strtoupper($invitation->poruwa_time) }}</div><div class="tl-event">Poruwa Ceremony</div><div class="tl-desc">The sacred auspicious rite uniting two souls</div></div>
      </div>
      <div class="tl-item reveal">
        <div class="tl-content tl-left"><div class="tl-time">Midday</div><div class="tl-event">Wedding Feast</div><div class="tl-desc">A lavish spread of traditional Sri Lankan cuisine</div></div>
        <div class="tl-node"><div class="tl-dot"></div></div>
        <div class="tl-content tl-right"></div>
      </div>
      <div class="tl-item reveal">
        <div class="tl-content tl-left"></div>
        <div class="tl-node"><div class="tl-dot"></div></div>
        <div class="tl-content tl-right"><div class="tl-time">{{ strtoupper($invitation->duration_to) }}</div><div class="tl-event">A Fond Farewell</div><div class="tl-desc">Carrying memories that last a lifetime</div></div>
      </div>
    </div>
  </div>
</section>

<style>
/* ── Gallery ── */
#gallery{background:linear-gradient(180deg,var(--night),var(--night-mid));}
.gallery-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:40px;}
.gallery-item{position:relative;overflow:hidden;cursor:pointer;background:var(--navy);aspect-ratio:4/3;}
.gallery-item:first-child{grid-column:span 2;aspect-ratio:unset;}
.gallery-item img{width:100%;height:100%;object-fit:cover;transition:transform 0.6s ease,filter 0.4s ease;filter:brightness(0.75) saturate(0.7) hue-rotate(200deg);}
.gallery-item:hover img{transform:scale(1.06);filter:brightness(0.9) saturate(0.85) hue-rotate(200deg);}
.gallery-overlay{position:absolute;inset:0;background:rgba(4,6,15,0);display:flex;align-items:center;justify-content:center;font-size:28px;opacity:0;transition:all 0.3s ease;}
.gallery-item:hover .gallery-overlay{background:rgba(4,6,15,0.5);opacity:1;border:1px solid rgba(180,210,255,0.2);}
#lightbox{position:fixed;inset:0;z-index:9000;background:rgba(0,0,0,0.95);display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity 0.3s ease;}
#lightbox.active{opacity:1;pointer-events:auto;}
#lightbox img{max-width:90vw;max-height:88vh;object-fit:contain;box-shadow:0 0 60px rgba(100,160,255,0.15);border:1px solid rgba(180,210,255,0.15);}
.lb-close{position:absolute;top:20px;right:28px;background:transparent;border:1px solid rgba(180,210,255,0.25);color:var(--moon);font-size:28px;cursor:pointer;width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;transition:background 0.2s;}
.lb-close:hover{background:rgba(180,210,255,0.1);}

/* ── Venue ── */
#venue{background:var(--night-mid);}
.venue-card{max-width:640px;margin:44px auto 0;border:1px solid rgba(180,210,255,0.15);overflow:hidden;}
.map-placeholder{height:280px;background:rgba(180,210,255,0.03);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;}
.map-placeholder span{font-size:40px;}
.map-placeholder p{font-family:'Playfair Display',serif;font-size:17px;color:var(--silver);}
.map-placeholder small{font-size:11px;letter-spacing:2px;text-transform:uppercase;color:var(--text-moon);}
.venue-info{padding:28px;background:rgba(180,210,255,0.02);text-align:center;}
.venue-name{font-family:'Playfair Display',serif;font-size:22px;color:var(--moon);font-weight:600;}
.venue-address{font-size:13px;color:var(--text-moon);margin-top:6px;letter-spacing:1px;}
.venue-note{font-size:13px;color:rgba(180,210,255,0.3);margin-top:8px;font-style:italic;}
.venue-btn{display:inline-block;margin-top:18px;padding:11px 30px;background:transparent;color:var(--moon);font-family:sans-serif;font-size:11px;letter-spacing:2px;text-transform:uppercase;text-decoration:none;border:1px solid rgba(180,210,255,0.3);transition:background 0.25s ease;}
.venue-btn:hover{background:rgba(180,210,255,0.08);}

/* ── RSVP ── */
#rsvp{background:radial-gradient(ellipse at center,var(--navy),var(--night));position:relative;overflow:hidden;}
#rsvp::before{content:'✦';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-size:clamp(120px,25vw,240px);color:rgba(180,210,255,0.02);pointer-events:none;}
.rsvp-card{max-width:500px;margin:44px auto 0;background:rgba(180,210,255,0.03);border:1px solid rgba(180,210,255,0.14);padding:44px 32px;text-align:center;}
.rsvp-dear{font-family:'Great Vibes',cursive;font-size:clamp(32px,6vw,46px);color:var(--moon);margin-bottom:10px;}
.rsvp-msg{font-size:14px;color:var(--text-moon);letter-spacing:1px;line-height:1.9;margin-bottom:28px;}
.rsvp-field-group{display:flex;flex-direction:column;gap:14px;margin-bottom:24px;text-align:left;}
.rsvp-field{display:flex;flex-direction:column;gap:6px;}
.rsvp-field-label{font-size:10px;letter-spacing:3px;text-transform:uppercase;color:var(--silver);}
.rsvp-input{background:rgba(180,210,255,0.04);border:1px solid rgba(180,210,255,0.18);color:var(--silver-pale);font-family:'Cormorant Garamond',serif;font-size:15px;padding:10px 14px;outline:none;transition:border-color 0.25s ease;}
.rsvp-input:focus{border-color:rgba(180,210,255,0.5);}
.rsvp-input option{background:var(--navy);color:var(--silver-pale);}
.rsvp-textarea{resize:vertical;min-height:80px;}
.rsvp-choices{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin-bottom:24px;}
.rsvp-btn{padding:12px 22px;border:1px solid rgba(180,210,255,0.25);background:transparent;color:rgba(180,210,255,0.7);font-family:'Cormorant Garamond',serif;font-size:15px;letter-spacing:2px;cursor:pointer;transition:all 0.25s ease;min-width:110px;}
.rsvp-btn:hover{border-color:var(--silver);color:var(--moon);background:rgba(180,210,255,0.07);}
.rsvp-btn.selected-accept{background:rgba(34,197,94,0.15);border-color:#22c55e;color:#86efac;}
.rsvp-btn.selected-decline{background:rgba(239,68,68,0.15);border-color:#ef4444;color:#fca5a5;}
.rsvp-btn.selected-maybe{background:rgba(100,160,255,0.15);border-color:#64a0ff;color:#a8c8ff;}
.rsvp-hint{font-size:11px;color:rgba(180,210,255,0.35);letter-spacing:1px;margin-bottom:20px;}
.rsvp-whatsapp{display:inline-flex;align-items:center;gap:10px;padding:14px 32px;background:linear-gradient(135deg,#25D366,#128C7E);color:#fff;font-family:sans-serif;font-size:13px;font-weight:600;letter-spacing:1px;text-decoration:none;box-shadow:0 4px 20px rgba(37,211,102,0.25);transition:opacity 0.2s;}
.rsvp-whatsapp.disabled{opacity:0.3;pointer-events:none;}
.rsvp-no-contact{color:rgba(180,210,255,0.4);font-size:12px;letter-spacing:1px;}

/* ── Closing ── */
#closing{position:relative;padding:100px 20px;text-align:center;overflow:hidden;}
.closing-bg{position:absolute;inset:0;background:radial-gradient(ellipse at center,var(--navy-light) 0%,var(--navy) 40%,var(--night) 100%);}
.closing-inner{position:relative;z-index:1;max-width:600px;margin:0 auto;}
.closing-ornament{font-size:20px;color:rgba(180,210,255,0.4);letter-spacing:10px;margin-bottom:24px;}
.closing-names{font-family:'Great Vibes',cursive;font-size:clamp(44px,10vw,80px);color:var(--moon);text-shadow:0 0 60px rgba(140,190,255,0.5);line-height:1.2;margin-bottom:8px;}
.closing-date{font-family:'Cinzel',serif;font-size:13px;letter-spacing:4px;color:var(--silver);margin-top:16px;}
.closing-venue{font-size:12px;letter-spacing:2px;color:rgba(180,210,255,0.4);margin-top:6px;}
.closing-msg{margin-top:32px;font-family:'Cormorant Garamond',serif;font-size:17px;font-style:italic;color:var(--text-moon);line-height:2;}
.closing-hearts{font-size:24px;letter-spacing:8px;margin-top:28px;animation:heartbeat 2s ease-in-out infinite;}
@keyframes heartbeat{0%,100%{transform:scale(1);}50%{transform:scale(1.08);}}

/* ── Footer ── */
#footer{background:var(--night-deep);padding:60px 20px;text-align:center;}
.footer-ornament{display:flex;align-items:center;justify-content:center;gap:12px;margin-bottom:28px;}
.footer-ornament-line{flex:1;max-width:100px;height:1px;background:rgba(180,210,255,0.18);}
.footer-names{font-family:'Great Vibes',cursive;font-size:clamp(32px,7vw,52px);color:var(--silver);margin-bottom:10px;}
.footer-tagline{font-family:'Playfair Display',serif;font-size:15px;font-style:italic;color:rgba(180,210,255,0.4);margin-bottom:20px;}
.footer-date{font-family:'Cinzel',serif;font-size:12px;letter-spacing:4px;color:rgba(180,210,255,0.3);margin-top:16px;}
.footer-venue{font-size:12px;letter-spacing:2px;color:rgba(200,216,240,0.2);margin-top:6px;}
.footer-bottom{font-size:11px;letter-spacing:2px;color:rgba(180,210,255,0.25);margin-top:24px;}

/* ── Responsive ── */
@media(max-width:600px){
  .couple-grid{grid-template-columns:1fr;gap:16px;}
  .couple-and{font-size:40px;}
  .tl-line{left:20px;}
  .tl-item{grid-template-columns:20px 1fr;}
  .tl-content.tl-left{display:none;}
  .tl-node{margin:0;}
  .tl-content.tl-right{padding-left:16px;}
  .countdown-grid{grid-template-columns:1fr auto 1fr;grid-template-rows:auto auto;}
  .cd-sep:last-of-type{display:none;}
  .ls-line{left:24px;}
  .ls-item{grid-template-columns:48px 1fr !important;}
  .ls-item > div:empty{display:none;}
  .ls-card-left{text-align:left !important;padding-right:0 !important;padding-left:16px;}
  .ls-card-right{padding-left:16px;}
  .gallery-grid{grid-template-columns:1fr 1fr;}
  .gallery-item:first-child{grid-column:span 2;}
  .inv-details{flex-direction:column;align-items:center;}
  .inv-detail-sep{transform:rotate(90deg);}
}
</style>

<!-- ── GALLERY ── -->
<section id="gallery">
  <div class="section">
    <span class="section-label reveal">— Captured Moments —</span>
    <h2 class="section-title reveal">Our Gallery</h2>
    <div class="silver-divider reveal"><span>✦</span></div>
    <p class="section-intro reveal">A glimpse of the beautiful moments we have shared together.</p>
    <div class="gallery-grid reveal">
      @foreach(['back1.png','back2.png','back3.png','back4.png','back5.png','back6.png'] as $img)
      <div class="gallery-item" onclick="openLightbox('{{ asset($img) }}')">
        <img src="{{ asset($img) }}" alt="Wedding photo" loading="lazy">
        <div class="gallery-overlay"><span>🔍</span></div>
      </div>
      @endforeach
    </div>
  </div>
</section>
<div id="lightbox" onclick="closeLightbox()">
  <button class="lb-close" onclick="closeLightbox()">&times;</button>
  <img id="lb-img" src="" alt="">
</div>

<!-- ── VENUE ── -->
<section id="venue">
  <div class="section">
    <span class="section-label reveal">Where Love Unfolds</span>
    <h2 class="section-title reveal">The Celebration Venue</h2>
    <div class="silver-divider reveal"><span>📍</span></div>
    <div class="venue-card reveal">
      @if($invitation->venue_map_url)
      <iframe src="{{ $invitation->venue_map_url }}" width="100%" height="280" style="border:0;display:block;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      @else
      <div class="map-placeholder"><span>🗺️</span><p>{{ $d['venueName'] }}</p>@if($d['venueCity'])<small>{{ $d['venueCity'] }}</small>@endif</div>
      @endif
      <div class="venue-info">
        <div class="venue-name">{{ $d['venueName'] }}</div>
        @if($d['venueCity'])<div class="venue-address">📍 {{ $d['venueCity'] }}</div>@endif
        <p class="venue-note">We look forward to welcoming you</p>
        @if($invitation->venue_map_url)
        <a href="{{ $invitation->venue_map_url }}" target="_blank" class="venue-btn">📍 Get Directions</a>
        @else
        <a href="https://maps.google.com/?q={{ urlencode($invitation->wedding_location) }}" target="_blank" class="venue-btn">📍 Search on Maps</a>
        @endif
      </div>
    </div>
  </div>
</section>

<!-- ── RSVP ── -->
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
  <div class="section">
    <span class="section-label reveal">Your Presence Matters</span>
    <h2 class="section-title reveal" style="color:var(--moon);">Kindly Respond</h2>
    <div class="silver-divider reveal"><span>✦</span></div>
    <div class="rsvp-card reveal">
      @if(isset($guestName))<div class="rsvp-dear">Dear {{ $guestName }},</div>@endif
      <p class="rsvp-msg">We humbly request the honour of your presence<br>as we exchange our vows and begin our forever.</p>
      @if($waNumber)
      <div class="rsvp-field-group">
        <div class="rsvp-field">
          <label class="rsvp-field-label">Your Name</label>
          <input type="text" id="rsvp-name" class="rsvp-input" placeholder="Enter your name" value="{{ $guestName ?? '' }}">
        </div>
        <div class="rsvp-field">
          <label class="rsvp-field-label">Number of Guests</label>
          <select id="rsvp-count" class="rsvp-input">
            <option value="1">1 Guest (Just me)</option>
            <option value="2">2 Guests</option>
            <option value="3">3 Guests</option>
            <option value="4">4 Guests</option>
            <option value="5+">5+ Guests</option>
          </select>
        </div>
        <div class="rsvp-field">
          <label class="rsvp-field-label">Message to the Couple <span style="opacity:0.4;font-size:11px;">(optional)</span></label>
          <textarea id="rsvp-message" class="rsvp-input rsvp-textarea" placeholder="Share your warm wishes..."></textarea>
        </div>
      </div>
      <div class="rsvp-choices">
        <button type="button" class="rsvp-btn" id="btn-accepted" onclick="selectRsvp('accepted')">✓ Accept</button>
        <button type="button" class="rsvp-btn" id="btn-declined" onclick="selectRsvp('declined')">✕ Decline</button>
        <button type="button" class="rsvp-btn" id="btn-maybe"    onclick="selectRsvp('maybe')">? Maybe</button>
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
  </div>
</section>

<!-- ── CLOSING ── -->
<section id="closing">
  <div class="closing-bg"></div>
  <div class="closing-inner">
    <div class="closing-ornament reveal">☽ ✦ ☽</div>
    <div class="closing-names reveal">{{ $invitation->bride_name }} &amp; {{ $invitation->groom_name }}</div>
    <div class="silver-divider reveal" style="max-width:240px;"><span>✦</span></div>
    <p class="closing-date reveal">{{ $d['dayOfWeek'] }}, {{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }} {{ $d['year'] }}</p>
    <p class="closing-venue reveal">{{ $d['venueName'] }}@if($d['venueCity']), {{ $d['venueCity'] }}@endif</p>
    <div class="closing-msg reveal">
      <p>Thank you for being part of our story.</p>
      <p>Your love and presence mean the world to us.</p>
    </div>
    <div class="closing-hearts reveal">🌙 ⭐ 🌙</div>
  </div>
</section>

<!-- ── FOOTER ── -->
<footer id="footer">
  <div class="footer-ornament">
    <span class="footer-ornament-line"></span>
    <span style="color:var(--silver);font-size:13px;">✦ ☽ ✦</span>
    <span class="footer-ornament-line"></span>
  </div>
  <div class="footer-names">{{ $invitation->bride_name }} &amp; {{ $invitation->groom_name }}</div>
  <p class="footer-tagline">&ldquo; And they lived happily ever after &rdquo;</p>
  <div class="silver-divider" style="margin:16px auto;max-width:200px;"><span>✦</span></div>
  <div class="footer-date">{{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }} {{ $d['year'] }}</div>
  <p class="footer-venue">{{ $d['venueName'] }}@if($d['venueCity']), {{ $d['venueCity'] }}@endif</p>
  <p class="footer-bottom">Crafted with <span style="color:var(--silver);">♥</span> for a lifetime of love</p>
</footer>

</div><!-- end #main-content -->

<script>
// ── STAR FIELD ──
(function(){
  const field = document.getElementById('star-field');
  for(let i=0;i<120;i++){
    const s = document.createElement('div');
    s.className = 'star-dot';
    const size = Math.random()*2.5+0.5;
    s.style.cssText = `left:${Math.random()*100}%;top:${Math.random()*100}%;width:${size}px;height:${size}px;animation-duration:${(Math.random()*4+2).toFixed(1)}s;animation-delay:${(Math.random()*5).toFixed(1)}s;`;
    field.appendChild(s);
  }
})();

// ── LIGHTBOX ──
function openLightbox(src){document.getElementById('lb-img').src=src;document.getElementById('lightbox').classList.add('active');document.body.style.overflow='hidden';}
function closeLightbox(){document.getElementById('lightbox').classList.remove('active');document.body.style.overflow='auto';}

// ── RSVP ──
const waNumber   = '{{ $waNumber ?? '' }}';
const guestLabel = '{{ addslashes($guestLabel ?? 'A Guest') }}';
const coupleName = '{{ addslashes($coupleName ?? '') }}';
const weddingDay = '{{ addslashes($invitation->wedding_day ?? '') }}';
const rsvpUrl    = '{{ isset($token) ? route('invite.rsvp', $token) : '' }}';
const csrfToken  = '{{ csrf_token() }}';
let selectedRsvp = null;
const rsvpBtnLabels = {accepted:'✓ Send Acceptance',declined:'✕ Send Decline',maybe:'? Send Maybe'};
function selectRsvp(choice){
  selectedRsvp = choice;
  ['accepted','declined','maybe'].forEach(c=>{
    const map={accepted:'accept',declined:'decline',maybe:'maybe'};
    document.getElementById('btn-'+c).className='rsvp-btn'+(c===choice?' selected-'+map[c]:'');
  });
  const name    = document.getElementById('rsvp-name')?.value || guestLabel;
  const count   = document.getElementById('rsvp-count')?.value || '1';
  const message = document.getElementById('rsvp-message')?.value || '';
  const msgs = {
    accepted:`Hi! This is ${name}. I am happy to confirm that I will be ATTENDING the wedding of ${coupleName} on ${weddingDay} with ${count} guest(s). 🌹${message?'\n\nMessage: '+message:''}`,
    declined:`Hi! This is ${name}. I regret to inform you that I will NOT be able to attend the wedding of ${coupleName} on ${weddingDay}. Wishing you a wonderful day! 🙏${message?'\n\nMessage: '+message:''}`,
    maybe:`Hi! This is ${name}. Regarding the wedding of ${coupleName} on ${weddingDay} — I am UNSURE if I can attend and will confirm soon. 🌸${message?'\n\nMessage: '+message:''}`,
  };
  const waBtn = document.getElementById('rsvp-wa-btn');
  waBtn.classList.remove('disabled');
  waBtn.href = 'https://wa.me/'+waNumber+'?text='+encodeURIComponent(msgs[choice]);
  document.getElementById('rsvp-wa-label').textContent = rsvpBtnLabels[choice];
}
function sendRsvp(e){
  if(!selectedRsvp||!rsvpUrl) return true;
  fetch(rsvpUrl,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken},body:JSON.stringify({rsvp:selectedRsvp})}).catch(()=>{});
  return true;
}

// ── COUNTDOWN ──
@php $weddingTs = ($invitation->wedding->wedding_date ? $invitation->wedding->wedding_date->timestamp * 1000 : 0); @endphp
const weddingTime = {{ $weddingTs }};
function updateCountdown(){
  const diff = weddingTime - Date.now();
  if(diff<=0){['cd-days','cd-hours','cd-mins','cd-secs'].forEach(id=>document.getElementById(id).textContent='00');return;}
  document.getElementById('cd-days').textContent  = String(Math.floor(diff/86400000)).padStart(2,'0');
  document.getElementById('cd-hours').textContent = String(Math.floor((diff%86400000)/3600000)).padStart(2,'0');
  document.getElementById('cd-mins').textContent  = String(Math.floor((diff%3600000)/60000)).padStart(2,'0');
  document.getElementById('cd-secs').textContent  = String(Math.floor((diff%60000)/1000)).padStart(2,'0');
}
updateCountdown(); setInterval(updateCountdown,1000);

// ── MUSIC ──
@if($sec['music'] ?? true)
let musicOn=false;
const audio=document.getElementById('bg-music');
const musicBtn=document.getElementById('music-btn');
function toggleMusic(){
  if(musicOn){audio.pause();musicBtn.textContent='🎵 Music Off';musicOn=false;}
  else{audio.play().catch(()=>{});musicBtn.textContent='🎵 Music On';musicOn=true;}
}
@else
function toggleMusic(){}
@endif

// ── SCROLL REVEAL ──
function triggerReveal(){
  const els=document.querySelectorAll('.reveal');
  const io=new IntersectionObserver((entries)=>{
    entries.forEach((e,i)=>{if(e.isIntersecting){setTimeout(()=>e.target.classList.add('visible'),i*80);io.unobserve(e.target);}});
  },{threshold:0.12});
  els.forEach(el=>io.observe(el));
}

@if(!$isPreviewPane)
// ── VIDEO → FLASH → REVEAL ──
function doFlashReveal(){
  const flash=document.getElementById('flash');
  const main=document.getElementById('main-content');
  const heroText=document.getElementById('hero-text');
  const scrollHint=document.getElementById('scroll-hint');
  const heroWrap=document.getElementById('hero-video-wrap');
  flash.classList.add('flash-on');
  setTimeout(()=>{
    main.classList.add('visible');
    heroText.classList.add('visible');
    heroWrap.classList.add('bars-gone');
    flash.classList.remove('flash-on');
    flash.classList.add('flash-fade');
    setTimeout(()=>{
      flash.style.display='none';
      scrollHint.classList.add('visible');
      triggerReveal();
    },2000);
  },200);
}

// ── LOADING + VIDEO INIT ──
(function(){
  const video=document.getElementById('hero-video');
  const loading=document.getElementById('loading-screen');
  let revealed=false;
  function startExperience(){
    if(revealed) return;
    revealed=true;
    loading.classList.add('fade-out');
    video.play().catch(()=>{});
    video.addEventListener('ended',doFlashReveal,{once:true});
    if(video.duration && video.duration<30){
      setTimeout(doFlashReveal,(video.duration*1000)+200);
    }
  }
  function checkReady(){
    if(video.readyState>=4){startExperience();return;}
    video.addEventListener('canplaythrough',startExperience,{once:true});
    setTimeout(startExperience,8000);
  }
  if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',checkReady);}
  else{checkReady();}
  document.body.style.overflow='hidden';
  loading.addEventListener('transitionend',()=>{document.body.style.overflow='auto';},{once:true});
})();
@else
// Preview pane — reveal all immediately
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.reveal').forEach(el => el.classList.add('visible'));
});
@endif
</script>
</body>
</html>
