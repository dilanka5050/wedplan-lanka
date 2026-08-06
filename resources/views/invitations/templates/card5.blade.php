<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Wedding Invitation — {{ $invitation->bride_name }} & {{ $invitation->groom_name }}{{ $isGuest ? ' — ' . $guest->name : '' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Playfair+Display:ital,wght@0,400;1,700&family=Lato:wght@300;400&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{background:#f4f4f4;display:flex;flex-direction:column;align-items:center;min-height:100vh;padding:20px;font-family:'Lato',sans-serif;color:#333;}
.toolbar{width:600px;display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
.toolbar a{font-family:sans-serif;font-size:13px;color:#6b7280;text-decoration:none;padding:8px 16px;border:1px solid #e5e7eb;border-radius:999px;background:white;}
.toolbar a:hover{background:#f9fafb;}
.toolbar button{font-family:sans-serif;font-size:13px;font-weight:600;color:white;background:linear-gradient(135deg,#db2777,#9333ea);border:none;padding:8px 20px;border-radius:999px;cursor:pointer;box-shadow:0 2px 8px rgba(219,39,119,0.3);}
.guest-badge{font-family:sans-serif;font-size:12px;background:#fefce8;border:1px solid #fde68a;color:#92400e;padding:6px 14px;border-radius:999px;}
.invitation-card{width:500px;background-color:#fff;background-image:url('/back5.png');background-size:cover;background-position:center;padding:60px 40px;box-shadow:0 10px 30px rgba(0,0,0,0.1);text-align:center;position:relative;box-sizing:border-box;-webkit-print-color-adjust:exact;print-color-adjust:exact;}
.content{margin-top:370px;}
h3{font-family:'Cinzel',serif;font-size:14px;letter-spacing:2px;margin-bottom:5px;text-transform:uppercase;}
.names-section{font-family:'Cinzel',serif;font-size:13px;line-height:1.8;margin-bottom:25px;}
.couple-names{font-family:'Playfair Display',serif;font-size:36px;font-style:italic;color:#b08d57;margin:20px 0;font-weight:700;}
.request-text{font-size:12px;text-transform:uppercase;letter-spacing:1px;margin-bottom:20px;line-height:1.7;}
.guest-name{font-size:14px;color:#b08d57;font-weight:700;letter-spacing:1px;text-transform:uppercase;border-bottom:1px solid #b08d57;display:inline-block;min-width:200px;padding-bottom:2px;margin-top:4px;}
.wedding-details{text-transform:uppercase;font-weight:bold;font-size:13px;letter-spacing:1.5px;margin-bottom:30px;}
.info-grid{display:flex;justify-content:center;align-items:center;gap:50px;margin:30px 0;font-family:'Cinzel',serif;}
.info-column{width:180px;text-align:center;position:relative;}
.info-column::before{content:'';position:absolute;top:-10px;left:20px;right:20px;border-top:1px solid #888;}
.info-column::after{content:'';position:absolute;bottom:-10px;left:20px;right:20px;border-bottom:1px solid #888;}
.info-title{font-size:11px;letter-spacing:3px;color:#888;margin-bottom:10px;text-transform:uppercase;}
.info-main{font-size:14px;line-height:1.9;letter-spacing:1px;color:#333;}
.info-highlight{font-size:22px;color:#b08d57;font-weight:bold;}
.venue{font-weight:bold;font-size:16px;margin-top:18px;line-height:1.8;letter-spacing:1px;text-transform:uppercase;}
.ceremony{font-size:11px;margin-top:15px;font-style:italic;letter-spacing:1px;text-transform:uppercase;}
@media print{@page{size:500px 900px;margin:0;}body{background:white;padding:0;}.toolbar{display:none;}.invitation-card{box-shadow:none;width:100%;}}
</style>
</head>
<body>
@include('invitations.templates._toolbar', ['jpegColor' => '#b08d57', 'jpegBorder' => '#fde68a', 'cardSelector' => '.invitation-card'])
<div class="invitation-card">
    <div class="content">
        <h3>Together with Their Parents</h3>
        <div class="names-section">
            DAUGHTER OF MR. & MRS. {{ strtoupper($invitation->bride_surname) }}<br>
            &<br>
            SON OF MR. & MRS. {{ strtoupper($invitation->groom_surname) }}
        </div>
        <div class="couple-names">{{ $invitation->bride_name }} & {{ $invitation->groom_name }}</div>
        <div class="request-text">
            Request the honor of the presence of<br>
            @if($isGuest)<span class="guest-name">{{ $guestLine }}</span>@else Mr. / Mrs. / Miss. / Family @endif
        </div>
        <div class="wedding-details">To Celebrate Our Wedding</div>
        <div class="info-grid">
            <div class="info-column">
                <div class="info-title">Time</div>
                <div class="info-main">
                    FROM<br>
                    <span class="info-highlight">{{ strtoupper($invitation->duration_from) }}</span><br>
                    TO {{ strtoupper($invitation->duration_to) }}
                </div>
            </div>
            <div class="info-column">
                <div class="info-title">Date</div>
                <div class="info-main">
                    {{ strtoupper($d['dayOfWeek']) }}<br>
                    <span class="info-highlight">{{ $d['dayNumber'] }}<sup>{{ $d['daySuffix'] }}</sup></span><br>
                    {{ strtoupper($d['month']) }} {{ $d['year'] }}
                </div>
            </div>
        </div>
        <div class="venue">
            AT<br>
            {{ strtoupper($d['venueName']) }}<br>
            @if($d['venueCity'])({{ strtoupper($d['venueCity']) }})@endif
        </div>
        <div class="ceremony">(PORUWA CEREMONY AT {{ strtoupper($invitation->poruwa_time) }})</div>
    </div>
</div>
</body>
</html>
