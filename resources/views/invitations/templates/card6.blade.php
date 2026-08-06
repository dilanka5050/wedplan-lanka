<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Wedding Invitation — {{ $invitation->bride_name }} & {{ $invitation->groom_name }}{{ $isGuest ? ' — ' . $guest->name : '' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{background:#f0f0f0;display:flex;flex-direction:column;align-items:center;min-height:100vh;padding:20px;font-family:'Montserrat',sans-serif;color:#222;}
.toolbar{width:650px;display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
.toolbar a{font-family:sans-serif;font-size:13px;color:#6b7280;text-decoration:none;padding:8px 16px;border:1px solid #e5e7eb;border-radius:999px;background:white;}
.toolbar a:hover{background:#f9fafb;}
.toolbar button{font-family:sans-serif;font-size:13px;font-weight:600;color:white;background:linear-gradient(135deg,#db2777,#9333ea);border:none;padding:8px 20px;border-radius:999px;cursor:pointer;box-shadow:0 2px 8px rgba(219,39,119,0.3);}
.guest-badge{font-family:sans-serif;font-size:12px;background:#fdf2f8;border:1px solid #fbcfe8;color:#be185d;padding:6px 14px;border-radius:999px;}
.card{width:550px;min-height:800px;background-color:#fff;background-image:url('/back6.png');background-size:cover;background-position:center;box-shadow:0 15px 35px rgba(0,0,0,0.15);position:relative;padding:30px;box-sizing:border-box;text-align:center;overflow:hidden;-webkit-print-color-adjust:exact;print-color-adjust:exact;}
.header-text{font-family:'Great Vibes',cursive;font-size:38px;margin-top:5px;}
.parents-section{display:flex;justify-content:space-between;margin-top:15px;font-size:10px;line-height:1.4;font-weight:700;text-transform:uppercase;}
.parents-box{width:45%;}
.couple-names-container{display:flex;justify-content:space-between;align-items:center;margin-top:35px;padding:0 15px;}
.name{font-family:'Great Vibes',cursive;font-size:36px;width:40%;color:#333;}
.event-details{margin-top:25px;text-transform:uppercase;}
.celebrate-title{font-weight:700;font-size:14px;letter-spacing:1px;}
.date-container{display:flex;justify-content:center;align-items:center;margin:10px 0;font-size:16px;}
.day-big{font-size:40px;font-weight:700;border-left:1.5px solid #333;border-right:1.5px solid #333;padding:0 15px;margin:0 15px;}
.time-venue{font-weight:700;font-size:13px;line-height:1.6;}
.request-text{font-size:12px;margin-top:20px;line-height:1.4;text-transform:uppercase;}
.guest-name{font-size:12px;font-weight:700;letter-spacing:1px;border-bottom:1px solid #333;display:inline-block;min-width:180px;padding-bottom:2px;margin-top:4px;}
.great-occasion{font-weight:700;font-size:12px;text-transform:uppercase;}
.footer-divider{border-top:1px dotted #555;width:50%;margin:15px auto;}
.rsvp{font-weight:700;margin-top:5px;letter-spacing:2px;font-size:12px;}
@media print{@page{size:550px 800px;margin:0;}body{background:white;padding:0;}.toolbar{display:none;}.card{box-shadow:none;width:100%;}}
</style>
</head>
<body>
@include('invitations.templates._toolbar', ['jpegColor' => '#555', 'jpegBorder' => '#e5e7eb', 'cardSelector' => '.card'])
<div class="card">
    <div class="header-text">Together with their families</div>
    <br><br><br>
    <div class="parents-section">
        <div class="parents-box">
            <span style="font-weight:400;display:block;margin-bottom:2px;">DAUGHTER OF</span>
            MR. & MRS. {{ strtoupper($invitation->bride_surname) }}
        </div>
        <div class="parents-box">
            <span style="font-weight:400;display:block;margin-bottom:2px;">SON OF</span>
            MR. & MRS. {{ strtoupper($invitation->groom_surname) }}
        </div>
    </div>
    <div class="couple-names-container">
        <div class="name">{{ $invitation->bride_name }}</div>
        <div class="name">{{ $invitation->groom_name }}</div>
    </div>
    <br><br>
    <div class="event-details">
        <div class="celebrate-title">CELEBRATE THEIR WEDDING</div>
        <div class="date-container">
            <div>{{ strtoupper($d['month']) }}</div>
            <div class="day-big">{{ $d['dayNumber'] }}</div>
            <div>{{ $d['year'] }}</div>
        </div>
        <div style="font-weight:700;margin-bottom:8px;">{{ strtoupper($d['dayOfWeek']) }}</div>
        <div class="time-venue">
            FROM {{ strtoupper($invitation->duration_from) }} TO {{ strtoupper($invitation->duration_to) }}<br>
            AT<br>
            {{ strtoupper($d['venueName']) }}<br>
            @if($d['venueCity'])({{ strtoupper($d['venueCity']) }})@endif
        </div>
    </div>
    <div class="request-text">
        REQUEST THE HONOUR OF THE PRESENCE OF<br>
        @if($isGuest)<span class="guest-name">{{ $guestLine }}</span>@else MR. / MRS. / MISS. / FAMILY @endif
    </div>
    <div class="great-occasion">TO CELEBRATE THEIR GREAT OCCASION</div>
    <div class="footer-divider"></div>
    <div class="rsvp">R.S.V.P</div>
    <div style="font-size:11px;margin-top:4px;text-transform:uppercase;">(PORUWA CEREMONY AT {{ strtoupper($invitation->poruwa_time) }})</div>
</div>
</body>
</html>
