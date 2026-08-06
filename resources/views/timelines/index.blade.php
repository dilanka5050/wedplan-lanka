@extends('layouts.app')
@section('title', 'Agenda')
@section('page-title', 'Wedding Agenda')

@section('content')

{{-- Add Event Card --}}
<div class="bg-white rounded-2xl border border-gray-100 card-shadow mb-6" x-data="{ open: false }">
    <div class="flex items-center justify-between px-6 py-4">
        <h3 class="font-bold text-gray-900">Ceremony Schedule</h3>
        <button @click="open = !open"
                class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-pink-600 to-purple-600 text-white text-sm font-semibold rounded-full shadow-md hover:shadow-lg transition">
            <span x-text="open ? '✕ Cancel' : '+ Add Event'"></span>
        </button>
    </div>

    <div x-show="open" x-cloak class="border-t border-gray-100 px-6 py-5">
        <form method="POST" action="{{ route('timelines.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Event Title <span class="text-red-400">*</span></label>
                    <input type="text" name="title" placeholder="e.g. Poruwa Ceremony, Reception Dinner" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Start Time</label>
                    <input type="time" name="start_time"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">End Time</label>
                    <input type="time" name="end_time"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Nekath Time
                        <span class="text-gray-400 font-normal">(auspicious time)</span>
                    </label>
                    <input type="time" name="nekath_time"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Description</label>
                    <input type="text" name="description" placeholder="Optional notes about this event"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-1">
                <button type="button" @click="open = false"
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 border border-gray-200 rounded-full hover:bg-gray-50 transition">Cancel</button>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow-md hover:shadow-lg transition">
                    Add Event
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Info banner --}}
@if($timelines->count() > 0)
<div class="mb-4 flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
    <span class="text-base mt-0.5">💡</span>
    <p class="text-xs text-blue-700 leading-relaxed">
        Events are ordered by <span class="font-semibold">Start Time</span>. Each event shows its time range (start → end).
        <span class="font-semibold">Nekath time</span> is the auspicious moment for key ceremonies — shown highlighted in amber.
        Click <span class="font-semibold">✏️</span> to edit any event.
    </p>
</div>
@endif

{{-- Timeline --}}
@forelse($timelines as $event)
<div class="relative flex gap-4 mb-3" x-data="{ editing: false }">

    {{-- Timeline spine --}}
    <div class="flex flex-col items-center flex-shrink-0 w-16">
        <div class="w-3 h-3 rounded-full bg-gradient-to-br from-pink-500 to-purple-500 mt-5 shadow-sm z-10"></div>
        @if(!$loop->last)
        <div class="w-0.5 flex-1 bg-gradient-to-b from-pink-200 to-purple-100 mt-1"></div>
        @endif
    </div>

    {{-- Event card --}}
    <div class="flex-1 bg-white rounded-2xl border border-gray-100 card-shadow mb-1 overflow-hidden">

        {{-- View state --}}
        <div x-show="!editing" class="flex items-start gap-4 px-5 py-4">

            {{-- Time block --}}
            <div class="flex-shrink-0 text-center min-w-[80px]">
                @if($event->start_time)
                <p class="text-sm font-black text-pink-600">
                    {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}
                </p>
                @if($event->end_time)
                <p class="text-xs text-gray-400 mt-0.5">
                    → {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                </p>
                @endif
                @else
                <p class="text-xs text-gray-300 italic">No time</p>
                @endif

                @if($event->nekath_time)
                <div class="mt-1.5 bg-amber-50 border border-amber-200 rounded-lg px-1.5 py-1">
                    <p class="text-xs font-bold text-amber-600">🕐 {{ \Carbon\Carbon::parse($event->nekath_time)->format('h:i A') }}</p>
                    <p class="text-xs text-amber-400" style="font-size:9px">Nekath</p>
                </div>
                @endif
            </div>

            {{-- Divider --}}
            <div class="w-px self-stretch bg-gray-100 flex-shrink-0"></div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800">{{ $event->title }}</p>
                @if($event->description)
                <p class="text-xs text-gray-400 mt-0.5">{{ $event->description }}</p>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-1 flex-shrink-0">
                <button @click="editing = true"
                        class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-300 hover:text-pink-500 hover:bg-pink-50 transition"
                        title="Edit">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/>
                    </svg>
                </button>
                <form method="POST" action="{{ route('timelines.destroy', $event) }}">
                    @csrf @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Remove this event?')"
                            class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-300 hover:text-red-400 hover:bg-red-50 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Edit state --}}
        <div x-show="editing" x-cloak class="px-5 py-4 bg-pink-50/30 border-t border-pink-100">
            <form method="POST" action="{{ route('timelines.update', $event) }}" class="space-y-3">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Event Title</label>
                        <input type="text" name="title" value="{{ $event->title }}" required
                               class="w-full px-3 py-2 border border-pink-300 bg-white rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Start Time</label>
                        <input type="time" name="start_time"
                               value="{{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('H:i') : '' }}"
                               class="w-full px-3 py-2 border border-pink-300 bg-white rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">End Time</label>
                        <input type="time" name="end_time"
                               value="{{ $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('H:i') : '' }}"
                               class="w-full px-3 py-2 border border-pink-300 bg-white rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nekath Time</label>
                        <input type="time" name="nekath_time"
                               value="{{ $event->nekath_time ? \Carbon\Carbon::parse($event->nekath_time)->format('H:i') : '' }}"
                               class="w-full px-3 py-2 border border-pink-300 bg-white rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Description</label>
                        <input type="text" name="description" value="{{ $event->description }}"
                               class="w-full px-3 py-2 border border-pink-300 bg-white rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" @click="editing = false"
                            class="px-4 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-full hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit"
                            class="px-5 py-2 text-xs font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow hover:shadow-md transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@empty
<div class="bg-white rounded-2xl border border-gray-100 card-shadow p-16 text-center">
    <p class="text-5xl mb-4">📅</p>
    <p class="text-base font-semibold text-gray-700">No agenda events yet</p>
    <p class="text-sm text-gray-400 mt-1">Plan your ceremony schedule — Poruwa, reception, dinner & more.</p>
</div>
@endforelse

@endsection
