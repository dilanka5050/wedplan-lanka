<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Wedding Invitation — {{ $invitation->bride_name }} & {{ $invitation->groom_name }}{{ $isGuest ? ' — ' . $guest->name : '' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Cormorant+Garamond:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{background:#f2f2f2;display:flex;flex-direction:column;align-items:center;min-height:100vh;padding:20px;font-family:'Cormorant Garamond',serif;}
.toolbar{width:800px;display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
.toolbar a{font-family:sans-serif;font-size:13px;color:#6b7280;text-decoration:none;padding:8px 16px;border:1px solid #e5e7eb;border-radius:999px;background:white;}
.toolbar a:hover{background:#f9fafb;}
.toolbar button{font-family:sans-serif;font-size:13px;font-weight:600;color:white;background:linear-gradient(135deg,#db2777,#9333ea);border:none;padding:8px 20px;border-radius:999px;cursor:pointer;box-shadow:0 2px 8px rgba(219,39,119,0.3);}
.guest-badge{font-family:sans-serif;font-size:12px;background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;padding:6px 14px;border-radius:999px;}
.invitation-card{width:800px;min-height:1200px;position:relative;padding:140px 90px 120px;background-color:white;box-shadow:0 10px 30px rgba(0,0,0,0.15);overflow:hidden;-webkit-print-color-adjust:exact;print-color-adjust:exact;}
.bg-img{position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;z-index:0;}
.content{text-align:center;position:relative;z-index:2;}
.couple-name{font-family:'Great Vibes',cursive;color:#15744e;font-size:82px;line-height:1.1;margin-top:40px;margin-bottom:30px;}
.details{font-size:20px;line-height:1.4;color:#222;text-transform:uppercase;margin-top:22px;letter-spacing:0.5px;}
.date-section{display:flex;justify-content:center;align-items:center;gap:30px;margin-top:45px;}
.date-box{width:190px;position:relative;font-size:28px;text-transform:uppercase;text-align:center;}
.date-box::before{content:'';position:absolute;top:-12px;left:0;width:100%;border-top:4px solid #333;}
.date-box::after{content:'';position:absolute;bottom:-12px;left:0;width:100%;border-bottom:4px solid #333;}
.day-number{font-size:78px;color:#15744e;font-weight:700;line-height:1;}
.day-number sup{font-size:35px;}
.year{font-size:26px;margin-top:10px;}
.venue{margin-top:55px;}
.venue .at{font-size:34px;margin-bottom:10px;}
.venue h2{font-size:38px;font-weight:700;text-transform:uppercase;}
.venue h3{font-size:26px;margin-top:5px;font-weight:500;}
.time{margin-top:35px;font-size:22px;line-height:1.5;}
.guest-name{font-size:22px;color:#15744e;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;margin-top:6px;border-bottom:1px solid #15744e;display:inline-block;min-width:300px;padding-bottom:2px;}
@media print{@page{size:800px 1200px;margin:0;}body{background:white;padding:0;}.toolbar{display:none;}.invitation-card{box-shadow:none;width:100%;min-height:100vh;}}
</style>
</head>
<body>
@include('invitations.templates._toolbar', ['jpegColor' => '#15744e', 'jpegBorder' => '#bbf7d0', 'cardSelector' => '.invitation-card'])
<div class="invitation-card">
    <img src="/back2.png" class="bg-img" alt="" crossorigin="anonymous">
    <div class="content">
        <div class="couple-name">{{ $invitation->bride_name }} & {{ $invitation->groom_name }}</div>
        <div class="details">
            DAUGHTER OF MR. & MRS. {{ strtoupper($invitation->bride_surname) }}<br>
            AND<br>
            SON OF MR. & MRS. {{ strtoupper($invitation->groom_surname) }}
        </div>
        <div class="details">
            REQUEST THE HONOUR OF PRESENCE OF<br>
            @if($isGuest)<span class="guest-name">{{ $guestLine }}</span>@else MR. & MRS. / MR. / MRS. / MISS. / FAMILY @endif
        </div>
        <div class="details">ON THE OCCASION OF THEIR CHILDREN<br>MARRIAGE</div>
        <div class="date-section">
            <div class="date-box">{{ $d['dayOfWeek'] }}</div>
            <div>
                <div class="day-number">{{ $d['dayNumber'] }}<sup>{{ $d['daySuffix'] }}</sup></div>
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
