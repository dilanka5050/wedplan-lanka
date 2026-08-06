@php $hasInvitation = isset($invitation) && $invitation && auth()->user()->isPro(); @endphp

{{-- Info banner --}}
<div class="mx-4 md:mx-6 my-4 flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
    <span class="text-base mt-0.5">💡</span>
    <p class="text-xs text-blue-700 leading-relaxed">
        <span class="font-semibold">When a guest accepts, set how many people are actually attending.</span>
        Change the RSVP status to <span class="font-semibold">Accepted</span> first — then a confirmation count field will appear.
    </p>
</div>

{{-- ===== DESKTOP TABLE (md+) ===== --}}
<div class="hidden md:block overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-t border-gray-100">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Name</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Group</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Type</th>
                <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Invited</th>
                <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Confirmed
                    <span class="block text-gray-400 font-normal normal-case tracking-normal" style="font-size:10px">only when Accepted</span>
                </th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">RSVP</th>
                @if($weddingPast)
                <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Gifted (Rs.)</th>
                @endif
                @if($hasInvitation)
                <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-center">Invitation</th>
                @endif
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($guests as $guest)
            @php
                $isAccepted  = $guest->rsvp_status->value === 'accepted';
                $guestPhone  = $guest->phone ?? '';
                $hasPhone    = !empty(trim($guestPhone));
                $inviteUrl   = route('invite.show', $guest->invite_token);
                $guestWaText = 'Hi ' . $guest->name . '! Please view your wedding invitation here: ' . $inviteUrl;
                $guestWaLink = 'https://wa.me/' . preg_replace('/\D/', '', $guestPhone) . '?text=' . urlencode($guestWaText);
            @endphp
            <tr class="hover:bg-gray-50/50 transition guest-row"
                x-show="!$store.guestSearch.q || '{{ strtolower($guest->name) }}'.includes($store.guestSearch.q.toLowerCase())">

                <td class="px-6 py-4">
                    <p class="font-medium text-gray-800">{{ $guest->name }}</p>
                    @if($guest->notes)
                        <p class="text-xs text-gray-400 mt-0.5 truncate max-w-[180px]">{{ $guest->notes }}</p>
                    @endif
                </td>

                <td class="px-6 py-4 text-gray-500 text-xs">{{ $guest->group?->name ?? '—' }}</td>

                <td class="px-6 py-4">
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full
                        {{ $guest->invitation_type === 'family' ? 'bg-purple-50 text-purple-700' :
                           ($guest->invitation_type === 'couple' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                        {{ $guest->invitation_label }}
                    </span>
                </td>

                <td class="px-4 py-4 text-center">
                    <div x-data="{ editing: false, val: {{ $guest->total_invited }} }">
                        <div x-show="!editing" class="flex items-center justify-center gap-1.5">
                            <span class="text-sm font-bold text-gray-700">{{ $guest->total_invited }}</span>
                            <button type="button" @click="editing = true"
                                    class="w-5 h-5 rounded flex items-center justify-center text-gray-300 hover:text-pink-500 hover:bg-pink-50 transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/></svg>
                            </button>
                        </div>
                        <form x-show="editing" x-cloak method="POST" action="{{ route('guests.update', $guest) }}"
                              class="flex items-center justify-center gap-1">
                            @csrf @method('PUT')
                            <input type="number" name="total_invited" x-model="val" min="1" max="20"
                                   class="w-12 text-center border border-pink-300 bg-pink-50 rounded-lg text-xs py-1.5 font-bold focus:outline-none focus:ring-2 focus:ring-pink-300">
                            <button type="submit" class="w-7 h-7 rounded-lg bg-green-500 hover:bg-green-600 text-white flex items-center justify-center transition shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </button>
                            <button type="button" @click="editing = false; val = {{ $guest->total_invited }}"
                                    class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>
                </td>

                <td class="px-4 py-4 text-center">
                    @if($isAccepted)
                        <div x-data="{ editing: false, val: {{ $guest->rsvp_confirmed }} }">
                            <div x-show="!editing" class="flex items-center justify-center gap-1.5">
                                <span class="text-sm font-black text-green-600">{{ $guest->rsvp_confirmed }}</span>
                                <span class="text-xs text-gray-300">/ {{ $guest->total_invited }}</span>
                                <button type="button" @click="editing = true"
                                        class="w-5 h-5 rounded flex items-center justify-center text-gray-300 hover:text-pink-500 hover:bg-pink-50 transition ml-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/></svg>
                                </button>
                            </div>
                            <form x-show="editing" x-cloak method="POST" action="{{ route('guests.update', $guest) }}"
                                  class="flex items-center justify-center gap-1">
                                @csrf @method('PUT')
                                <input type="number" name="rsvp_confirmed" x-model="val" min="0" max="{{ $guest->total_invited }}"
                                       class="w-12 text-center border border-pink-300 bg-pink-50 rounded-lg text-xs py-1.5 font-bold focus:outline-none focus:ring-2 focus:ring-pink-300">
                                <span class="text-xs text-gray-400">/ {{ $guest->total_invited }}</span>
                                <button type="submit" class="w-7 h-7 rounded-lg bg-green-500 hover:bg-green-600 text-white flex items-center justify-center transition shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </button>
                                <button type="button" @click="editing = false; val = {{ $guest->rsvp_confirmed }}"
                                        class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <span class="text-xs text-gray-300 italic">—</span>
                    @endif
                </td>

                <td class="px-6 py-4">
                    <form method="POST" action="{{ route('guests.update', $guest) }}">
                        @csrf @method('PUT')
                        @if($guest->invitation_type === 'single')
                        <input type="hidden" name="rsvp_confirmed" id="rsvp_confirmed_{{ $guest->id }}" value="0">
                        @endif
                        <select name="rsvp_status"
                                onchange="
                                    document.getElementById('loading-overlay').classList.remove('hidden');
                                    @if($guest->invitation_type === 'single')
                                    document.getElementById('rsvp_confirmed_{{ $guest->id }}').value = (this.value === 'accepted') ? 1 : 0;
                                    @endif
                                    this.form.submit()"
                                class="text-xs font-semibold border rounded-full px-3 py-1.5 outline-none cursor-pointer transition
                                {{ $isAccepted ? 'bg-green-50 text-green-700 border-green-200' :
                                   ($guest->rsvp_status->value === 'declined' ? 'bg-red-50 text-red-600 border-red-200' :
                                   ($guest->rsvp_status->value === 'maybe' ? 'bg-amber-50 text-amber-700 border-amber-200' :
                                   'bg-gray-50 text-gray-600 border-gray-200')) }}">
                            <option value="pending"  {{ $guest->rsvp_status->value === 'pending'  ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="accepted" {{ $guest->rsvp_status->value === 'accepted' ? 'selected' : '' }}>✅ Accepted</option>
                            <option value="declined" {{ $guest->rsvp_status->value === 'declined' ? 'selected' : '' }}>❌ Declined</option>
                            <option value="maybe"    {{ $guest->rsvp_status->value === 'maybe'    ? 'selected' : '' }}>🤔 Maybe</option>
                        </select>
                    </form>
                </td>

                @if($weddingPast)
                <td class="px-6 py-4 text-right">
                    <div x-data="{ editing: false, val: '{{ $guest->gifted_amount ?? '' }}' }">
                        <div x-show="!editing" class="flex items-center justify-end gap-1.5">
                            <span class="text-sm font-bold text-gray-700" x-text="val !== '' ? 'Rs. ' + Number(val).toLocaleString() : '—'"></span>
                            <button type="button" @click="editing = true"
                                    class="w-5 h-5 rounded flex items-center justify-center text-gray-300 hover:text-pink-500 hover:bg-pink-50 transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/></svg>
                            </button>
                        </div>
                        <form x-show="editing" x-cloak method="POST" action="{{ route('guests.update', $guest) }}"
                              class="flex items-center justify-end gap-1">
                            @csrf @method('PUT')
                            <input type="number" name="gifted_amount" x-model="val" min="0" step="0.01" placeholder="0.00"
                                   class="w-24 text-right border border-pink-300 bg-pink-50 rounded-lg text-xs py-1.5 px-2 font-bold focus:outline-none focus:ring-2 focus:ring-pink-300">
                            <button type="submit" class="w-7 h-7 rounded-lg bg-green-500 hover:bg-green-600 text-white flex items-center justify-center transition shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </button>
                            <button type="button" @click="editing = false; val = '{{ $guest->gifted_amount ?? '' }}'"
                                    class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
                @endif

                @if($hasInvitation)
                <td class="px-4 py-4 text-center">
                    <div class="flex items-center justify-center gap-1.5">
                        <a href="{{ route('invitations.guest', $guest) }}"
                           class="inline-flex items-center gap-1 px-2 py-1.5 text-xs font-semibold text-pink-600 bg-pink-50 border border-pink-200 rounded-full hover:bg-pink-100 transition"
                           title="Print card">
                            💌 <span class="hidden lg:inline">Print</span>
                        </a>
                        @if($hasPhone)
                        <a href="{{ $guestWaLink }}" target="_blank"
                           data-invite-url="{{ $inviteUrl }}"
                           data-wa-number="{{ preg_replace('/\D/', '', $guestPhone) }}"
                           data-wa-name="{{ addslashes($guest->name) }}"
                           class="send-link inline-flex items-center gap-1 px-2 py-1.5 text-xs font-semibold text-green-600 bg-green-50 border border-green-200 rounded-full hover:bg-green-100 transition"
                           title="Send via WhatsApp">
                            <svg class="w-3 h-3 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            <span class="hidden lg:inline">Send</span>
                        </a>
                        @else
                        <span class="inline-flex items-center px-2 py-1.5 text-xs text-gray-300 bg-gray-50 border border-gray-100 rounded-full cursor-not-allowed"
                              title="Add phone number to enable">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </span>
                        @endif
                    </div>
                </td>
                @endif

                <td class="px-4 py-4 text-right">
                    <form method="POST" action="{{ route('guests.destroy', $guest) }}">
                        @csrf @method('DELETE')
                        <button class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-300 hover:text-red-400 hover:bg-red-50 transition">✕</button>
                    </form>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="{{ ($hasInvitation ? 8 : 7) + ($weddingPast ? 1 : 0) }}" class="px-6 py-12 text-center">
                    <p class="text-3xl mb-2">👥</p>
                    <p class="text-sm font-medium text-gray-500">{{ $emptyMessage }}</p>
                </td>
            </tr>
            @endforelse
        </tbody>

        @if($guests->count() > 0)
        <tfoot class="bg-gray-50 border-t-2 border-gray-200">
            <tr>
                <td colspan="3" class="px-6 py-3 text-xs font-bold text-gray-600 uppercase tracking-wide">Totals</td>
                <td class="px-4 py-3 text-center text-sm font-black text-gray-800">{{ $guests->sum('total_invited') }}</td>
                <td class="px-4 py-3 text-center text-sm font-black text-green-600">{{ $guests->sum('rsvp_confirmed') }}</td>
                <td colspan="{{ ($hasInvitation ? 3 : 2) + ($weddingPast ? 1 : 0) }}" class="px-6 py-3 text-xs text-gray-400">
                    {{ $guests->sum('total_invited') - $guests->sum('rsvp_confirmed') }} yet to confirm
                </td>
                @if($weddingPast)
                <td class="px-6 py-3 text-right text-sm font-black text-amber-600">
                    Rs. {{ number_format($guests->sum('gifted_amount'), 2) }}
                </td>
                @endif
            </tr>
        </tfoot>
        @endif
    </table>
</div>

{{-- ===== MOBILE CARDS (below md) ===== --}}
<div class="md:hidden divide-y divide-gray-100">
    @forelse($guests as $guest)
    @php
        $isAccepted  = $guest->rsvp_status->value === 'accepted';
        $guestPhone  = $guest->phone ?? '';
        $hasPhone    = !empty(trim($guestPhone));
        $inviteUrl   = route('invite.show', $guest->invite_token);
        $guestWaText = 'Hi ' . $guest->name . '! Please view your wedding invitation here: ' . $inviteUrl;
        $guestWaLink = 'https://wa.me/' . preg_replace('/\D/', '', $guestPhone) . '?text=' . urlencode($guestWaText);
    @endphp
    <div class="px-4 py-4 guest-row"
         x-show="!$store.guestSearch.q || '{{ strtolower($guest->name) }}'.includes($store.guestSearch.q.toLowerCase())">

        {{-- Name + type + delete --}}
        <div class="flex items-start justify-between gap-2 mb-3">
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800 truncate">{{ $guest->name }}</p>
                @if($guest->notes)
                    <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $guest->notes }}</p>
                @endif
                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full
                        {{ $guest->invitation_type === 'family' ? 'bg-purple-50 text-purple-700' :
                           ($guest->invitation_type === 'couple' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                        {{ $guest->invitation_label }}
                    </span>
                    @if($guest->group)
                    <span class="text-xs text-gray-400">{{ $guest->group->name }}</span>
                    @endif
                </div>
            </div>
            <form method="POST" action="{{ route('guests.destroy', $guest) }}" class="flex-shrink-0">
                @csrf @method('DELETE')
                <button class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-300 hover:text-red-400 hover:bg-red-50 transition">✕</button>
            </form>
        </div>

        {{-- Invited + Confirmed counts --}}
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 mb-1">Invited</p>
                <div x-data="{ editing: false, val: {{ $guest->total_invited }} }">
                    <div x-show="!editing" class="flex items-center gap-1.5">
                        <span class="text-base font-black text-gray-800">{{ $guest->total_invited }}</span>
                        <button type="button" @click="editing = true"
                                class="w-5 h-5 rounded flex items-center justify-center text-gray-300 hover:text-pink-500 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/></svg>
                        </button>
                    </div>
                    <form x-show="editing" x-cloak method="POST" action="{{ route('guests.update', $guest) }}"
                          class="flex items-center gap-1">
                        @csrf @method('PUT')
                        <input type="number" name="total_invited" x-model="val" min="1" max="20"
                               class="w-12 text-center border border-pink-300 bg-pink-50 rounded-lg text-xs py-1 font-bold focus:outline-none">
                        <button type="submit" class="w-6 h-6 rounded bg-green-500 text-white flex items-center justify-center">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </button>
                        <button type="button" @click="editing = false" class="w-6 h-6 rounded bg-gray-100 text-gray-500 flex items-center justify-center">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 mb-1">Confirmed</p>
                @if($isAccepted)
                <div x-data="{ editing: false, val: {{ $guest->rsvp_confirmed }} }">
                    <div x-show="!editing" class="flex items-center gap-1.5">
                        <span class="text-base font-black text-green-600">{{ $guest->rsvp_confirmed }}</span>
                        <span class="text-xs text-gray-300">/ {{ $guest->total_invited }}</span>
                        <button type="button" @click="editing = true"
                                class="w-5 h-5 rounded flex items-center justify-center text-gray-300 hover:text-pink-500 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/></svg>
                        </button>
                    </div>
                    <form x-show="editing" x-cloak method="POST" action="{{ route('guests.update', $guest) }}"
                          class="flex items-center gap-1">
                        @csrf @method('PUT')
                        <input type="number" name="rsvp_confirmed" x-model="val" min="0" max="{{ $guest->total_invited }}"
                               class="w-12 text-center border border-pink-300 bg-pink-50 rounded-lg text-xs py-1 font-bold focus:outline-none">
                        <button type="submit" class="w-6 h-6 rounded bg-green-500 text-white flex items-center justify-center">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </button>
                        <button type="button" @click="editing = false" class="w-6 h-6 rounded bg-gray-100 text-gray-500 flex items-center justify-center">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </form>
                </div>
                @else
                <span class="text-xs text-gray-300 italic">Set RSVP first</span>
                @endif
            </div>
        </div>

        {{-- RSVP --}}
        <div class="mb-3">
            <form method="POST" action="{{ route('guests.update', $guest) }}">
                @csrf @method('PUT')
                @if($guest->invitation_type === 'single')
                <input type="hidden" name="rsvp_confirmed" id="rsvp_confirmed_m_{{ $guest->id }}" value="0">
                @endif
                <select name="rsvp_status"
                        onchange="
                            document.getElementById('loading-overlay').classList.remove('hidden');
                            @if($guest->invitation_type === 'single')
                            document.getElementById('rsvp_confirmed_m_{{ $guest->id }}').value = (this.value === 'accepted') ? 1 : 0;
                            @endif
                            this.form.submit()"
                        class="w-full text-sm font-semibold border rounded-xl px-3 py-2 outline-none cursor-pointer transition
                        {{ $isAccepted ? 'bg-green-50 text-green-700 border-green-200' :
                           ($guest->rsvp_status->value === 'declined' ? 'bg-red-50 text-red-600 border-red-200' :
                           ($guest->rsvp_status->value === 'maybe' ? 'bg-amber-50 text-amber-700 border-amber-200' :
                           'bg-gray-50 text-gray-600 border-gray-200')) }}">
                    <option value="pending"  {{ $guest->rsvp_status->value === 'pending'  ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="accepted" {{ $guest->rsvp_status->value === 'accepted' ? 'selected' : '' }}>✅ Accepted</option>
                    <option value="declined" {{ $guest->rsvp_status->value === 'declined' ? 'selected' : '' }}>❌ Declined</option>
                    <option value="maybe"    {{ $guest->rsvp_status->value === 'maybe'    ? 'selected' : '' }}>🤔 Maybe</option>
                </select>
            </form>
        </div>

        {{-- Gifted amount (post-wedding) --}}
        @if($weddingPast)
        <div class="mb-3">
            <p class="text-xs text-gray-400 mb-1">Gifted Amount</p>
            <div x-data="{ editing: false, val: '{{ $guest->gifted_amount ?? '' }}' }">
                <div x-show="!editing" class="flex items-center gap-2">
                    <span class="text-sm font-bold text-amber-600" x-text="val !== '' ? 'Rs. ' + Number(val).toLocaleString() : '—'"></span>
                    <button type="button" @click="editing = true"
                            class="w-5 h-5 rounded flex items-center justify-center text-gray-300 hover:text-pink-500 transition">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/></svg>
                    </button>
                </div>
                <form x-show="editing" x-cloak method="POST" action="{{ route('guests.update', $guest) }}"
                      class="flex items-center gap-2">
                    @csrf @method('PUT')
                    <input type="number" name="gifted_amount" x-model="val" min="0" step="0.01" placeholder="0.00"
                           class="flex-1 border border-pink-300 bg-pink-50 rounded-lg text-sm py-1.5 px-3 font-bold focus:outline-none">
                    <button type="submit" class="w-8 h-8 rounded-lg bg-green-500 text-white flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </button>
                    <button type="button" @click="editing = false" class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Invitation actions --}}
        @if($hasInvitation)
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('invitations.guest', $guest) }}"
               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-pink-600 bg-pink-50 border border-pink-200 rounded-full hover:bg-pink-100 transition">
                💌 Print Card
            </a>
            @if($hasPhone)
            <a href="{{ $guestWaLink }}" target="_blank"
               data-invite-url="{{ $inviteUrl }}"
               data-wa-number="{{ preg_replace('/\D/', '', $guestPhone) }}"
               data-wa-name="{{ addslashes($guest->name) }}"
               class="send-link inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-green-600 bg-green-50 border border-green-200 rounded-full hover:bg-green-100 transition">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Send via WhatsApp
            </a>
            @endif
        </div>
        @endif

    </div>
    @empty
    <div class="px-4 py-12 text-center">
        <p class="text-3xl mb-2">👥</p>
        <p class="text-sm font-medium text-gray-500">{{ $emptyMessage }}</p>
    </div>
    @endforelse

    {{-- Mobile totals --}}
    @if($guests->count() > 0)
    <div class="px-4 py-3 bg-gray-50 border-t-2 border-gray-200 flex items-center justify-between flex-wrap gap-2">
        <span class="text-xs font-bold text-gray-600 uppercase tracking-wide">Totals</span>
        <div class="flex items-center gap-4">
            <span class="text-xs text-gray-500">Invited: <span class="font-black text-gray-800">{{ $guests->sum('total_invited') }}</span></span>
            <span class="text-xs text-gray-500">Confirmed: <span class="font-black text-green-600">{{ $guests->sum('rsvp_confirmed') }}</span></span>
            @if($weddingPast)
            <span class="text-xs text-gray-500">Gifted: <span class="font-black text-amber-600">Rs. {{ number_format($guests->sum('gifted_amount'), 2) }}</span></span>
            @endif
        </div>
    </div>
    @endif
</div>

<script>
(function(){
    const tpl = localStorage.getItem('digitalTemplate') || 'digital1';
    document.querySelectorAll('.send-link[data-invite-url]').forEach(el => {
        const url  = el.dataset.inviteUrl + '?template=' + tpl;
        const text = 'Hi ' + el.dataset.waName + '! Please view your wedding invitation here: ' + url;
        el.href = 'https://wa.me/' + el.dataset.waNumber + '?text=' + encodeURIComponent(text);
    });
})();
</script>
