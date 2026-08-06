<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Wedding Invitation — {{ $invitation->bride_name }} & {{ $invitation->groom_name }}{{ $isGuest ? ' — ' . $guest->name : '' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Cormorant+Garamond:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{background:#f3f3f3;display:flex;flex-direction:column;align-items:center;min-height:100vh;padding:20px;font-family:'Cormorant Garamond',serif;}
.toolbar{width:800px;display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
.toolbar a{font-family:sans-serif;font-size:13px;color:#6b7280;text-decoration:none;padding:8px 16px;border:1px solid #e5e7eb;border-radius:999px;background:white;}
.toolbar a:hover{background:#f9fafb;}
.toolbar button{font-family:sans-serif;font-size:13px;font-weight:600;color:white;background:linear-gradient(135deg,#db2777,#9333ea);border:none;padding:8px 20px;border-radius:999px;cursor:pointer;box-shadow:0 2px 8px rgba(219,39,119,0.3);}
.guest-badge{font-family:sans-serif;font-size:12px;background:#fdf2f8;border:1px solid #fbcfe8;color:#be185d;padding:6px 14px;border-radius:999px;}
.invitation-card{width:800px;min-height:1200px;background-image:url('/back4.png');background-size:cover;background-position:center;background-repeat:no-repeat;background-color:white;position:relative;padding:90px 90px 170px;box-shadow:0 10px 30px rgba(0,0,0,0.15);-webkit-print-color-adjust:exact;print-color-adjust:exact;}
.content{text-align:center;}
.couple-name{font-family:'Great Vibes',cursive;color:#c44762;line-height:1.1;}
.couple-name .name{font-size:82px;}
.couple-name .amp{font-size:64px;margin:5px 0;}
.details{margin-top:28px;font-size:20px;line-height:1.4;text-transform:uppercase;color:#222;}
.request{margin-top:25px;font-size:20px;line-height:1.4;text-transform:uppercase;}
.guest-name{font-size:20px;color:#c44762;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;margin-top:6px;border-bottom:1px solid #c44762;display:inline-block;min-width:300px;padding-bottom:2px;}
.sub-title{font-size:22px;text-transform:uppercase;line-height:1.5;color:#222;}
.date-section{margin-top:45px;display:flex;justify-content:center;align-items:center;gap:28px;}
.date-box{width:180px;position:relative;text-align:center;font-size:30px;text-transform:uppercase;}
.date-box::before{content:'';position:absolute;top:-12px;left:0;width:100%;border-top:4px solid #333;}
.date-box::after{content:'';position:absolute;bottom:-12px;left:0;width:100%;border-bottom:4px solid #333;}
.day-number{font-size:76px;color:#c44762;font-weight:700;line-height:1;}
.year{margin-top:8px;font-size:28px;}
.venue{margin-top:35px;text-transform:uppercase;}
.venue .at{font-size:28px;margin-bottom:8px;}
.venue h2{font-size:38px;font-weight:700;}
.venue h3{font-size:24px;margin-top:5px;}
.time{margin-top:25px;font-size:20px;line-height:1.5;text-transform:uppercase;}
@media print{@page{size:800px 1200px;margin:0;}body{background:white;padding:0;}.toolbar{display:none;}.invitation-card{box-shadow:none;width:100%;min-height:100vh;}}
</style>
</head>
<body>
@include('invitations.templates._toolbar', ['jpegColor' => '#c44762', 'jpegBorder' => '#f9a8d4', 'cardSelector' => '.invitation-card'])
<div class="invitation-card">
    <div class="content">
        <br><br><br><br><br><br>
        <div class="couple-name">
            <div class="name">{{ $invitation->bride_name }}</div>
            <div class="amp">&</div>
            <div class="name">{{ $invitation->groom_name }}</div>
        </div>
        <div class="details">
            DAUGHTER OF MR. & MRS. {{ strtoupper($invitation->bride_surname) }}<br>
            AND<br>
            SON OF MR. & MRS. {{ strtoupper($invitation->groom_surname) }}
        </div>
        <div class="request">
            REQUEST THE HONOUR OF PRESENCE OF<br>
            @if($isGuest)<span class="guest-name">{{ $guestLine }}</span>@else MR. & MRS. / MR. / MRS. / MISS. / FAMILY @endif
        </div>
        <div class="sub-title">ON THE OCCASION OF THEIR SON & DAUGHTER<br>MARRIAGE CEREMONY</div>
        <div class="date-section">
            <div class="date-box">{{ $d['dayOfWeek'] }}</div>
            <div>
                <div class="day-number">{{ $d['dayNumber'] }}</div>
                <div class="year">{{ $d['year'] }}</div>
            </div>
            <div class="date-box">{{ $d['month'] }}</div>
        </div>
        <div class="venue">
            <div class="at">AT</div>
            <h2>{{ $d['venueName'] }}</h2>
            @if($d['venueCity'])<h3>{{ $d['venueCity'] }}</h3>@endif
        </div>
        <div class="time">
            FROM {{ strtoupper($invitation->duration_from) }} TO {{ strtoupper($invitation->duration_to) }}<br>
            (PORUWA CEREMONY AT {{ strtoupper($invitation->poruwa_time) }})
        </div>
    </div>
</div>
</body>
</html>
