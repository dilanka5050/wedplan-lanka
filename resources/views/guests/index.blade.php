@extends('layouts.app')
@section('title', 'Guests')
@section('page-title', 'Guest Management')

@section('content')

{{-- Stats Row --}}
<div class="grid grid-cols-2 {{ $hasSeparateLists ? 'lg:grid-cols-6' : 'lg:grid-cols-4' }} gap-4 mb-6">
    {{-- Common cards (all users) --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-5 card-shadow">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-lg mb-3">💌</div>
        <p class="text-2xl font-black text-gray-900">{{ $stats['total_invitations'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Invitations Sent</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 card-shadow">
        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-lg mb-3">👥</div>
        <p class="text-2xl font-black text-gray-900">{{ $stats['total_invited'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Total People Invited</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 card-shadow">
        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-lg mb-3">✅</div>
        <p class="text-2xl font-black text-green-600">{{ $stats['total_confirmed'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Confirmed Attending</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 card-shadow">
        <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-lg mb-3">⏳</div>
        <p class="text-2xl font-black text-amber-600">{{ $stats['pending'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Awaiting Response</p>
    </div>

    {{-- Pro-only: Bride & Groom breakdown cards --}}
    @if($hasSeparateLists)
    <div class="bg-white rounded-2xl border border-pink-100 p-5 card-shadow relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-pink-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="w-10 h-10 bg-pink-50 rounded-xl flex items-center justify-center text-lg mb-3">👰</div>
        <p class="text-2xl font-black text-pink-600">{{ $stats['bride_count'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Bride's Side</p>
        <p class="text-xs text-pink-400 mt-1 font-medium">{{ $brideGuests->count() }} invitations</p>
    </div>
    <div class="bg-white rounded-2xl border border-blue-100 p-5 card-shadow relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-lg mb-3">🤵</div>
        <p class="text-2xl font-black text-blue-600">{{ $stats['groom_count'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Groom's Side</p>
        <p class="text-xs text-blue-400 mt-1 font-medium">{{ $groomGuests->count() }} invitations</p>
    </div>
    @else
    {{-- Locked placeholder cards for free users --}}
    <div class="bg-gray-50 rounded-2xl border border-dashed border-gray-200 p-5 flex flex-col items-center justify-center text-center opacity-60">
        <span class="text-2xl mb-2">👰</span>
        <p class="text-xs font-semibold text-gray-400">Bride's Side</p>
        <span class="mt-2 text-xs bg-amber-100 text-amber-600 font-bold px-2 py-0.5 rounded-full">PRO</span>
    </div>
    <div class="bg-gray-50 rounded-2xl border border-dashed border-gray-200 p-5 flex flex-col items-center justify-center text-center opacity-60">
        <span class="text-2xl mb-2">🤵</span>
        <p class="text-xs font-semibold text-gray-400">Groom's Side</p>
        <span class="mt-2 text-xs bg-amber-100 text-amber-600 font-bold px-2 py-0.5 rounded-full">PRO</span>
    </div>
    @endif
</div>

{{-- Main Card with Tabs --}}
<div class="bg-white rounded-2xl border border-gray-100 card-shadow"
     x-data="{
        tab: 'all', addOpen: false, importOpen: false, type: 'single', total: 1, search: '',
     }">

    {{-- Guest quota bar (free users only) --}}
    @if(!auth()->user()->isPro())
    <div class="px-4 md:px-6 pt-4">
        <div class="bg-gray-50 rounded-2xl border border-gray-100 px-4 py-3">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-semibold text-gray-600">Guest Quota</span>
                <span class="text-xs font-bold {{ $guestPct >= 100 ? 'text-red-500' : 'text-gray-700' }}">
                    {{ $guestCount }} / {{ $guestLimit }} invitations
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="h-2 rounded-full transition-all duration-500
                    {{ $guestPct >= 100 ? 'bg-red-400' : ($guestPct >= 80 ? 'bg-amber-400' : 'bg-gradient-to-r from-pink-500 to-purple-500') }}"
                    style="width: {{ $guestPct }}%"></div>
            </div>
            @if($guestPct >= 100)
            <p class="text-xs text-red-500 font-semibold mt-1.5">Limit reached · <button @click="upgradeOpen = true" class="underline">Upgrade to Pro</button> for unlimited guests</p>
            @elseif($guestPct >= 80)
            <p class="text-xs text-amber-500 font-medium mt-1">{{ $guestLimit - $guestCount }} slots remaining · running low</p>
            @else
            <p class="text-xs text-gray-400 mt-1">{{ $guestLimit - $guestCount }} slots remaining</p>
            @endif
        </div>
    </div>
    @endif
    <div class="flex flex-col px-4 md:px-6 pt-4 pb-0 gap-3">

        {{-- Tabs --}}
        <div class="flex items-center gap-1 bg-gray-100 rounded-xl p-1 overflow-x-auto scrollbar-hide w-full sm:w-fit">
            <button @click="tab = 'all'"
                    :class="tab === 'all' ? 'bg-white shadow text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-1.5 rounded-lg text-sm transition">
                All <span class="ml-1 text-xs text-gray-400">({{ $stats['total_invitations'] }})</span>
            </button>

            @if($hasSeparateLists)
            <button @click="tab = 'bride'"
                    :class="tab === 'bride' ? 'bg-white shadow text-pink-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-1.5 rounded-lg text-sm transition">
                👰 Bride's List <span class="ml-1 text-xs text-gray-400">({{ $brideGuests->count() }})</span>
            </button>
            <button @click="tab = 'groom'"
                    :class="tab === 'groom' ? 'bg-white shadow text-blue-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-1.5 rounded-lg text-sm transition">
                🤵 Groom's List <span class="ml-1 text-xs text-gray-400">({{ $groomGuests->count() }})</span>
            </button>
            @else
            {{-- Locked tabs for free users --}}
            <div class="relative group">
                <button disabled class="px-4 py-1.5 rounded-lg text-sm text-gray-300 cursor-not-allowed flex items-center gap-1">
                    👰 Bride's List <span class="text-xs bg-amber-100 text-amber-600 font-bold px-1.5 py-0.5 rounded-full ml-1">PRO</span>
                </button>
            </div>
            <div class="relative group">
                <button disabled class="px-4 py-1.5 rounded-lg text-sm text-gray-300 cursor-not-allowed flex items-center gap-1">
                    🤵 Groom's List <span class="text-xs bg-amber-100 text-amber-600 font-bold px-1.5 py-0.5 rounded-full ml-1">PRO</span>
                </button>
            </div>
            @endif
        </div>

        {{-- Search + Action Buttons --}}
        <div class="flex flex-wrap items-center gap-2">
            <div class="relative flex-1 min-w-[140px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
                <input type="text" x-model="search" @input="Alpine.store('guestSearch').q = search" placeholder="Search guests..."
                       class="w-full pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-full focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
            </div>
            @if(auth()->user()->isPro())
            <a href="{{ route('guests.export') }}"
               class="flex items-center gap-1.5 px-3 py-2 bg-white border-2 border-purple-200 text-purple-600 text-xs font-semibold rounded-full hover:bg-purple-50 transition whitespace-nowrap">
                ⬇ <span class="hidden sm:inline">Export CSV</span><span class="sm:hidden">Export</span>
            </a>
            <button @click="importOpen = !importOpen; addOpen = false"
                    class="flex items-center gap-1.5 px-3 py-2 bg-white border-2 border-purple-200 text-purple-600 text-xs font-semibold rounded-full hover:bg-purple-50 transition whitespace-nowrap">
                <span x-text="importOpen ? '✕ Cancel' : '📥 Import'"></span>
            </button>
            @else
            <span class="flex items-center gap-1.5 px-3 py-2 bg-gray-50 border border-dashed border-gray-200 text-gray-300 text-xs font-semibold rounded-full cursor-not-allowed">
                ⬇ Export <span class="bg-amber-100 text-amber-600 font-bold px-1.5 py-0.5 rounded-full ml-1">PRO</span>
            </span>
            @endif
            <button @click="addOpen = !addOpen; importOpen = false"
                    class="flex items-center gap-1.5 px-3 py-2 bg-gradient-to-r from-pink-600 to-purple-600 text-white text-xs font-semibold rounded-full shadow-md hover:shadow-lg transition whitespace-nowrap">
                <span x-text="addOpen ? '✕ Cancel' : '+ Add Guest'"></span>
            </button>
        </div>
    </div>

    {{-- Pro upgrade notice for free users --}}
    @if(!$hasSeparateLists)
    <div class="mx-6 mt-4 flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl">
        <span class="text-xl">⭐</span>
        <div class="flex-1">
            <p class="text-sm font-semibold text-amber-800">Separate Bride & Groom lists are a Pro feature</p>
            <p class="text-xs text-amber-600 mt-0.5">Upgrade to Pro to manage bride-side and groom-side guest lists separately.</p>
        </div>
        <a href="#" @click.prevent="upgradeOpen = true" class="text-xs font-bold text-white bg-gradient-to-r from-pink-600 to-purple-600 px-4 py-2 rounded-full shadow hover:shadow-md transition whitespace-nowrap">
            Upgrade to Pro
        </a>
    </div>
    @endif

    {{-- Import CSV Panel (Pro only) --}}
    @if(auth()->user()->isPro())
    <div x-show="importOpen" x-cloak class="border-t border-gray-100 mt-4 px-6 py-5 bg-purple-50/30">
        <div class="max-w-lg">
            <h4 class="font-bold text-gray-900 mb-1">Bulk Import Guests via CSV</h4>
            <p class="text-xs text-gray-500 mb-4">Download the template, fill in your guests, then upload it here.</p>

            {{-- Step 1: Download template --}}
            <div class="flex items-center gap-3 p-4 bg-white rounded-xl border border-gray-200 mb-4">
                <span class="text-2xl">📄</span>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-700">Step 1 — Download Template</p>
                    <p class="text-xs text-gray-400 mt-0.5">CSV with correct column headers and example rows</p>
                </div>
                <a href="{{ route('guests.import.template') }}"
                   class="flex-shrink-0 text-xs font-bold text-purple-600 bg-purple-50 border border-purple-200 px-4 py-2 rounded-full hover:bg-purple-100 transition">
                    ⬇ Download
                </a>
            </div>

            {{-- Step 2: Upload --}}
            <div class="p-4 bg-white rounded-xl border border-gray-200">
                <p class="text-sm font-semibold text-gray-700 mb-3">Step 2 — Upload Filled CSV</p>
                <form method="POST" action="{{ route('guests.import.preview') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="flex items-center gap-3">
                        <input type="file" name="csv_file" accept=".csv,.txt" required
                               class="flex-1 text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 cursor-pointer">
                        <button type="submit"
                                class="flex-shrink-0 px-5 py-2 text-xs font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow hover:shadow-md transition">
                            Validate & Preview →
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Max file size: 2MB · Only .csv files accepted</p>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Add Guest Form --}}
    <div x-show="addOpen" x-cloak class="border-t border-gray-100 mt-4 px-6 py-5">
        <form method="POST" action="{{ route('guests.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Invitation Name <span class="text-red-400">*</span></label>
                    <input type="text" name="name" placeholder="e.g. Perera Family, Mr. & Mrs. Silva" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                    <p class="text-xs text-gray-400 mt-1">Use a family/couple name for group invitations</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Guest Side <span class="text-red-400">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($groups->whereIn('slug', ['bride-side', 'groom-side']) as $group)
                        <label class="cursor-pointer">
                            <input type="radio" name="guest_group_id" value="{{ $group->id }}" class="sr-only peer" required>
                            <div class="flex items-center gap-2 p-3 border-2 rounded-xl transition
                                        peer-checked:border-pink-500 peer-checked:bg-pink-50 border-gray-200 hover:border-pink-300">
                                <span class="text-xl">{{ $group->slug === 'bride-side' ? '👰' : '🤵' }}</span>
                                <span class="text-sm font-semibold text-gray-700">{{ $group->name }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Invitation Type <span class="text-red-400">*</span></label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach([['single','👤','Single','1'],['couple','👫','Couple','2'],['family','👨‍👩‍👧‍👦','Family','4']] as [$val,$icon,$label,$default])
                        <label class="cursor-pointer">
                            <input type="radio" name="invitation_type" value="{{ $val }}" class="sr-only peer"
                                   x-model="type" @change="total = {{ $default }}"
                                   {{ $val === 'single' ? 'checked' : '' }}>
                            <div class="flex flex-col items-center gap-1 p-3 border-2 rounded-xl text-center transition
                                        peer-checked:border-pink-500 peer-checked:bg-pink-50 border-gray-200 hover:border-pink-300">
                                <span class="text-xl">{{ $icon }}</span>
                                <span class="text-xs font-semibold text-gray-700">{{ $label }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Total People <span class="text-red-400">*</span></label>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="total = Math.max(1, total - 1)"
                                class="w-9 h-9 rounded-xl border-2 border-gray-200 flex items-center justify-center text-gray-600 hover:border-pink-400 hover:text-pink-600 font-bold text-lg transition">−</button>
                        <input type="number" name="total_invited" :value="total" x-model="total"
                               min="1" max="20" required
                               class="w-16 text-center px-2 py-2 border border-gray-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-pink-200 outline-none">
                        <button type="button" @click="total = Math.min(20, total + 1)"
                                class="w-9 h-9 rounded-xl border-2 border-gray-200 flex items-center justify-center text-gray-600 hover:border-pink-400 hover:text-pink-600 font-bold text-lg transition">+</button>
                        <span class="text-xs text-gray-400">people</span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Phone</label>
                    <x-phone-input name="phone" :value="old('phone')" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email</label>
                    <input type="email" name="email" placeholder="optional"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Notes</label>
                    <input type="text" name="notes" placeholder="e.g. Vegetarian, needs wheelchair access..."
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="addOpen = false"
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 border border-gray-200 rounded-full hover:bg-gray-50 transition">Cancel</button>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow-md hover:shadow-lg transition">
                    Add Guest
                </button>
            </div>
        </form>
    </div>

    {{-- Tab Panels --}}
    <div class="mt-2">
        {{-- ALL GUESTS --}}
        <div x-show="tab === 'all'">
            @include('guests._table', ['guests' => $allGuests, 'emptyMessage' => 'No guests added yet.', 'invitation' => $wedding->invitation, 'weddingPast' => $weddingPast])
        </div>

        {{-- BRIDE'S LIST --}}
        @if($hasSeparateLists)
        <div x-show="tab === 'bride'" x-cloak>
            <div class="px-6 py-3 bg-pink-50 border-t border-pink-100 flex items-center gap-2">
                <span class="text-lg">👰</span>
                <p class="text-sm font-semibold text-pink-700">Bride's Guest List</p>
                <span class="ml-auto text-xs text-pink-500">{{ $stats['bride_count'] }} people · Guests assigned to "Bride Side" group</span>
            </div>
            @include('guests._table', ['guests' => $brideGuests, 'emptyMessage' => 'No guests in Bride\'s list yet. Add guests and assign them to the "Bride Side" group.', 'invitation' => $wedding->invitation, 'weddingPast' => $weddingPast])
        </div>

        {{-- GROOM'S LIST --}}
        <div x-show="tab === 'groom'" x-cloak>
            <div class="px-6 py-3 bg-blue-50 border-t border-blue-100 flex items-center gap-2">
                <span class="text-lg">🤵</span>
                <p class="text-sm font-semibold text-blue-700">Groom's Guest List</p>
                <span class="ml-auto text-xs text-blue-500">{{ $stats['groom_count'] }} people · Guests assigned to "Groom Side" group</span>
            </div>
            @include('guests._table', ['guests' => $groomGuests, 'emptyMessage' => 'No guests in Groom\'s list yet. Add guests and assign them to the "Groom Side" group.', 'invitation' => $wedding->invitation, 'weddingPast' => $weddingPast])
        </div>
        @endif
    </div>

</div>

@endsection
