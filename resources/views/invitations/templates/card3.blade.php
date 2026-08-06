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
.guest-badge{font-family:sans-serif;font-size:12px;background:#fdf2f8;border:1px solid #fbcfe8;color:#be185d;padding:6px 14px;border-radius:999px;}
.invitation-card{width:800px;min-height:1200px;position:relative;padding:70px 90px 260px;background-color:white;box-shadow:0 10px 30px rgba(0,0,0,0.15);overflow:hidden;-webkit-print-color-adjust:exact;print-color-adjust:exact;}
.bg-img{position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;z-index:0;}
.content{text-align:center;position:relative;z-index:2;transform:translateY(-30px);}
.details{font-size:20px;color:#222;line-height:1.4;text-transform:uppercase;letter-spacing:0.5px;margin-top:16px;}
.sub-title{margin-top:25px;font-size:22px;text-transform:uppercase;line-height:1.5;color:#222;}
.couple-name{margin-top:25px;font-family:'Great Vibes',cursive;color:#c0176b;font-size:84px;line-height:1.1;}
.day{margin-top:22px;font-size:24px;text-transform:uppercase;color:#222;}
.date-row{margin-top:16px;display:flex;justify-content:center;align-items:center;gap:22px;}
.date-item{font-size:34px;color:#111;position:relative;padding:0 18px;}
.date-item.border-right::after{content:'';position:absolute;top:0;right:-10px;width:2px;height:100%;background:#111;}
.date-number{font-size:72px;font-weight:700;line-height:1;}
.year{font-size:34px;}
.time{margin-top:30px;font-size:22px;line-height:1.5;text-transform:uppercase;}
.venue{margin-top:18px;font-size:26px;line-height:1.5;text-transform:uppercase;}
.venue strong{font-weight:700;}
.guest-name{font-size:20px;color:#c0176b;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;margin-top:6px;border-bottom:1px solid #c0176b;display:inline-block;min-width:300px;padding-bottom:2px;}
@media print{@page{size:800px 1200px;margin:0;}body{background:white;padding:0;}.toolbar{display:none;}.invitation-card{box-shadow:none;width:100%;min-height:100vh;}}
</style>
</head>
<body>
@include('invitations.templates._toolbar', ['jpegColor' => '#c0176b', 'jpegBorder' => '#fbcfe8', 'cardSelector' => '.invitation-card'])
<div class="invitation-card">
    <img src="/back3.png" class="bg-img" alt="" crossorigin="anonymous">
    <div class="content">
        <div class="details">
            DAUGHTER OF MR. & MRS. {{ strtoupper($invitation->bride_surname) }}<br>
            TOGETHER WITH<br>
            SON OF MR. & MRS. {{ strtoupper($invitation->groom_surname) }}
        </div>
        <div class="details">
            REQUEST THE HONOR OF THE PRESENCE OF<br>
            @if($isGuest)<span class="guest-name">{{ $guestLine }}</span>@else MR. & MRS / MR / MRS / MISS / FAMILY @endif
        </div>
        <div class="sub-title">ON THE OCCASION OF THEIR MARRIAGE OF<br>THEIR LOVING DAUGHTER & SON</div>
        <div class="couple-name">{{ $invitation->bride_name }} & {{ $invitation->groom_name }}</div>
        <div class="day">ON {{ $d['dayOfWeek'] }}</div>
        <div class="date-row">
            <div class="date-item border-right">{{ $d['month'] }}</div>
            <div class="date-item border-right"><div class="date-number">{{ $d['dayNumber'] }}</div></div>
            <div class="date-item year">{{ $d['year'] }}</div>
        </div>
        <div class="time">
            (PORUWA CEREMONY AT {{ strtoupper($invitation->poruwa_time) }})<br>
            FROM {{ strtoupper($invitation->duration_from) }} TO {{ strtoupper($invitation->duration_to) }}
        </div>
        <div class="venue">
            @php $lp = explode(',', $invitation->wedding_location, 2); @endphp
            AT THE {{ strtoupper(trim($lp[0])) }}<br>
            @if(!empty($lp[1]))<strong>{{ strtoupper(trim($lp[1])) }}</strong>@endif
        </div>
    </div>
</div>
</body>
</html>
