@php
    $d         = $invitation->parsedDay();
    $cfg       = $invitation->resolvedConfig();
    $sec       = $cfg['sections'];
    $heroVideo = $cfg['hero_video']
        ? asset('hero_video/' . $cfg['hero_video'] . '.mp4')
        : asset('hero_video/video_hero1.mp4');
    $isPreviewPane   = request()->boolean('preview');
    $galleryPhotos   = $invitation->wedding->files()->where('usage', 'invitation_gallery')->get();
    $showGallery     = ($sec['gallery'] ?? true) && $galleryPhotos->count() > 0;
    $timelineEvents  = $cfg['timeline_events'] ?? [];
    $loveStoryItems  = $cfg['love_story_items'] ?? [];
@endphp
<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $invitation->bride_name }} & {{ $invitation->groom_name }} — විවාහ ආරාධනාව</title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Sinhala:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,400&family=Great+Vibes&family=Cinzel:wght@400;600&display=swap" rel="stylesheet">
<style>
:root{
  --gold:#c9a84c;--gold-light:#e8c97a;--gold-pale:#fdf6e3;
  --cream:#f5efe0;--dark:#0c0a06;--dark-mid:#1a1408;
  --text-gold:rgba(201,168,76,0.85);--text-cream:rgba(245,239,224,0.8);
}
*{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{background:var(--dark);font-family:'Noto Sans Sinhala','Cormorant Garamond',serif;color:var(--cream);overflow-x:hidden;}
.si{font-family:'Noto Sans Sinhala',sans-serif;}

/* ── Loading Screen ── */
#loading-screen{
  position:fixed;inset:0;z-index:9999;
  background:linear-gradient(160deg,#1a1408 0%,#0c0a06 50%,#1a1408 100%);
  display:flex;flex-direction:column;align-items:center;justify-content:center;gap:28px;
  transition:opacity 1.2s ease,visibility 1.2s ease;
}
#loading-screen.fade-out{opacity:0;visibility:hidden;}
.loading-ornament{
  font-family:'Great Vibes',cursive;font-size:clamp(36px,8vw,56px);
  color:var(--gold);text-shadow:0 0 40px rgba(201,168,76,0.5);
  animation:loadPulse 2s ease-in-out infinite;
}
@keyframes loadPulse{0%,100%{opacity:0.6;transform:scale(0.97);}50%{opacity:1;transform:scale(1);}}
.loading-title{
  font-family:'Noto Sans Sinhala',sans-serif;font-size:clamp(11px,2vw,14px);
  letter-spacing:2px;color:var(--text-gold);
}
.loading-bar-wrap{width:min(260px,60vw);height:1px;background:rgba(201,168,76,0.2);position:relative;overflow:hidden;}
.loading-bar{
  position:absolute;left:-100%;top:0;height:100%;width:100%;
  background:linear-gradient(90deg,transparent,var(--gold),transparent);
  animation:loadBar 1.8s ease-in-out infinite;
}
@keyframes loadBar{0%{left:-100%;}100%{left:100%;}}
.loading-sub{font-family:'Noto Sans Sinhala',sans-serif;font-size:11px;letter-spacing:1px;color:rgba(201,168,76,0.4);}

/* ── Toolbar ── */
.toolbar{
  position:fixed;top:0;left:0;right:0;z-index:500;
  display:flex;align-items:center;justify-content:space-between;
  padding:10px 24px;
  background:rgba(12,10,6,0.75);backdrop-filter:blur(12px);
  border-bottom:1px solid rgba(201,168,76,0.15);
}
.toolbar a,.toolbar button{
  font-family:sans-serif;font-size:11px;color:var(--gold-light);
  text-decoration:none;padding:5px 14px;
  border:1px solid rgba(201,168,76,0.3);border-radius:999px;
  background:transparent;cursor:pointer;
}
.toolbar span{font-family:'Noto Sans Sinhala',sans-serif;font-size:11px;color:var(--gold-light);letter-spacing:1px;}
@media print{.toolbar{display:none;}}
</style>
@if($isPreviewPane)
<style>body{overflow:auto !important;} #main-content{opacity:1 !important;}</style>
@endif
</head>
<body>

<!-- ══ LOADING SCREEN ══ -->
@if(!$isPreviewPane)
<div id="loading-screen">
  <div class="loading-ornament">අපේ කතාව ආරම්භ වෙයි…</div>
  <div class="loading-title">ඔබේ ආරාධනාව සූදානම් කරමින්</div>
  <div class="loading-bar-wrap"><div class="loading-bar"></div></div>
  <div class="loading-sub">කරුණාකර මොහොතක් රැඳෙන්න</div>
</div>
@endif

<!-- ══ TOOLBAR ══ -->
@if(!$isPreviewPane)
<div class="toolbar">
  @if(isset($token))
    <span>💌 විවාහ ආරාධනාව</span>
  @elseif(isset($guest))
    <a href="{{ route('guests.index') }}">← ආපසු</a>
    <span>💌 {{ $guest->name }}</span>
  @else
    <a href="{{ route('digital-invitations.index') }}">← Templates</a>
    <span>සිංහල</span>
  @endif
  @if($sec['music'] ?? true)
  <button onclick="toggleMusic()" id="music-btn">🎵 Music Off</button>
  @endif
</div>
@endif

@if($sec['music'] ?? true)
<audio id="bg-music" loop>
  <source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-9.mp3" type="audio/mpeg">
</audio>
@endif

<style>
/* ── Hero Video ── */
#hero-video-wrap{
  position:relative;width:100%;height:100vh;overflow:hidden;background:#0c0a06;
}
#hero-video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;}
#hero-video-wrap::before,#hero-video-wrap::after{
  content:'';position:absolute;left:0;right:0;height:6vh;
  background:#0c0a06;z-index:2;pointer-events:none;transition:height 1.5s ease;
}
#hero-video-wrap::before{top:0;}
#hero-video-wrap::after{bottom:0;}
#hero-video-wrap.bars-gone::before,#hero-video-wrap.bars-gone::after{height:0;}
.hero-vignette{position:absolute;inset:0;z-index:3;pointer-events:none;background:radial-gradient(ellipse at center,transparent 40%,rgba(12,10,6,0.7) 100%);}
.hero-fade-bottom{position:absolute;bottom:0;left:0;right:0;height:35%;z-index:4;pointer-events:none;background:linear-gradient(to bottom,transparent,#0c0a06);}
#flash{position:fixed;inset:0;z-index:800;background:#fff;opacity:0;pointer-events:none;transition:opacity 0.15s ease;}
#flash.flash-on{opacity:1;}
#flash.flash-fade{opacity:0;transition:opacity 1.8s ease;}
#hero-text{
  position:absolute;inset:0;z-index:6;
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  text-align:center;padding:80px 20px 60px;
  opacity:0;pointer-events:none;transition:opacity 1.5s ease;
  background:linear-gradient(to bottom,rgba(12,10,6,0.55) 0%,rgba(12,10,6,0.72) 50%,rgba(12,10,6,0.85) 100%);
}
#hero-text.visible{opacity:1;pointer-events:auto;}
.hero-text-card{
  position:relative;background:rgba(12,10,6,0.62);
  backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);
  border:1px solid rgba(201,168,76,0.25);padding:44px 48px 40px;width:min(560px,92vw);
  box-shadow:0 8px 60px rgba(0,0,0,0.7),inset 0 1px 0 rgba(201,168,76,0.12);
}
.hero-text-card::before{content:'';position:absolute;inset:7px;border:1px solid rgba(201,168,76,0.1);pointer-events:none;}
.hero-pre-line{
  font-family:'Noto Sans Sinhala',sans-serif;font-size:clamp(9px,1.5vw,12px);
  letter-spacing:2px;color:#f0d98a;margin-bottom:20px;
  animation:fadeUp 1.2s ease both;animation-delay:0.3s;
}
.hero-names-wrap{animation:fadeUp 1.4s ease both;animation-delay:0.6s;}
.hero-name{
  font-family:'Great Vibes',cursive;font-size:clamp(48px,11vw,88px);
  color:#fff8e8;line-height:1.1;
  text-shadow:0 0 40px rgba(201,168,76,0.9),0 0 80px rgba(201,168,76,0.4),0 2px 8px rgba(0,0,0,0.9);
}
.hero-amp{display:block;font-size:clamp(28px,5vw,48px);color:#e8c97a;margin:2px 0;text-shadow:0 0 30px rgba(201,168,76,0.8),0 2px 6px rgba(0,0,0,0.8);}
.hero-invite-line{
  font-family:'Noto Sans Sinhala',sans-serif;font-size:clamp(11px,1.8vw,14px);
  color:#e8dfc8;margin-top:18px;text-shadow:0 1px 6px rgba(0,0,0,0.9);
  animation:fadeUp 1.4s ease both;animation-delay:0.9s;
}
.hero-date-line{
  font-family:'Cinzel',serif;font-size:clamp(12px,2vw,15px);letter-spacing:4px;color:#f0d98a;margin-top:12px;
  text-shadow:0 0 20px rgba(201,168,76,0.7),0 1px 6px rgba(0,0,0,0.9);
  animation:fadeUp 1.4s ease both;animation-delay:1.1s;
}
.hero-venue-line{font-size:12px;letter-spacing:2px;color:#c8b87a;margin-top:8px;text-shadow:0 1px 4px rgba(0,0,0,0.9);animation:fadeUp 1.4s ease both;animation-delay:1.3s;}
.hero-divider{display:flex;align-items:center;justify-content:center;gap:12px;margin:16px auto;width:min(260px,70vw);animation:fadeUp 1.4s ease both;animation-delay:0.8s;}
.hero-divider::before,.hero-divider::after{content:'';flex:1;height:1px;background:linear-gradient(90deg,transparent,rgba(240,217,138,0.7));}
.hero-divider::after{background:linear-gradient(90deg,rgba(240,217,138,0.7),transparent);}
.hero-divider span{color:#f0d98a;font-size:13px;}
@keyframes fadeUp{from{opacity:0;transform:translateY(24px);}to{opacity:1;transform:translateY(0);}}
.scroll-hint{
  position:absolute;bottom:28px;left:50%;transform:translateX(-50%);
  display:flex;flex-direction:column;align-items:center;gap:6px;
  color:rgba(240,217,138,0.7);font-family:'Noto Sans Sinhala',sans-serif;font-size:10px;letter-spacing:1px;
  animation:bounce 2s ease-in-out infinite;opacity:0;transition:opacity 1s ease;
}
.scroll-hint.visible{opacity:1;}
@keyframes bounce{0%,100%{transform:translateX(-50%) translateY(0);}50%{transform:translateX(-50%) translateY(7px);}}
.scroll-chevron{width:18px;height:18px;border-right:1px solid rgba(240,217,138,0.7);border-bottom:1px solid rgba(240,217,138,0.7);transform:rotate(45deg);}
</style>

@if(!$isPreviewPane)
<div id="flash"></div>
@endif

<!-- ══ HERO VIDEO ══ -->
@if(!$isPreviewPane)
<section id="hero-video-wrap">
  <video id="hero-video" muted playsinline preload="metadata" poster="{{ asset('back1.png') }}">
    <source src="{{ $heroVideo }}" type="video/mp4">
  </video>
  <div class="hero-vignette"></div>
  <div class="hero-fade-bottom"></div>
  <div id="hero-text">
    <div class="hero-text-card">
      <p class="hero-pre-line">ඔවුන්ගේ පවුල් සමඟ</p>
      <div class="hero-names-wrap">
        <div class="hero-name">{{ $invitation->bride_name }}</div>
        <span class="hero-amp">&</span>
        <div class="hero-name">{{ $invitation->groom_name }}</div>
      </div>
      <div class="hero-divider"><span>✦</span></div>
      <p class="hero-invite-line">ඔවුන්ගේ විවාහ මංගල්යයට ඔබව ආරාධනා කරයි</p>
      <p class="hero-date-line">{{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }} {{ $d['year'] }}</p>
      <p class="hero-venue-line">{{ $d['venueName'] }}@if($d['venueCity']), {{ $d['venueCity'] }}@endif</p>
    </div>
  </div>
  <div class="scroll-hint" id="scroll-hint"><div class="scroll-chevron"></div><span>පහළට</span></div>
</section>
@else
<section style="background:linear-gradient(160deg,#1a1408,#0c0a06);padding:60px 20px;text-align:center;">
  <div class="hero-text-card" style="margin:0 auto;opacity:1;position:relative;">
    <p class="hero-pre-line">ඔවුන්ගේ පවුල් සමඟ</p>
    <div class="hero-names-wrap">
      <div class="hero-name">{{ $invitation->bride_name }}</div>
      <span class="hero-amp">&</span>
      <div class="hero-name">{{ $invitation->groom_name }}</div>
    </div>
    <div class="hero-divider"><span>✦</span></div>
    <p class="hero-invite-line">ඔවුන්ගේ විවාහ මංගල්යයට ඔබව ආරාධනා කරයි</p>
    <p class="hero-date-line">{{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }} {{ $d['year'] }}</p>
    <p class="hero-venue-line">{{ $d['venueName'] }}@if($d['venueCity']), {{ $d['venueCity'] }}@endif</p>
  </div>
</section>
@endif

<style>
/* ── Shared ── */
#main-content{opacity:0;transition:opacity 1s ease;}
#main-content.visible{opacity:1;}
.section{padding:90px 20px;max-width:800px;margin:0 auto;text-align:center;}
.section-label{font-family:'Noto Sans Sinhala',sans-serif;font-size:11px;letter-spacing:2px;color:var(--gold);margin-bottom:14px;display:block;}
.section-title{font-family:'Playfair Display',serif;font-size:clamp(28px,5vw,44px);color:var(--cream);font-weight:600;line-height:1.2;}
.section-intro{font-family:'Noto Sans Sinhala',sans-serif;font-size:14px;color:rgba(245,239,224,0.55);line-height:2;margin-top:14px;}
.gold-divider{display:flex;align-items:center;justify-content:center;gap:12px;margin:22px auto;}
.gold-divider::before,.gold-divider::after{content:'';flex:1;max-width:120px;height:1px;background:linear-gradient(90deg,transparent,var(--gold));}
.gold-divider::after{background:linear-gradient(90deg,var(--gold),transparent);}
.gold-divider span{color:var(--gold);font-size:15px;}
.reveal{opacity:0;transform:translateY(28px);transition:opacity 0.9s ease,transform 0.9s ease;}
.reveal.visible{opacity:1;transform:translateY(0);}

/* ── Invitation ── */
#invitation{background:linear-gradient(180deg,#0c0a06 0%,#1c1206 50%,#0c0a06 100%);padding:80px 20px;text-align:center;position:relative;}
.floral-top,.floral-bottom{text-align:center;font-size:18px;color:rgba(201,168,76,0.35);letter-spacing:8px;padding:12px 0;}
.floral-row{font-size:22px;letter-spacing:10px;margin-top:28px;}
.inv-quote{
  font-family:'Noto Sans Sinhala',sans-serif;font-size:clamp(14px,2.5vw,18px);
  color:var(--gold-light);border-left:2px solid rgba(201,168,76,0.4);padding-left:20px;
  max-width:560px;margin:28px auto;text-align:left;line-height:2;
}
.inv-body{font-family:'Noto Sans Sinhala',sans-serif;font-size:15px;color:rgba(245,239,224,0.65);line-height:2.2;max-width:500px;margin:0 auto;}
.inv-names{font-family:'Great Vibes',cursive;font-size:clamp(28px,5vw,38px);color:var(--gold-light);display:block;margin:6px 0;}
.inv-details{display:flex;align-items:center;justify-content:center;gap:24px;margin-top:32px;flex-wrap:wrap;}
.inv-detail-item{display:flex;flex-direction:column;align-items:center;gap:6px;padding:20px 28px;background:rgba(201,168,76,0.05);border:1px solid rgba(201,168,76,0.18);}
.inv-detail-icon{font-size:28px;}
.inv-detail-label{font-family:'Noto Sans Sinhala',sans-serif;font-size:10px;letter-spacing:1px;color:var(--gold);}
.inv-detail-val{font-family:'Playfair Display',serif;font-size:16px;color:var(--cream);}
.inv-detail-sep{font-size:28px;color:rgba(201,168,76,0.3);}

/* ── Couple ── */
#couple{background:linear-gradient(180deg,#0c0a06 0%,#1a1408 100%);}
.couple-grid{display:grid;grid-template-columns:1fr auto 1fr;gap:24px;align-items:center;max-width:720px;margin:44px auto 0;}
.couple-card{padding:36px 24px;background:rgba(201,168,76,0.04);border:1px solid rgba(201,168,76,0.18);position:relative;}
.couple-card::before{content:'';position:absolute;inset:6px;border:1px solid rgba(201,168,76,0.07);pointer-events:none;}
.couple-emoji{font-size:44px;margin-bottom:14px;display:block;}
.couple-name{font-family:'Great Vibes',cursive;font-size:clamp(28px,5vw,40px);color:var(--gold-light);}
.couple-surname{font-family:'Noto Sans Sinhala',sans-serif;font-size:11px;letter-spacing:1px;color:var(--text-gold);margin-top:5px;}
.couple-parents{font-family:'Noto Sans Sinhala',sans-serif;font-size:12px;color:rgba(245,239,224,0.4);margin-top:12px;line-height:2;}
.couple-and{font-family:'Great Vibes',cursive;font-size:56px;color:var(--gold);text-align:center;}

/* ── Invitee ── */
#invitee{background:linear-gradient(135deg,#1a1408,#0c0a06);padding:80px 20px;}
.invitee-inner{max-width:600px;margin:0 auto;text-align:center;}
.invitee-label{font-family:'Noto Sans Sinhala',sans-serif;font-size:11px;letter-spacing:2px;color:var(--gold);margin-bottom:16px;display:block;}
.invitee-pre{font-family:'Noto Sans Sinhala',sans-serif;font-size:13px;color:rgba(245,239,224,0.5);margin-bottom:20px;}
.invitee-name{font-family:'Great Vibes',cursive;font-size:clamp(40px,9vw,64px);color:var(--gold-light);line-height:1.2;margin-bottom:12px;text-shadow:0 0 40px rgba(201,168,76,0.4);}
.invitee-plus{font-family:'Noto Sans Sinhala',sans-serif;font-size:13px;color:var(--text-gold);}
.invitee-msg{font-family:'Noto Sans Sinhala',sans-serif;font-size:13px;color:rgba(245,239,224,0.5);line-height:2;margin-top:8px;}
.invitee-quote{font-family:'Noto Sans Sinhala',sans-serif;font-size:15px;color:var(--gold);margin-top:20px;}
.ornament-row{display:flex;align-items:center;justify-content:center;gap:10px;margin:20px auto;color:var(--gold);font-size:13px;}
.ornament-line{flex:1;max-width:80px;height:1px;background:rgba(201,168,76,0.3);}
</style>

<!-- ══ MAIN CONTENT ══ -->
<div id="main-content" class="{{ $isPreviewPane ? 'visible' : '' }}">

<!-- ── INVITATION ── -->
@if($sec['invitation'] ?? true)
<section id="invitation">
  <div class="floral-top">❧ ✦ ❧ ✦ ❧</div>
  <div class="section">
    <span class="section-label reveal">— පවිත්‍ර බැඳීමක් —</span>
    <h2 class="section-title reveal">මහත් සතුටින් හා ආදරයෙන්</h2>
    <div class="gold-divider reveal"><span>✦</span></div>
    <blockquote class="inv-quote reveal">
      &ldquo; දෙදෙනෙකු, එක් හදවතක් — ආදරයෙන් බැඳී, ආශිර්වාදයෙන් පිරී &rdquo;
    </blockquote>
    <p class="inv-body reveal">
      ඔවුන්ගේ ආදරණීය පවුල් සමඟ,<br>
      <strong class="inv-names">{{ $invitation->bride_name }} &amp; {{ $invitation->groom_name }}</strong><br>
      ඔවුන්ගේ විවාහ මංගල්යයට සාක්ෂි වීමට<br>ඔබව සතුටින් ආරාධනා කරයි.
    </p>
    <div class="inv-details reveal">
      <div class="inv-detail-item"><span class="inv-detail-icon">👰</span><span class="inv-detail-label">මනාලිය</span><span class="inv-detail-val">{{ $invitation->bride_name }} {{ $invitation->bride_surname }}</span></div>
      <div class="inv-detail-sep">✦</div>
      <div class="inv-detail-item"><span class="inv-detail-icon">🤵</span><span class="inv-detail-label">මනාලයා</span><span class="inv-detail-val">{{ $invitation->groom_name }} {{ $invitation->groom_surname }}</span></div>
    </div>
    <div class="floral-row reveal">🌸 ✦ 🌺 ✦ 🌸</div>
  </div>
  <div class="floral-bottom">❧ ✦ ❧ ✦ ❧</div>
</section>
@endif

<!-- ── COUPLE ── -->
@if($sec['couple'] ?? true)
<section id="couple">
  <div class="section">
    <span class="section-label reveal">ආදරණීය යුවළ</span>
    <h2 class="section-title reveal">දෙහදවතක්, එක් ඉරණමක්</h2>
    <div class="gold-divider reveal"><span>✦</span></div>
    <p class="section-intro reveal">එකිනෙකා සොයාගත් දෙදෙනෙකු, සදාකාලිකව එකට ගමන් කිරීමට තෝරාගනිති.</p>
    <div class="couple-grid reveal">
      <div class="couple-card">
        <span class="couple-emoji">👰</span>
        <div class="couple-name">{{ $invitation->bride_name }}</div>
        <div class="couple-surname">{{ $invitation->bride_surname }}</div>
        <div class="couple-parents">දියණිය<br>Mr. &amp; Mrs. {{ $invitation->bride_surname }}</div>
      </div>
      <div class="couple-and">&</div>
      <div class="couple-card">
        <span class="couple-emoji">🤵</span>
        <div class="couple-name">{{ $invitation->groom_name }}</div>
        <div class="couple-surname">{{ $invitation->groom_surname }}</div>
        <div class="couple-parents">පුත්‍රයා<br>Mr. &amp; Mrs. {{ $invitation->groom_surname }}</div>
      </div>
    </div>
  </div>
</section>
@endif

<!-- ── INVITEE ── -->
@if(isset($guest))
<section id="invitee">
  <div class="invitee-inner">
    <div class="ornament-row reveal"><span class="ornament-line"></span><span>✦ ❧ ✦</span><span class="ornament-line"></span></div>
    <span class="invitee-label reveal">පෞද්ගලික ආරාධනාවක්</span>
    <p class="invitee-pre reveal">මහත් සතුටින්, මෙම ආරාධනාව ඔබ වෙත ලබා දෙමු</p>
    <div class="invitee-name reveal">{{ $guest->name }}</div>
    @if($guest->total_invited > 1)
    <p class="invitee-plus reveal">& {{ $guest->total_invited - 1 }} ආදරණීය අමුත්තන්</p>
    @endif
    <div class="gold-divider reveal" style="margin:24px auto;"><span>🌸</span></div>
    <p class="invitee-msg reveal">ඔබේ පැමිණීම අපේ උත්සවයට ශෝභාවක් ගෙනෙනු ඇත.</p>
    <p class="invitee-quote reveal">&ldquo; ආදරය ඇති තැන ජීවිතය ඇත &rdquo;</p>
  </div>
</section>
@endif

<style>
/* ── Love Story ── */
#love-story{background:#0c0a06;padding:90px 20px;position:relative;overflow:hidden;}
.ls-parallax-bg{position:absolute;inset:0;background:radial-gradient(ellipse at 20% 50%,rgba(201,168,76,0.04) 0%,transparent 60%),radial-gradient(ellipse at 80% 50%,rgba(201,168,76,0.04) 0%,transparent 60%);pointer-events:none;}
.ls-timeline{max-width:700px;margin:50px auto 0;position:relative;}
.ls-line{position:absolute;left:50%;top:0;bottom:0;width:1px;background:linear-gradient(to bottom,transparent,var(--gold) 15%,var(--gold) 85%,transparent);transform:translateX(-50%);}
.ls-item{display:grid;grid-template-columns:1fr 60px 1fr;gap:0;margin-bottom:48px;align-items:center;}
.ls-card-left{padding-right:24px;text-align:right;}
.ls-card-right{padding-left:24px;text-align:left;}
.ls-icon-wrap{display:flex;align-items:center;justify-content:center;position:relative;z-index:1;}
.ls-icon{width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#1a1408,#2a1e08);border:2px solid var(--gold);box-shadow:0 0 20px rgba(201,168,76,0.4);display:flex;align-items:center;justify-content:center;font-size:20px;}
.ls-year{font-family:'Noto Sans Sinhala',sans-serif;font-size:10px;letter-spacing:1px;color:var(--gold);margin-bottom:6px;}
.ls-title{font-family:'Playfair Display',serif;font-size:18px;color:var(--cream);font-weight:600;margin-bottom:6px;}
.ls-desc{font-family:'Noto Sans Sinhala',sans-serif;font-size:12px;color:rgba(245,239,224,0.45);line-height:2;}

/* ── Details ── */
#details{background:#0c0a06;}
.details-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:20px;margin-top:44px;}
.detail-card{padding:30px 18px;background:rgba(201,168,76,0.04);border:1px solid rgba(201,168,76,0.14);border-top:2px solid var(--gold);text-align:center;transition:transform 0.3s ease,box-shadow 0.3s ease;}
.detail-card:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(201,168,76,0.1);}
.detail-icon{font-size:26px;margin-bottom:10px;display:block;}
.detail-label{font-family:'Noto Sans Sinhala',sans-serif;font-size:10px;letter-spacing:1px;color:var(--gold);margin-bottom:7px;}
.detail-value{font-family:'Playfair Display',serif;font-size:17px;color:var(--cream);font-weight:600;}
.detail-sub{font-family:'Noto Sans Sinhala',sans-serif;font-size:11px;color:rgba(245,239,224,0.4);margin-top:5px;}
</style>

<!-- ── LOVE STORY ── -->
@if($sec['love_story'] ?? true)
<section id="love-story">
  <div class="ls-parallax-bg"></div>
  <div class="section" style="position:relative;z-index:1;">
    <span class="section-label reveal">— ආදරය ආරම්භ වූ හැටි —</span>
    <h2 class="section-title reveal">අපේ ආදර කතාව</h2>
    <div class="gold-divider reveal"><span>🌸</span></div>
    <p class="section-intro reveal">සෑම මහා ආදර කතාවකටම ආරම්භයක් ඇත. මෙය අපේ කතාවයි.</p>
    <div class="ls-timeline">
      <div class="ls-line"></div>
      @foreach($loveStoryItems as $i => $item)
      <div class="ls-item reveal">
        @if($i % 2 === 0)
        <div class="ls-card-left"><div class="ls-year">{{ $item['label'] }}</div><div class="ls-title">{{ $item['title'] }}</div>@if($item['desc'])<p class="ls-desc">{{ $item['desc'] }}</p>@endif</div>
        <div class="ls-icon-wrap"><div class="ls-icon">{{ $item['icon'] }}</div></div>
        <div></div>
        @else
        <div></div>
        <div class="ls-icon-wrap"><div class="ls-icon">{{ $item['icon'] }}</div></div>
        <div class="ls-card-right"><div class="ls-year">{{ $item['label'] }}</div><div class="ls-title">{{ $item['title'] }}</div>@if($item['desc'])<p class="ls-desc">{{ $item['desc'] }}</p>@endif</div>
        @endif
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- ── EVENT DETAILS ── -->
@if($sec['details'] ?? true)
<section id="details">
  <div class="section">
    <span class="section-label reveal">ඔබේ දිනදර්ශනයේ සටහන් කරන්න</span>
    <h2 class="section-title reveal">පවිත්ර උත්සවය</h2>
    <div class="gold-divider reveal"><span>✦</span></div>
    <p class="section-intro reveal">සෑම විස්තරයක්ම ආදරයෙන් සකස් කර ඇත — සුන්දර, සම්ප්රදායික, අමතක නොවන දිනයක් සඳහා.</p>
    <div class="details-grid reveal">
      <div class="detail-card">
        <span class="detail-icon">📅</span>
        <div class="detail-label">ශුභ දිනය</div>
        <div class="detail-value">{{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }}</div>
        <div class="detail-sub">{{ $d['dayOfWeek'] }}, {{ $d['year'] }}</div>
      </div>
      <div class="detail-card">
        <span class="detail-icon">⏰</span>
        <div class="detail-label">පොරොව් මංගල්යය</div>
        <div class="detail-value">{{ strtoupper($invitation->poruwa_time) }}</div>
        <div class="detail-sub">ශුභ නැකත</div>
      </div>
      <div class="detail-card">
        <span class="detail-icon">🍾</span>
        <div class="detail-label">සාදය ආරම්භ වේ</div>
        <div class="detail-value">{{ strtoupper($invitation->duration_from) }}</div>
        <div class="detail-sub">දක්වා {{ strtoupper($invitation->duration_to) }}</div>
      </div>
      <div class="detail-card">
        <span class="detail-icon">📍</span>
        <div class="detail-label">ස්ථානය</div>
        <div class="detail-value">{{ $d['venueName'] }}</div>
        <div class="detail-sub">{{ $d['venueCity'] }}</div>
      </div>
    </div>
  </div>
</section>
@endif

<style>
/* ── Countdown ── */
#countdown{background:linear-gradient(135deg,#1a1408,#0c0a06);position:relative;overflow:hidden;}
#countdown::before{content:'සදාකාලිකව';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-family:'Noto Sans Sinhala',sans-serif;font-size:clamp(40px,10vw,100px);font-weight:600;color:rgba(201,168,76,0.04);white-space:nowrap;pointer-events:none;}
.countdown-grid{display:grid;grid-template-columns:1fr auto 1fr auto 1fr auto 1fr;align-items:center;gap:8px;max-width:520px;margin:44px auto 0;}
.cd-box{background:rgba(0,0,0,0.35);border:1px solid rgba(201,168,76,0.2);padding:22px 10px;text-align:center;}
.cd-num{font-family:'Playfair Display',serif;font-size:clamp(32px,6vw,52px);color:var(--gold-light);font-weight:700;line-height:1;display:block;}
.cd-label{font-family:'Noto Sans Sinhala',sans-serif;font-size:10px;letter-spacing:1px;color:rgba(201,168,76,0.5);margin-top:6px;display:block;}
.cd-sep{font-family:'Playfair Display',serif;font-size:clamp(24px,4vw,40px);color:rgba(201,168,76,0.4);text-align:center;padding-bottom:16px;}
.countdown-footer{font-family:'Noto Sans Sinhala',sans-serif;font-size:11px;letter-spacing:1px;color:rgba(201,168,76,0.4);margin-top:24px;}

/* ── Timeline ── */
#timeline{background:#0c0a06;}
.timeline-wrap{max-width:600px;margin:44px auto 0;position:relative;}
.tl-line{position:absolute;left:50%;top:0;bottom:0;width:1px;background:linear-gradient(to bottom,transparent,var(--gold) 20%,var(--gold) 80%,transparent);transform:translateX(-50%);}
.tl-item{display:grid;grid-template-columns:1fr 44px 1fr;gap:0;margin-bottom:36px;align-items:center;}
.tl-content{padding:0 20px;}
.tl-left{text-align:right;}
.tl-right{text-align:left;}
.tl-node{display:flex;align-items:center;justify-content:center;position:relative;z-index:1;}
.tl-dot{width:14px;height:14px;border-radius:50%;background:var(--dark-mid);border:2px solid var(--gold);box-shadow:0 0 12px rgba(201,168,76,0.5);}
.tl-time{font-size:11px;letter-spacing:2px;text-transform:uppercase;color:var(--gold);font-weight:600;}
.tl-event{font-family:'Noto Sans Sinhala',sans-serif;font-size:14px;color:var(--cream);margin-top:3px;}
.tl-desc{font-family:'Noto Sans Sinhala',sans-serif;font-size:11px;color:rgba(245,239,224,0.4);margin-top:3px;line-height:1.8;}
</style>

<!-- ── COUNTDOWN ── -->
@if($sec['countdown'] ?? true)
<section id="countdown">
  <div class="section">
    <span class="section-label reveal">ඉවසිලිවන්ත බලාපොරොත්තුව</span>
    <h2 class="section-title reveal">මොහොත ළඟා වෙයි</h2>
    <div class="gold-divider reveal"><span>✦</span></div>
    <div class="countdown-grid reveal">
      <div class="cd-box"><span class="cd-num" id="cd-days">--</span><span class="cd-label">දින</span></div>
      <div class="cd-sep">:</div>
      <div class="cd-box"><span class="cd-num" id="cd-hours">--</span><span class="cd-label">පැය</span></div>
      <div class="cd-sep">:</div>
      <div class="cd-box"><span class="cd-num" id="cd-mins">--</span><span class="cd-label">මිනිත්තු</span></div>
      <div class="cd-sep">:</div>
      <div class="cd-box"><span class="cd-num" id="cd-secs">--</span><span class="cd-label">තත්පර</span></div>
    </div>
    <p class="countdown-footer reveal">{{ $d['dayOfWeek'] }}, {{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }} {{ $d['year'] }}</p>
  </div>
</section>
@endif

<!-- ── TIMELINE ── -->
@if($sec['timeline'] ?? true)
<section id="timeline">
  <div class="section">
    <span class="section-label reveal">අමතක නොවන දිනයක්</span>
    <h2 class="section-title reveal">වැඩසටහන</h2>
    <div class="gold-divider reveal"><span>✦</span></div>
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

<style>
/* ── Gallery ── */
#gallery{background:linear-gradient(180deg,#0c0a06,#1a1408);}
.gallery-grid{display:grid;grid-template-columns:repeat(3,1fr);grid-template-rows:auto auto;gap:10px;margin-top:40px;}
.gallery-item{position:relative;overflow:hidden;cursor:pointer;background:#1a1408;aspect-ratio:4/3;}
.gallery-item-wide{grid-column:span 2;grid-row:span 1;aspect-ratio:unset;}
.gallery-item img{width:100%;height:100%;object-fit:cover;transition:transform 0.6s ease,filter 0.4s ease;filter:brightness(0.85) saturate(0.9);}
.gallery-item:hover img{transform:scale(1.06);filter:brightness(1) saturate(1.1);}
.gallery-overlay{position:absolute;inset:0;background:rgba(12,10,6,0);display:flex;align-items:center;justify-content:center;font-size:28px;opacity:0;transition:all 0.3s ease;border:0px solid rgba(201,168,76,0);}
.gallery-item:hover .gallery-overlay{background:rgba(12,10,6,0.45);opacity:1;border:1px solid rgba(201,168,76,0.3);}
#lightbox{position:fixed;inset:0;z-index:9000;background:rgba(0,0,0,0.92);display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity 0.3s ease;}
#lightbox.active{opacity:1;pointer-events:auto;}
#lightbox img{max-width:90vw;max-height:88vh;object-fit:contain;box-shadow:0 0 60px rgba(201,168,76,0.2);border:1px solid rgba(201,168,76,0.2);}
.lb-close{position:absolute;top:20px;right:28px;background:transparent;border:1px solid rgba(201,168,76,0.3);color:var(--gold-light);font-size:28px;cursor:pointer;width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;transition:background 0.2s;}
.lb-close:hover{background:rgba(201,168,76,0.15);}

/* ── Venue ── */
#venue{background:linear-gradient(180deg,#0c0a06,#1a1408);}
.venue-card{max-width:640px;margin:44px auto 0;border:1px solid rgba(201,168,76,0.2);overflow:hidden;}
.map-placeholder{height:280px;background:rgba(201,168,76,0.04);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;}
.map-placeholder span{font-size:40px;}
.map-placeholder p{font-family:'Playfair Display',serif;font-size:17px;color:var(--gold);}
.map-placeholder small{font-family:'Noto Sans Sinhala',sans-serif;font-size:11px;letter-spacing:1px;color:rgba(245,239,224,0.35);}
.venue-info{padding:28px;background:rgba(201,168,76,0.03);text-align:center;}
.venue-name{font-family:'Playfair Display',serif;font-size:22px;color:var(--gold-light);font-weight:600;}
.venue-address{font-family:'Noto Sans Sinhala',sans-serif;font-size:12px;color:rgba(245,239,224,0.4);margin-top:6px;}
.venue-note{font-family:'Noto Sans Sinhala',sans-serif;font-size:12px;color:rgba(245,239,224,0.35);margin-top:8px;}
.venue-btn{display:inline-block;margin-top:18px;padding:11px 30px;background:transparent;color:var(--gold-light);font-family:'Noto Sans Sinhala',sans-serif;font-size:11px;letter-spacing:1px;text-decoration:none;border:1px solid rgba(201,168,76,0.35);transition:background 0.25s ease;}
.venue-btn:hover{background:rgba(201,168,76,0.1);}
</style>

<!-- ── GALLERY ── -->
@if($showGallery)
<section id="gallery">
  <div class="section">
    <span class="section-label reveal">— ලස්සන මොහොත් —</span>
    <h2 class="section-title reveal">ජායාරූප</h2>
    <div class="gold-divider reveal"><span>✦</span></div>
    <p class="section-intro reveal">අපි එකට ගත කළ සුන්දර මොහොත් කිහිපයක්.</p>
    <div class="gallery-grid reveal"
         style="grid-template-columns:repeat({{ min($galleryPhotos->count(), 3) }},1fr);">
      @foreach($galleryPhotos as $i => $photo)
      <div class="gallery-item {{ $i === 0 && $galleryPhotos->count() > 2 ? 'gallery-item-wide' : '' }}"
           onclick="openLightbox('{{ Storage::url($photo->path) }}')">
        <img src="{{ Storage::url($photo->path) }}" alt="Wedding photo" loading="lazy">
        <div class="gallery-overlay"><span>🔍</span></div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<div id="lightbox" onclick="closeLightbox()">
  <button class="lb-close" onclick="closeLightbox()">&times;</button>
  <img id="lb-img" src="" alt="">
</div>

<!-- ── VENUE ── -->
@if($sec['venue'] ?? true)
<section id="venue">
  <div class="section">
    <span class="section-label reveal">ආදරය විකසිත වන තැන</span>
    <h2 class="section-title reveal">උත්සව ස්ථානය</h2>
    <div class="gold-divider reveal"><span>📍</span></div>
    <div class="venue-card reveal">
      @if($invitation->venue_map_url)
      <iframe src="{{ $invitation->venue_map_url }}" width="100%" height="280" style="border:0;display:block;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      @else
      <div class="map-placeholder"><span>🗺️</span><p>{{ $d['venueName'] }}</p>@if($d['venueCity'])<small>{{ $d['venueCity'] }}</small>@endif</div>
      @endif
      <div class="venue-info">
        <div class="venue-name">{{ $d['venueName'] }}</div>
        @if($d['venueCity'])<div class="venue-address">📍 {{ $d['venueCity'] }}</div>@endif
        <p class="venue-note">ඔබව පිළිගැනීමට අපි ආශාවෙන් සිටිමු</p>
        @if($invitation->venue_map_url)
        <a href="{{ $invitation->venue_map_url }}" target="_blank" class="venue-btn">📍 මාර්ගය ලබාගන්න</a>
        @else
        <a href="https://maps.google.com/?q={{ urlencode($invitation->wedding_location) }}" target="_blank" class="venue-btn">📍 සිතියමේ සොයන්න</a>
        @endif
      </div>
    </div>
  </div>
</section>
@endif

<style>
/* ── RSVP ── */
#rsvp{background:radial-gradient(ellipse at center,#1a1408,#0c0a06);position:relative;overflow:hidden;}
#rsvp::before{content:'✦';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-size:clamp(120px,25vw,240px);color:rgba(201,168,76,0.03);pointer-events:none;}
.rsvp-card{max-width:500px;margin:44px auto 0;background:rgba(201,168,76,0.04);border:1px solid rgba(201,168,76,0.18);padding:44px 32px;text-align:center;}
.rsvp-dear{font-family:'Great Vibes',cursive;font-size:clamp(32px,6vw,46px);color:var(--gold-light);margin-bottom:10px;}
.rsvp-msg{font-family:'Noto Sans Sinhala',sans-serif;font-size:13px;color:rgba(245,239,224,0.5);line-height:2;margin-bottom:28px;}
.rsvp-field-group{display:flex;flex-direction:column;gap:14px;margin-bottom:24px;text-align:left;}
.rsvp-field{display:flex;flex-direction:column;gap:6px;}
.rsvp-field-label{font-family:'Noto Sans Sinhala',sans-serif;font-size:10px;letter-spacing:1px;color:var(--gold);}
.rsvp-input{background:rgba(201,168,76,0.05);border:1px solid rgba(201,168,76,0.2);color:var(--cream);font-family:'Noto Sans Sinhala',sans-serif;font-size:14px;padding:10px 14px;outline:none;transition:border-color 0.25s ease;}
.rsvp-input:focus{border-color:rgba(201,168,76,0.6);}
.rsvp-input option{background:#1a1408;color:var(--cream);}
.rsvp-textarea{resize:vertical;min-height:80px;}
.rsvp-choices{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin-bottom:24px;}
.rsvp-btn{padding:12px 22px;border:1px solid rgba(201,168,76,0.3);background:transparent;color:rgba(201,168,76,0.7);font-family:'Noto Sans Sinhala',sans-serif;font-size:14px;cursor:pointer;transition:all 0.25s ease;min-width:110px;}
.rsvp-btn:hover{border-color:var(--gold);color:var(--gold-light);background:rgba(201,168,76,0.08);}
.rsvp-btn.selected-accept{background:rgba(34,197,94,0.15);border-color:#22c55e;color:#86efac;}
.rsvp-btn.selected-decline{background:rgba(239,68,68,0.15);border-color:#ef4444;color:#fca5a5;}
.rsvp-btn.selected-maybe{background:rgba(234,179,8,0.15);border-color:#eab308;color:#fde047;}
.rsvp-hint{font-family:'Noto Sans Sinhala',sans-serif;font-size:11px;color:rgba(201,168,76,0.4);margin-bottom:20px;}
.rsvp-whatsapp{display:inline-flex;align-items:center;gap:10px;padding:14px 32px;background:linear-gradient(135deg,#25D366,#128C7E);color:#fff;font-family:'Noto Sans Sinhala',sans-serif;font-size:13px;font-weight:600;text-decoration:none;box-shadow:0 4px 20px rgba(37,211,102,0.3);transition:opacity 0.2s;}
.rsvp-whatsapp.disabled{opacity:0.35;pointer-events:none;}
.rsvp-no-contact{font-family:'Noto Sans Sinhala',sans-serif;color:rgba(201,168,76,0.5);font-size:12px;}

/* ── Closing ── */
#closing{position:relative;padding:100px 20px;text-align:center;overflow:hidden;}
.closing-bg{position:absolute;inset:0;background:radial-gradient(ellipse at center,#2a1e08 0%,#1a1408 40%,#0c0a06 100%);}
.closing-inner{position:relative;z-index:1;max-width:600px;margin:0 auto;}
.closing-ornament{font-size:20px;color:rgba(201,168,76,0.5);letter-spacing:10px;margin-bottom:24px;}
.closing-names{font-family:'Great Vibes',cursive;font-size:clamp(44px,10vw,80px);color:var(--gold-light);text-shadow:0 0 60px rgba(201,168,76,0.5);line-height:1.2;margin-bottom:8px;}
.closing-date{font-family:'Cinzel',serif;font-size:13px;letter-spacing:4px;color:var(--gold);margin-top:16px;}
.closing-venue{font-size:12px;letter-spacing:2px;color:rgba(201,168,76,0.5);margin-top:6px;}
.closing-msg{margin-top:32px;font-family:'Noto Sans Sinhala',sans-serif;font-size:15px;color:rgba(245,239,224,0.6);line-height:2.2;}
.closing-hearts{font-size:24px;letter-spacing:8px;margin-top:28px;animation:heartbeat 2s ease-in-out infinite;}
@keyframes heartbeat{0%,100%{transform:scale(1);}50%{transform:scale(1.08);}}

/* ── Footer ── */
#footer{background:#060503;padding:60px 20px;text-align:center;}
.footer-ornament{display:flex;align-items:center;justify-content:center;gap:12px;margin-bottom:28px;}
.footer-ornament-line{flex:1;max-width:100px;height:1px;background:rgba(201,168,76,0.25);}
.footer-names{font-family:'Great Vibes',cursive;font-size:clamp(32px,7vw,52px);color:var(--gold);margin-bottom:10px;}
.footer-tagline{font-family:'Noto Sans Sinhala',sans-serif;font-size:14px;color:rgba(201,168,76,0.5);margin-bottom:20px;}
.footer-date{font-family:'Cinzel',serif;font-size:12px;letter-spacing:4px;color:rgba(201,168,76,0.4);margin-top:16px;}
.footer-venue{font-family:'Noto Sans Sinhala',sans-serif;font-size:11px;color:rgba(245,239,224,0.25);margin-top:6px;}
.footer-bottom{font-family:'Noto Sans Sinhala',sans-serif;font-size:11px;color:rgba(201,168,76,0.3);margin-top:24px;}

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
  .gallery-grid{grid-template-columns:1fr 1fr !important;}
  .gallery-item-wide{grid-column:span 2;}
  .inv-details{flex-direction:column;align-items:center;}
  .inv-detail-sep{transform:rotate(90deg);}
}
</style>

<!-- ── RSVP ── -->
@if($sec['rsvp'] ?? true)
@php
  $guestSide  = isset($guest) ? ($guest->group?->slug ?? '') : '';
  $waNumber   = ($guestSide === 'bride-side' && $invitation->bride_phone)
                  ? $invitation->bride_phone
                  : ($invitation->groom_phone ?? $invitation->bride_phone ?? '');
  $waNumber   = preg_replace('/\D/', '', $waNumber);
  $guestLabel = isset($guestName) ? $guestName : 'ආරාධිතයා';
  $coupleName = $invitation->bride_name . ' සහ ' . $invitation->groom_name;
@endphp
<section id="rsvp">
  <div class="section">
    <span class="section-label reveal">ඔබේ පැමිණීම වැදගත්</span>
    <h2 class="section-title reveal" style="color:var(--gold-light);">කරුණාකර ප්‍රතිචාර දක්වන්න</h2>
    <div class="gold-divider reveal"><span>✦</span></div>
    <div class="rsvp-card reveal">
      @if(isset($guestName))<div class="rsvp-dear">{{ $guestName }},</div>@endif
      <p class="rsvp-msg">ඔබේ ගෞරවනීය පැමිණීම අපි නිහතමානීව ඉල්ලා සිටිමු<br>අපේ ජීවිතයේ වඩාත්ම ශුභ දිනයේ.</p>
      @if($waNumber)
      <div class="rsvp-field-group">
        <div class="rsvp-field">
          <label class="rsvp-field-label">ඔබේ නම</label>
          <input type="text" id="rsvp-name" class="rsvp-input" placeholder="ඔබේ නම ඇතුළත් කරන්න" value="{{ $guestName ?? '' }}">
        </div>
        <div class="rsvp-field">
          <label class="rsvp-field-label">අමුත්තන් ගණන</label>
          <select id="rsvp-count" class="rsvp-input">
            <option value="1">1 (මම පමණයි)</option>
            <option value="2">දෙදෙනා</option>
            <option value="3">තිදෙනා</option>
            <option value="4">හතරදෙනා</option>
            <option value="5+">5+</option>
          </select>
        </div>
        <div class="rsvp-field">
          <label class="rsvp-field-label">යුවළට පණිවිඩය <span style="opacity:0.5;font-size:11px;">(අවශ්‍ය නැත)</span></label>
          <textarea id="rsvp-message" class="rsvp-input rsvp-textarea" placeholder="ඔබේ ආශිර්වාද බෙදාගන්න..."></textarea>
        </div>
      </div>
      <div class="rsvp-choices">
        <button type="button" class="rsvp-btn" id="btn-accepted" onclick="selectRsvp('accepted')">✓ පිළිගනිමි</button>
        <button type="button" class="rsvp-btn" id="btn-declined" onclick="selectRsvp('declined')">✕ නොඑමි</button>
        <button type="button" class="rsvp-btn" id="btn-maybe"    onclick="selectRsvp('maybe')">? සමහරවිට</button>
      </div>
      <p class="rsvp-hint" id="rsvp-hint">ඔබේ ප්‍රතිචාරය තෝරා WhatsApp හරහා යවන්න</p>
      <a id="rsvp-wa-btn" href="#" class="rsvp-whatsapp disabled" onclick="return sendRsvp(event)">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        <span id="rsvp-wa-label">ප්‍රතිචාරය යවන්න</span>
      </a>
      @else
      <p class="rsvp-no-contact">සම්බන්ධතා තොරතුරු තවම එකතු කර නැත.</p>
      @endif
    </div>
  </div>
</section>
@endif

<!-- ── CLOSING ── -->
@if($sec['closing'] ?? true)
<section id="closing">
  <div class="closing-bg"></div>
  <div class="closing-inner">
    <div class="closing-ornament reveal">❧ ✦ ❧</div>
    <div class="closing-names reveal">{{ $invitation->bride_name }} &amp; {{ $invitation->groom_name }}</div>
    <div class="gold-divider reveal" style="max-width:240px;"><span>✦</span></div>
    <p class="closing-date reveal">{{ $d['dayOfWeek'] }}, {{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }} {{ $d['year'] }}</p>
    <p class="closing-venue reveal">{{ $d['venueName'] }}@if($d['venueCity']), {{ $d['venueCity'] }}@endif</p>
    <div class="closing-msg reveal">
      <p>අපේ කතාවේ කොටසක් වීමට ස්තූතියි.</p>
      <p>ඔබේ ආදරය හා පැමිණීම අපට ලෝකයක් වටිනවා.</p>
    </div>
    <div class="closing-hearts reveal">❤️ 🌸 ❤️</div>
  </div>
</section>
@endif

<!-- ── FOOTER ── -->
<footer id="footer">
  <div class="footer-ornament">
    <span class="footer-ornament-line"></span>
    <span style="color:var(--gold);font-size:13px;">✦ ❧ ✦</span>
    <span class="footer-ornament-line"></span>
  </div>
  <div class="footer-names">{{ $invitation->bride_name }} &amp; {{ $invitation->groom_name }}</div>
  <p class="footer-tagline">&ldquo; ඔවුහු සදාකාලිකව සතුටින් ජීවත් වූහ &rdquo;</p>
  <div class="gold-divider" style="margin:16px auto;max-width:200px;"><span>✦</span></div>
  <div class="footer-date">{{ $d['dayNumber'] }}{{ $d['daySuffix'] }} {{ $d['month'] }} {{ $d['year'] }}</div>
  <p class="footer-venue">{{ $d['venueName'] }}@if($d['venueCity']), {{ $d['venueCity'] }}@endif</p>
  <p class="footer-bottom">ආදරයෙන් <span style="color:var(--gold);">♥</span> ජීවිත කාලයක ආදරය සඳහා</p>
</footer>

</div><!-- end #main-content -->

<script>
// ── LIGHTBOX ──
function openLightbox(src) {
  document.getElementById('lb-img').src = src;
  document.getElementById('lightbox').classList.add('active');
  document.body.style.overflow = 'hidden';
}
function closeLightbox() {
  document.getElementById('lightbox').classList.remove('active');
  document.body.style.overflow = 'auto';
}

// ── RSVP ──
const waNumber   = '{{ $waNumber ?? '' }}';
const guestLabel = '{{ addslashes($guestLabel ?? 'ආරාධිතයා') }}';
const coupleName = '{{ addslashes($coupleName ?? '') }}';
const weddingDay = '{{ addslashes($invitation->wedding_day ?? '') }}';
const rsvpUrl    = '{{ isset($token) ? route('invite.rsvp', $token) : '' }}';
const csrfToken  = '{{ csrf_token() }}';
let selectedRsvp = null;
const rsvpBtnLabels = {
  accepted: '✓ පිළිගැනීම යවන්න',
  declined: '✕ නොඑන බව යවන්න',
  maybe:    '? සමහරවිට යවන්න',
};
function selectRsvp(choice) {
  selectedRsvp = choice;
  ['accepted','declined','maybe'].forEach(c => {
    const map = { accepted:'accept', declined:'decline', maybe:'maybe' };
    document.getElementById('btn-' + c).className = 'rsvp-btn' + (c === choice ? ' selected-' + map[c] : '');
  });
  const name    = document.getElementById('rsvp-name')?.value || guestLabel;
  const count   = document.getElementById('rsvp-count')?.value || '1';
  const message = document.getElementById('rsvp-message')?.value || '';
  const msgs = {
    accepted: `ආයුබෝවන්! මම ${name}. ${coupleName} ගේ විවාහ මංගල්යයට ${weddingDay} දින ${count} දෙනා සමඟ පැමිණීමට සතුටින් තහවුරු කරමි. 🌹${message ? '\n\nපණිවිඩය: ' + message : ''}`,
    declined:  `ආයුබෝවන්! මම ${name}. ${coupleName} ගේ විවාහ මංගල්යයට ${weddingDay} දින පැමිණීමට නොහැකි වීම ගැන කනගාටුවෙමි. ඔබට ශුභ දිනයක් වේවා! 🙏${message ? '\n\nපණිවිඩය: ' + message : ''}`,
    maybe:     `ආයුබෝවන්! මම ${name}. ${coupleName} ගේ විවාහ මංගල්යයට ${weddingDay} දින — පැමිණිය හැකිද යන්න තවම නිශ්චිත නැත. ඉක්මනින් දන්වන්නම්. 🌸${message ? '\n\nපණිවිඩය: ' + message : ''}`,
  };
  const waBtn = document.getElementById('rsvp-wa-btn');
  waBtn.classList.remove('disabled');
  waBtn.href = 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent(msgs[choice]);
  document.getElementById('rsvp-wa-label').textContent = rsvpBtnLabels[choice];
}
function sendRsvp(e) {
  if (!selectedRsvp || !rsvpUrl) return true;
  fetch(rsvpUrl, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken}, body:JSON.stringify({rsvp:selectedRsvp}) }).catch(()=>{});
  return true;
}

// ── COUNTDOWN ──
@php $weddingTs = ($invitation->wedding->wedding_date ? $invitation->wedding->wedding_date->timestamp * 1000 : 0); @endphp
const weddingTime = {{ $weddingTs }};
function updateCountdown() {
  const diff = weddingTime - Date.now();
  if (diff <= 0) { ['cd-days','cd-hours','cd-mins','cd-secs'].forEach(id => document.getElementById(id).textContent = '00'); return; }
  document.getElementById('cd-days').textContent  = String(Math.floor(diff / 86400000)).padStart(2,'0');
  document.getElementById('cd-hours').textContent = String(Math.floor((diff % 86400000) / 3600000)).padStart(2,'0');
  document.getElementById('cd-mins').textContent  = String(Math.floor((diff % 3600000) / 60000)).padStart(2,'0');
  document.getElementById('cd-secs').textContent  = String(Math.floor((diff % 60000) / 1000)).padStart(2,'0');
}
updateCountdown(); setInterval(updateCountdown, 1000);

// ── MUSIC ──
@if($sec['music'] ?? true)
let musicOn = false;
const audio = document.getElementById('bg-music');
const musicBtn = document.getElementById('music-btn');
function toggleMusic() {
  if (musicOn) { audio.pause(); musicBtn.textContent = '🎵 Music Off'; musicOn = false; }
  else { audio.play().catch(()=>{}); musicBtn.textContent = '🎵 Music On'; musicOn = true; }
}
@else
function toggleMusic() {}
@endif

// ── SCROLL REVEAL ──
function triggerReveal() {
  const io = new IntersectionObserver((entries) => {
    entries.forEach((e, i) => {
      if (e.isIntersecting) { setTimeout(() => e.target.classList.add('visible'), i * 80); io.unobserve(e.target); }
    });
  }, { threshold: 0.12 });
  document.querySelectorAll('.reveal').forEach(el => io.observe(el));
}

@if(!$isPreviewPane)
// ── VIDEO → FLASH → REVEAL ──
function doFlashReveal() {
  const flash = document.getElementById('flash');
  const main  = document.getElementById('main-content');
  const heroText = document.getElementById('hero-text');
  const scrollHint = document.getElementById('scroll-hint');
  const heroWrap = document.getElementById('hero-video-wrap');
  flash.classList.add('flash-on');
  setTimeout(() => {
    main.classList.add('visible');
    heroText.classList.add('visible');
    heroWrap.classList.add('bars-gone');
    flash.classList.remove('flash-on');
    flash.classList.add('flash-fade');
    setTimeout(() => { flash.style.display = 'none'; scrollHint.classList.add('visible'); triggerReveal(); }, 1800);
  }, 200);
}
(function() {
  const video   = document.getElementById('hero-video');
  const loading = document.getElementById('loading-screen');
  let revealed  = false;
  function startExperience() {
    if (revealed) return;
    revealed = true;
    loading.classList.add('fade-out');
    video.play().catch(() => {});
    video.addEventListener('ended', doFlashReveal, { once: true });
    if (video.duration && video.duration < 30) {
      setTimeout(doFlashReveal, (video.duration * 1000) + 200);
    }
  }
  function checkReady() {
    if (video.readyState >= 4) { startExperience(); return; }
    video.addEventListener('canplaythrough', startExperience, { once: true });
    setTimeout(startExperience, 8000);
  }
  if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', checkReady); }
  else { checkReady(); }
  document.body.style.overflow = 'hidden';
  loading.addEventListener('transitionend', () => { document.body.style.overflow = 'auto'; }, { once: true });
})();
@else
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.reveal').forEach(el => el.classList.add('visible'));
});
@endif
</script>
</body>
</html>
