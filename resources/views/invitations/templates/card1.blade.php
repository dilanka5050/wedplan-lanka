<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Wedding Invitation — {{ $invitation->bride_name }} & {{ $invitation->groom_name }}{{ $isGuest ? ' — ' . $guest->name : '' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Cormorant+Garamond:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{background:#f2f2f2;display:flex;flex-direction:column;align-items:center;min-height:100vh;padding:20px;font-family:'Cormorant Garamond',serif;}
.toolbar{width:800px;display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
.toolbar a{font-family:sans-serif;font-size:13px;color:#6b7280;text-decoration:none;padding:8px 16px;border:1px solid #e5e7eb;border-radius:999px;background:white;}
.toolbar a:hover{background:#f9fafb;}
.toolbar button{font-family:sans-serif;font-size:13px;font-weight:600;color:white;background:linear-gradient(135deg,#db2777,#9333ea);border:none;padding:8px 20px;border-radius:999px;cursor:pointer;box-shadow:0 2px 8px rgba(219,39,119,0.3);}
.guest-badge{font-family:sans-serif;font-size:12px;background:#fdf2f8;border:1px solid #fbcfe8;color:#be185d;padding:6px 14px;border-radius:999px;}
.invitation-card{width:800px;min-height:1200px;position:relative;padding:120px 90px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.15);background-color:white;-webkit-print-color-adjust:exact;print-color-adjust:exact;}
.bg-img{position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;z-index:0;}
.content{text-align:center;position:relative;z-index:2;}
.couple-names{color:#b32017;font-family:'Great Vibes',cursive;line-height:1.1;}
.couple-names .name{font-size:78px;}
.couple-names .amp{font-size:60px;margin:10px 0;}
.details{margin-top:35px;font-size:18px;color:#222;line-height:1.5;letter-spacing:0.5px;text-transform:uppercase;}
.date-section{margin-top:50px;display:flex;justify-content:center;align-items:center;gap:30px;}
.date-box{width:180px;text-align:center;position:relative;font-size:24px;text-transform:uppercase;}
.date-box::before{content:'';position:absolute;top:-12px;left:0;width:100%;border-top:3px solid #333;}
.day-number{font-size:72px;color:#b32017;font-weight:700;line-height:1;}
.year{font-size:24px;margin-top:8px;}
.venue{margin-top:40px;}
.venue .at{font-size:28px;margin-bottom:10px;}
.venue h2{font-size:34px;font-weight:700;text-transform:uppercase;}
.venue h3{font-size:24px;font-weight:500;margin-top:8px;}
.time{margin-top:20px;font-size:20px;line-height:1.5;}
.couple-image{position:absolute;top:120px;right:90px;width:130px;z-index:3;}
.guest-name{font-size:20px;color:#b32017;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;margin-top:6px;border-bottom:1px solid #b32017;display:inline-block;min-width:300px;padding-bottom:2px;}
@media print{@page{size:800px 1200px;margin:0;}body{background:white;padding:0;}.toolbar{display:none;}.invitation-card{box-shadow:none;width:100%;min-height:100vh;}}
</style>
</head>
<body>
@include('invitations.templates._toolbar', ['jpegColor' => '#db2777', 'jpegBorder' => '#fbcfe8', 'cardSelector' => '.invitation-card'])
<div class="invitation-card">
    <img src="/back1.png" class="bg-img" alt="" crossorigin="anonymous">
    <img src="/couple1.png" class="couple-image" alt="Couple" crossorigin="anonymous">
    <div class="content">
        <div class="couple-names">
            <div class="name">{{ $invitation->bride_name }}</div>
            <div class="amp">&</div>
            <div class="name">{{ $invitation->groom_name }}</div>
        </div>
        <div class="details">
            DAUGHTER OF MR. & MRS. {{ strtoupper($invitation->bride_surname) }}<br>
            AND<br>
            SON OF MR. & MRS. {{ strtoupper($invitation->groom_surname) }}
        </div>
        <div class="details">
            REQUEST THE HONOUR OF PRESENCE OF<br>
            @if($isGuest)<span class="guest-name">{{ $guestLine }}</span>@else MR. & MRS. / MR. / MRS. / MISS. / FAMILY @endif
        </div>
        <div class="details">
            ON THE OCCASION OF THEIR SON & DAUGHTER<br>
            MARRIAGE CEREMONY
        </div>
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
