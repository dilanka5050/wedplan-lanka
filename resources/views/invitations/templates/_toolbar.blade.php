{{-- Shared toolbar for all invitation templates --}}
{{-- Variables expected: $isGuest, $guest (if isGuest), $invitation, $jpegColor, $jpegBorder, $cardSelector --}}
<div class="toolbar">
    @if($isGuest)
        <a href="{{ route('guests.index') }}">← Back to Guests</a>
        <span class="guest-badge">💌 {{ $guest->name }}</span>
    @else
        <a href="{{ route('invitations.index') }}">← Edit Details</a>
    @endif
    <div style="display:flex;gap:8px;align-items:center;">
        <button onclick="downloadJpeg()"
                style="font-family:sans-serif;font-size:13px;font-weight:600;color:{{ $jpegColor }};background:white;border:2px solid {{ $jpegBorder }};padding:8px 20px;border-radius:999px;cursor:pointer;"
                id="jpegBtn">
            🖼️ Save as JPEG
        </button>
        <button onclick="window.print()">🖨️ Print / Save as PDF</button>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
function downloadJpeg() {
    var btn = document.getElementById('jpegBtn');
    var original = btn.innerText;
    btn.innerText = '⏳ Generating...';
    btn.disabled = true;
    html2canvas(document.querySelector('{{ $cardSelector }}'), {
        scale: 2,
        useCORS: true,
        backgroundColor: '#ffffff',
        logging: false
    }).then(function(canvas) {
        var link = document.createElement('a');
        link.download = 'wedding-invitation-{{ $invitation->bride_name }}-{{ $invitation->groom_name }}.jpg';
        link.href = canvas.toDataURL('image/jpeg', 0.95);
        link.click();
        btn.innerText = original;
        btn.disabled = false;
    }).catch(function() {
        btn.innerText = original;
        btn.disabled = false;
        alert('Could not generate image. Please try Print / Save as PDF instead.');
    });
}
</script>
