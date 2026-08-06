@extends('layouts.app')
@section('title', 'Seating Plan')
@section('page-title', 'Seating Plan')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-5 card-shadow">
        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-lg mb-3">🪑</div>
        <p class="text-2xl font-black text-gray-900">{{ $stats['total_seats'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Total Seats</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 card-shadow">
        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-lg mb-3">✅</div>
        <p class="text-2xl font-black text-green-600">{{ $stats['seated'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Guests Seated</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 card-shadow">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-lg mb-3">🍽️</div>
        <p class="text-2xl font-black text-gray-900">{{ $stats['tables'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Tables</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 card-shadow">
        <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-lg mb-3">⏳</div>
        <p class="text-2xl font-black text-amber-600">{{ $stats['unassigned'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Unassigned Guests</p>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">

    {{-- LEFT: Tables --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Add Table Form --}}
        <div class="bg-white rounded-2xl border border-gray-100 card-shadow" x-data="{ open: false }">
            <div class="flex items-center justify-between px-6 py-4">
                <h3 class="font-bold text-gray-900">Tables</h3>
                <button @click="open = !open"
                        class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-pink-600 to-purple-600 text-white text-sm font-semibold rounded-full shadow-md hover:shadow-lg transition">
                    <span x-text="open ? '✕ Cancel' : '+ Add Table'"></span>
                </button>
            </div>
            <div x-show="open" x-cloak class="border-t border-gray-100 px-6 py-4">
                <form method="POST" action="{{ route('seating.store') }}" class="flex items-end gap-3">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Table Name <span class="text-red-400">*</span></label>
                        <input type="text" name="name" placeholder="e.g. Table 1, Bride's Table" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                    </div>
                    <div class="w-32">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Seats <span class="text-red-400">*</span></label>
                        <input type="number" name="capacity" min="1" max="100" value="8" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                    </div>
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow-md hover:shadow-lg transition whitespace-nowrap">
                        Add Table
                    </button>
                </form>
            </div>
        </div>

        {{-- Table Cards --}}
        @forelse($tables as $table)
        <div class="bg-white rounded-2xl border border-gray-100 card-shadow" x-data="{ editCapacity: false, newCapacity: {{ $table->capacity }} }">

            {{-- Table Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-lg">🍽️</div>
                    <div>
                        <p class="font-bold text-gray-900">{{ $table->name }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            {{-- Capacity editor --}}
                            <div x-show="!editCapacity" class="flex items-center gap-1">
                                <span class="text-xs text-gray-500">
                                    {{ $table->seated_count }} / {{ $table->capacity }} seats
                                </span>
                                <button @click="editCapacity = true" class="text-xs text-pink-500 hover:text-pink-700 ml-1">✏️</button>
                            </div>
                            <form x-show="editCapacity" x-cloak method="POST"
                                  action="{{ route('seating.update', $table) }}"
                                  class="inline-flex items-center gap-1">
                                @csrf @method('PUT')
                                <input type="number" name="capacity" x-model="newCapacity"
                                       min="1" max="100"
                                       class="w-16 text-center border border-pink-300 rounded-lg text-xs py-1 focus:outline-none focus:ring-1 focus:ring-pink-400">
                                <span class="text-xs text-gray-400">seats</span>
                                <button type="submit" class="text-green-500 hover:text-green-700 font-bold text-sm">✓</button>
                                <button type="button" @click="editCapacity = false" class="text-gray-400 hover:text-gray-600 text-sm">✕</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Seat progress bar --}}
                    @php
                        $pct = $table->capacity > 0 ? round(($table->seated_count / $table->capacity) * 100) : 0;
                        $barColor = $pct >= 100 ? 'bg-red-400' : ($pct >= 75 ? 'bg-amber-400' : 'bg-green-400');
                    @endphp
                    <div class="w-24 bg-gray-100 rounded-full h-2">
                        <div class="{{ $barColor }} h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="text-xs font-bold {{ $pct >= 100 ? 'text-red-500' : 'text-gray-500' }}">{{ $pct }}%</span>
                    <form method="POST" action="{{ route('seating.destroy', $table) }}">
                        @csrf @method('DELETE')
                        <button class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-300 hover:text-red-400 hover:bg-red-50 transition ml-1">✕</button>
                    </form>
                </div>
            </div>

            {{-- Assigned Guests --}}
            <div class="px-6 py-3 space-y-2">
                @forelse($table->guests as $guest)
                <div class="flex items-center justify-between py-1.5">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center text-xs font-bold text-purple-600">
                            {{ strtoupper(substr($guest->name, 0, 1)) }}
                        </span>
                        <span class="text-sm text-gray-700">{{ $guest->name }}</span>
                        @if($guest->group)
                        <span class="text-xs text-gray-400">· {{ $guest->group->name }}</span>
                        @endif
                        @if(max(1, (int)$guest->rsvp_confirmed) > 1)
                        <span class="text-xs bg-purple-100 text-purple-600 px-2 py-0.5 rounded-full">{{ $guest->rsvp_confirmed }} seats</span>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('seating.unassign', $guest->seatingAssignment) }}">
                        @csrf @method('DELETE')
                        <button class="text-xs text-gray-300 hover:text-red-400 transition">✕</button>
                    </form>
                </div>
                @empty
                <p class="text-xs text-gray-400 py-1">No guests assigned yet.</p>
                @endforelse
            </div>

            {{-- Assign Guest --}}
            @if($table->available_seats > 0)
            <div class="px-6 pb-4">
                <form method="POST" action="{{ route('seating.assign', $table) }}" class="flex items-center gap-2"
                      x-data="guestSearch({{ $unassigned->values()->map(fn($g) => ['id' => $g->id, 'name' => $g->name, 'group' => $g->group?->name, 'count' => max(1, (int)$g->rsvp_confirmed)])->toJson() }})">
                    @csrf
                    <input type="hidden" name="guest_id" x-model="selectedId">
                    <div class="relative flex-1">
                        <input type="text" x-model="query" @input="search()" @focus="open = true" @click.outside="open = false"
                               placeholder="Search accepted guests..."
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                        <ul x-show="open && results.length" x-cloak
                            class="absolute z-20 left-0 right-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-48 overflow-y-auto">
                            <template x-for="g in results" :key="g.id">
                                <li @click="select(g)"
                                    class="flex items-center justify-between px-3 py-2 text-sm hover:bg-pink-50 cursor-pointer">
                                    <span>
                                        <span x-text="g.name" class="font-medium text-gray-800"></span>
                                        <span x-show="g.group" x-text="' · ' + g.group" class="text-gray-400 text-xs"></span>
                                    </span>
                                    <span x-show="g.count > 1"
                                          x-text="g.count + ' seats'"
                                          class="text-xs bg-purple-100 text-purple-600 px-2 py-0.5 rounded-full ml-2"></span>
                                </li>
                            </template>
                        </ul>
                        <p x-show="open && query.length > 0 && results.length === 0" x-cloak
                           class="absolute z-20 left-0 right-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-lg px-3 py-2 text-xs text-gray-400">
                            No matching guests
                        </p>
                    </div>
                    <button type="submit" :disabled="!selectedId"
                            class="px-4 py-2 text-xs font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow hover:shadow-md transition whitespace-nowrap disabled:opacity-40 disabled:cursor-not-allowed">
                        Assign
                    </button>
                </form>
            </div>
            @else
            <div class="px-6 pb-4">
                <p class="text-xs text-red-400 font-medium">🔴 Table is full</p>
            </div>
            @endif

        </div>
        @empty
        <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-12 text-center">
            <p class="text-4xl mb-3">🍽️</p>
            <p class="text-sm font-medium text-gray-500">No tables yet.</p>
            <p class="text-xs text-gray-400 mt-1">Click "Add Table" to start building your seating plan.</p>
        </div>
        @endforelse
    </div>

    {{-- RIGHT: Unassigned Guests --}}
    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-100 card-shadow">
            <div class="px-6 py-4 border-b border-gray-50">
                <h3 class="font-bold text-gray-900">Unassigned Guests</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $unassigned->count() }} guests not yet seated</p>
            </div>
            <div class="divide-y divide-gray-50 max-h-[600px] overflow-y-auto">
                @forelse($unassigned as $guest)
                <div class="flex items-center gap-3 px-6 py-3">
                    <span class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-xs font-bold text-gray-500 flex-shrink-0">
                        {{ strtoupper(substr($guest->name, 0, 1)) }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $guest->name }}</p>
                        @if($guest->group)
                        <p class="text-xs text-gray-400">{{ $guest->group->name }}</p>
                        @endif
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full
                        {{ $guest->invitation_type === 'family' ? 'bg-purple-50 text-purple-600' :
                           ($guest->invitation_type === 'couple' ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-500') }}">
                        {{ $guest->invitation_label }}
                    </span>
                </div>
                @empty
                <div class="px-6 py-8 text-center">
                    <p class="text-2xl mb-2">🎉</p>
                    <p class="text-sm font-medium text-gray-500">All guests are seated!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function guestSearch(guests) {
    return {
        guests,
        query: '',
        results: [],
        selectedId: '',
        open: false,
        search() {
            this.selectedId = '';
            const q = this.query.toLowerCase();
            this.results = q.length === 0 ? [] : this.guests.filter(g =>
                g.name.toLowerCase().includes(q) || (g.group && g.group.toLowerCase().includes(q))
            );
        },
        select(g) {
            this.selectedId = g.id;
            this.query = g.name + (g.group ? ' · ' + g.group : '');
            this.open = false;
            this.results = [];
        }
    };
}
</script>
@endpush

@endsection
