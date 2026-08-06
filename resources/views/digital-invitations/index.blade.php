@extends('layouts.app')
@section('title', 'Digital Invitation')
@section('page-title', 'Digital Invitation')

@section('content')

@if(!$invitation)
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6 flex items-center gap-4">
    <span class="text-2xl">⚠️</span>
    <div class="flex-1">
        <p class="text-sm font-bold text-amber-800">Invitation details required</p>
        <p class="text-xs text-amber-600 mt-0.5">Please fill in your wedding details in the Invitation section first. The digital templates use the same bride/groom names, date, venue, and timing.</p>
    </div>
    <a href="{{ route('invitations.index') }}"
       class="text-xs font-bold text-white bg-gradient-to-r from-pink-600 to-purple-600 px-4 py-2 rounded-full shadow whitespace-nowrap">
        Go to Invitation →
    </a>
</div>
@endif

<p class="text-sm text-gray-500 mb-6">Choose a digital animated template below. The selected template will be used when you click <span class="font-semibold text-purple-600">✨ Digital</span> on a guest in the Guest List.</p>

{{-- Template selector grid --}}
<div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8" x-data="digitalTemplates()" x-init="init()">
    @foreach($templates as $key => $tpl)
    <div class="relative bg-white rounded-2xl border-2 card-shadow overflow-hidden cursor-pointer transition-all"
         :class="selected === '{{ $key }}' ? 'border-purple-500 shadow-lg shadow-purple-100' : 'border-gray-100 hover:border-purple-200'"
         @click="select('{{ $key }}')">

        {{-- Selected badge --}}
        <div x-show="selected === '{{ $key }}'"
             class="absolute top-2 right-2 z-10 bg-purple-600 text-white text-xs font-bold px-2.5 py-1 rounded-full">
            ✓ Selected
        </div>

        {{-- Preview thumbnail --}}
        <div class="relative h-44 overflow-hidden bg-gray-900">
            @if($invitation)
            <iframe src="{{ route('digital-invitations.preview', $key) }}"
                    class="absolute top-0 left-0 pointer-events-none"
                    style="width:600px; height:900px; transform: scale({{ 176/900 }}); transform-origin: top left; border:none;"
                    scrolling="no"></iframe>
            @else
            <div class="flex flex-col items-center justify-center h-full gap-2 text-center px-4">
                <span class="text-3xl">✨</span>
                <p class="text-xs text-gray-400">Fill invitation details to preview</p>
            </div>
            @endif
        </div>

        <div class="p-4">
            <h3 class="font-bold text-gray-900 text-sm">{{ $tpl['label'] }}</h3>
            <p class="text-xs text-gray-500 mt-0.5 mb-3">{{ $tpl['desc'] }}</p>
            <div class="flex gap-2">
                @if($invitation)
                <a href="{{ route('digital-invitations.preview', $key) }}" target="_blank"
                   @click.stop
                   class="flex-1 text-center py-1.5 text-xs font-semibold text-purple-600 bg-purple-50 border border-purple-200 rounded-full hover:bg-purple-100 transition">
                    👁 Preview
                </a>
                @if(isset($customizableTemplates[$key]))
                <a href="{{ route('digital-invitations.customize') }}?template={{ $key }}"
                   @click.stop
                   class="flex-1 text-center py-1.5 text-xs font-semibold text-pink-600 bg-pink-50 border border-pink-200 rounded-full hover:bg-pink-100 transition">
                    ✏️ Customize
                </a>
                @endif
                @else
                <span class="flex-1 text-center py-1.5 text-xs font-semibold text-gray-300 bg-gray-50 border border-gray-100 rounded-full cursor-not-allowed">
                    👁 Preview
                </span>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Active template info bar --}}
<div class="mb-6 flex items-center gap-3 px-4 py-3 bg-purple-50 border border-purple-100 rounded-xl">
    <span class="text-lg">✨</span>
    <p class="text-sm text-purple-700">
        Active template: <strong id="active-tpl-label">—</strong>
        &nbsp;&middot;&nbsp;
        <span class="text-xs text-purple-500">This template will be used when sending digital invitations to guests from the Guest List.</span>
    </p>
</div>

<div class="mt-2 bg-purple-50 border border-purple-100 rounded-2xl p-5 flex items-start gap-4">
    <span class="text-2xl mt-0.5">💡</span>
    <div>
        <p class="text-sm font-bold text-purple-800">How to send to guests</p>
        <p class="text-xs text-purple-600 mt-1 leading-relaxed">
            Select a template above, then go to the <a href="{{ route('guests.index') }}" class="font-semibold underline">Guest List</a> and click <span class="font-semibold">✨ Digital</span> to open the invitation for that guest, or click <span class="font-semibold">Send</span> to share it directly via WhatsApp.
        </p>
    </div>
</div>

<script>
function digitalTemplates() {
    return {
        selected: '{{ $invitation?->digital_template ?? 'digital6' }}',
        init() {
            this.updateLabel();
            this.updateGuestLinks();
        },
        select(key) {
            this.selected = key;
            localStorage.setItem('digitalTemplate', key);
            this.updateLabel();
            this.updateGuestLinks();
            @if($invitation)
            fetch('{{ route('digital-invitations.set-template') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ template: key })
            }).catch(() => {});
            @endif
        },
        updateLabel() {
            const labels = @json(collect($templates)->map(fn($t) => $t['label']));
            const el = document.getElementById('active-tpl-label');
            if (el) el.textContent = labels[this.selected] || this.selected;
        },
        updateGuestLinks() {
            // Update all Digital + Send links in the guest table if navigated back
            document.querySelectorAll('[data-digital-link]').forEach(el => {
                const base = el.dataset.digitalLink;
                el.href = base + '?template=' + this.selected;
            });
            document.querySelectorAll('[data-digital-wa]').forEach(el => {
                const base = el.dataset.digitalWa;
                const url  = base + '?template=' + this.selected;
                const text = el.dataset.waText.replace('__URL__', url);
                el.href = 'https://wa.me/' + el.dataset.waNumber + '?text=' + encodeURIComponent(text);
            });
        }
    };
}
</script>

@endsection
