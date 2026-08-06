<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Guest List — {{ $wedding->bride_name }} & {{ $wedding->groom_name }}</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background: #f8f7ff; color: #111; }

    /* Toolbar — hidden on print */
    .toolbar {
        position: fixed; top: 0; left: 0; right: 0; z-index: 50;
        background: white; border-bottom: 1px solid #e5e7eb;
        padding: 12px 24px; display: flex; align-items: center; justify-content: space-between;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }
    .toolbar-left { display: flex; align-items: center; gap: 12px; }
    .btn-print {
        background: linear-gradient(135deg, #db2777, #9333ea);
        color: white; font-size: 13px; font-weight: 600;
        padding: 8px 20px; border-radius: 999px; border: none; cursor: pointer;
        box-shadow: 0 2px 8px rgba(219,39,119,0.3);
    }
    .btn-back {
        font-size: 13px; color: #6b7280; text-decoration: none;
        padding: 8px 16px; border: 1px solid #e5e7eb; border-radius: 999px; background: white;
    }
    .btn-back:hover { background: #f9fafb; }

    /* Page content */
    .page { max-width: 900px; margin: 80px auto 40px; padding: 0 24px; }

    /* Header */
    .header { text-align: center; margin-bottom: 32px; padding-bottom: 24px; border-bottom: 2px solid #fce7f3; }
    .header h1 { font-family: 'Playfair Display', serif; font-size: 28px; color: #111; }
    .header p { font-size: 13px; color: #6b7280; margin-top: 4px; }

    /* Stats row */
    .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 28px; }
    .stat { background: white; border: 1px solid #f3f4f6; border-radius: 12px; padding: 14px 16px; text-align: center; }
    .stat-value { font-size: 22px; font-weight: 900; color: #111; }
    .stat-label { font-size: 11px; color: #9ca3af; margin-top: 2px; }

    /* Table */
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
    thead { background: linear-gradient(135deg, #fce7f3, #f3e8ff); }
    th { text-align: left; padding: 10px 14px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; }
    td { padding: 10px 14px; font-size: 13px; border-bottom: 1px solid #f9fafb; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #fdf2f8; }

    /* RSVP badges */
    .badge { display: inline-block; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 999px; }
    .badge-accepted  { background: #dcfce7; color: #15803d; }
    .badge-declined  { background: #fee2e2; color: #dc2626; }
    .badge-pending   { background: #fef9c3; color: #854d0e; }
    .badge-maybe     { background: #fef3c7; color: #92400e; }

    /* Group header rows */
    .group-header td { background: #f8f7ff; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #9333ea; padding: 8px 14px; }

    /* Footer */
    .footer { text-align: center; margin-top: 32px; font-size: 11px; color: #d1d5db; }

    /* Print styles */
    @media print {
        body { background: white; }
        .toolbar { display: none; }
        .page { margin-top: 20px; }
        table { box-shadow: none; }
        tr:hover td { background: transparent; }
        thead { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .stat { border: 1px solid #e5e7eb; }
    }
</style>
</head>
<body>

{{-- Toolbar --}}
<div class="toolbar">
    <div class="toolbar-left">
        <a href="{{ route('guests.index') }}" class="btn-back">← Back to Guests</a>
        <span style="font-size:13px; color:#6b7280;">
            {{ $wedding->bride_name }} & {{ $wedding->groom_name }} — Guest List
        </span>
    </div>
    <div style="display:flex; gap:8px; align-items:center;">
        <a href="{{ route('guests.export') }}"
           style="font-size:13px; font-weight:600; color:#9333ea; background:#f3e8ff; border:1px solid #e9d5ff; padding:8px 16px; border-radius:999px; text-decoration:none;">
            ⬇ Export CSV
        </a>
        <button onclick="window.print()" class="btn-print">🖨️ Print / Save as PDF</button>
    </div>
</div>

<div class="page">

    {{-- Header --}}
    <div class="header">
        <h1>💍 {{ $wedding->bride_name }} & {{ $wedding->groom_name }}</h1>
        <p>
            {{ $listLabel }}
            @if($wedding->wedding_date) · {{ $wedding->wedding_date->format('d F Y') }} @endif
            @if($wedding->venue) · {{ $wedding->venue }} @endif
        </p>
        <p style="margin-top:6px; font-size:11px; color:#d1d5db;">Printed {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    {{-- Stats --}}
    <div class="stats">
        <div class="stat">
            <div class="stat-value">{{ $stats['total_invitations'] }}</div>
            <div class="stat-label">Invitations</div>
        </div>
        <div class="stat">
            <div class="stat-value">{{ $stats['total_invited'] }}</div>
            <div class="stat-label">Total People</div>
        </div>
        <div class="stat">
            <div class="stat-value" style="color:#15803d;">{{ $stats['total_confirmed'] }}</div>
            <div class="stat-label">Confirmed</div>
        </div>
    </div>

    {{-- Guest Table grouped by side --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Side</th>
                <th>Type</th>
                <th style="text-align:center;">Invited</th>
                <th style="text-align:center;">Confirmed</th>
                <th>RSVP</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; $currentGroup = null; @endphp
            @foreach($guests as $guest)
                @if($guest->group?->name !== $currentGroup)
                    @php $currentGroup = $guest->group?->name; @endphp
                    <tr class="group-header">
                        <td colspan="8">
                            {{ $currentGroup ?? 'No Group' }}
                        </td>
                    </tr>
                @endif
                <tr>
                    <td style="color:#d1d5db; font-size:11px;">{{ $i++ }}</td>
                    <td>
                        <strong>{{ $guest->name }}</strong>
                        @if($guest->notes)
                        <br><span style="font-size:11px; color:#9ca3af;">{{ $guest->notes }}</span>
                        @endif
                    </td>
                    <td style="font-size:11px; color:#6b7280;">{{ $guest->group?->name ?? '—' }}</td>
                    <td style="font-size:11px; color:#6b7280; text-transform:capitalize;">{{ $guest->invitation_type }}</td>
                    <td style="text-align:center; font-weight:700;">{{ $guest->total_invited }}</td>
                    <td style="text-align:center; font-weight:700; color:#15803d;">
                        {{ $guest->rsvp_status->value === 'accepted' ? $guest->rsvp_confirmed : '—' }}
                    </td>
                    <td>
                        <span class="badge badge-{{ $guest->rsvp_status->value }}">
                            {{ ucfirst($guest->rsvp_status->value) }}
                        </span>
                    </td>
                    <td style="font-size:12px; color:#6b7280;">{{ $guest->phone ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#f9fafb;">
                <td colspan="4" style="font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase; padding:10px 14px;">Total</td>
                <td style="text-align:center; font-weight:900; font-size:14px;">{{ $stats['total_invited'] }}</td>
                <td style="text-align:center; font-weight:900; font-size:14px; color:#15803d;">{{ $stats['total_confirmed'] }}</td>
                <td colspan="2" style="font-size:11px; color:#9ca3af; padding:10px 14px;">
                    {{ $stats['accepted'] }} accepted · {{ $stats['declined'] }} declined · {{ $stats['pending'] }} pending
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Generated by WedPlan Lanka · {{ now()->format('d M Y') }}
    </div>
</div>

</body>
</html>
