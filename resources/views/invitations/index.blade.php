@extends('layouts.app')
@section('title', 'Invitation')
@section('page-title', 'Wedding Invitation')

@section('content')

<div class="grid lg:grid-cols-2 gap-6">

    {{-- LEFT: Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-6">

        {{-- Info banner --}}
        @if(!$invitation)
        <div class="mb-5 flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
            <span class="text-base mt-0.5">💡</span>
            <p class="text-xs text-blue-700 leading-relaxed">
                <span class="font-semibold">Fill in your invitation details first, then save.</span>
                Once saved, the live preview on the right will show your actual invitation card with your names, date, and venue.
            </p>
        </div>
        @else
        <div class="mb-5 flex items-start gap-3 bg-green-50 border border-green-100 rounded-xl px-4 py-3">
            <span class="text-base mt-0.5">✅</span>
            <p class="text-xs text-green-700 leading-relaxed">
                <span class="font-semibold">Invitation saved.</span>
                The preview on the right shows your real card. Switch templates using the tabs to compare, then save again to apply a different template.
            </p>
        </div>
        @endif

        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-pink-50 rounded-xl flex items-center justify-center text-xl">💌</div>
            <div>
                <h2 class="font-bold text-gray-900">Invitation Details</h2>
                <p class="text-xs text-gray-400 mt-0.5">Fill in the details then choose a template</p>
            </div>
        </div>

        <form method="POST" action="{{ route('invitations.store') }}" class="space-y-4">
            @csrf

            {{-- Names --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Bride's First Name <span class="text-red-400">*</span></label>
                    <input type="text" name="bride_name" value="{{ old('bride_name', $invitation?->bride_name) }}"
                           placeholder="e.g. Sanduni" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Groom's First Name <span class="text-red-400">*</span></label>
                    <input type="text" name="groom_name" value="{{ old('groom_name', $invitation?->groom_name) }}"
                           placeholder="e.g. Nilanka" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                </div>
            </div>

            {{-- Surnames --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Bride's Family Name <span class="text-red-400">*</span></label>
                    <input type="text" name="bride_surname" value="{{ old('bride_surname', $invitation?->bride_surname) }}"
                           placeholder="e.g. Karunadasa" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                    <p class="text-xs text-gray-400 mt-1">Used as: DAUGHTER OF MR. & MRS. ...</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Groom's Family Name <span class="text-red-400">*</span></label>
                    <input type="text" name="groom_surname" value="{{ old('groom_surname', $invitation?->groom_surname) }}"
                           placeholder="e.g. Abheythunga" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                    <p class="text-xs text-gray-400 mt-1">Used as: SON OF MR. & MRS. ...</p>
                </div>
            </div>

            {{-- Wedding Day --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Wedding Day <span class="text-red-400">*</span></label>
                <input type="text" name="wedding_day" value="{{ old('wedding_day', $invitation?->wedding_day) }}"
                       placeholder="e.g. Sunday 11th March 2026" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                <p class="text-xs text-gray-400 mt-1">Format: Day-of-week DayNumber Month Year — e.g. Sunday 11th March 2026</p>
            </div>

            {{-- Location --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Wedding Location <span class="text-red-400">*</span></label>
                <input type="text" name="wedding_location" value="{{ old('wedding_location', $invitation?->wedding_location) }}"
                       placeholder="e.g. The Grand Royal Hotel, Kurunegala" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                <p class="text-xs text-gray-400 mt-1">Separate venue name and city with a comma</p>
            </div>

            {{-- Venue Map URL --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Venue Google Maps Link</label>
                <input type="url" name="venue_map_url" value="{{ old('venue_map_url', $invitation?->venue_map_url) }}"
                       placeholder="https://maps.google.com/?q=..."
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                <p class="text-xs text-gray-400 mt-1">Paste the Google Maps share link for your venue</p>
            </div>

            {{-- Duration --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">From <span class="text-red-400">*</span></label>
                    <input type="text" name="duration_from" value="{{ old('duration_from', $invitation?->duration_from) }}"
                           placeholder="e.g. 09.00 A.M" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">To <span class="text-red-400">*</span></label>
                    <input type="text" name="duration_to" value="{{ old('duration_to', $invitation?->duration_to) }}"
                           placeholder="e.g. 04.00 PM" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                </div>
            </div>

            {{-- Poruwa Time --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Poruwa Ceremony Time <span class="text-red-400">*</span></label>
                <input type="text" name="poruwa_time" value="{{ old('poruwa_time', $invitation?->poruwa_time) }}"
                       placeholder="e.g. 09.18 A.M" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
            </div>

            {{-- Contact Numbers --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Bride's WhatsApp Number</label>
                    <x-phone-input name="bride_phone" :value="old('bride_phone', $invitation?->bride_phone)" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Groom's WhatsApp Number</label>
                    <x-phone-input name="groom_phone" :value="old('groom_phone', $invitation?->groom_phone)" />
                </div>
            </div>

            {{-- Template Selector --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-3">Choose Template <span class="text-red-400">*</span></label>

                @if(count($availableTemplates) > 0)
                <div class="grid grid-cols-3 gap-3">
                    @foreach($availableTemplates as $val => $tpl)
                    <label class="cursor-pointer">
                        <input type="radio" name="template" value="{{ $val }}" class="sr-only peer"
                               @change="$dispatch('template-changed', '{{ $val }}')"
                               {{ old('template', $invitation?->template ?? array_key_first($availableTemplates)) === $val ? 'checked' : '' }}>
                        <div class="border-2 rounded-2xl overflow-hidden transition peer-checked:border-pink-500 peer-checked:shadow-lg border-gray-200 hover:border-pink-300">
                            <div class="{{ $tpl['bg'] }} border-b p-4 text-center">
                                <p class="font-bold text-lg" style="font-family: serif; color: {{ $tpl['color'] }}">
                                    Bride & Groom
                                </p>
                                <p class="text-xs {{ $tpl['tc'] }} mt-1 uppercase tracking-wide">{{ $tpl['sub'] }}</p>
                            </div>
                            <div class="p-3 text-center bg-white">
                                <p class="text-xs font-semibold text-gray-700">{{ $tpl['label'] }}</p>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
                @else
                <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-4">
                    <span class="text-2xl">🎞️</span>
                    <div>
                        <p class="text-sm font-bold text-amber-800 capitalize">{{ $weddingType }} Wedding Templates — Coming Soon</p>
                        <p class="text-xs text-amber-600 mt-1">
                            We're currently designing beautiful invitation templates for {{ ucfirst($weddingType) }} weddings.
                            They'll be available shortly. In the meantime, please contact your wedding planner.
                        </p>
                    </div>
                </div>
                @endif
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 py-3 text-sm font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow-md hover:shadow-lg transition">
                    💾 Save & Preview Invitation
                </button>
                @if($invitation)
                <a href="{{ route('invitations.preview') }}"
                   class="px-5 py-3 text-sm font-semibold text-pink-600 border-2 border-pink-200 rounded-full hover:bg-pink-50 transition">
                    👁 Preview
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- RIGHT: Live template previews --}}
    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-5"
             x-data="{ selected: '{{ old('template', $invitation?->template ?? 'card1') }}' }"
             x-on:template-changed.window="selected = $event.detail">

            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900">Template Preview</h3>
                <span class="text-xs text-gray-400">Updates as you select</span>
            </div>

            {{-- Tab switcher --}}
            @if(count($availableTemplates) > 0)
            <div class="flex gap-1 bg-gray-100 rounded-xl p-1 mb-4 flex-wrap">
                @foreach($availableTemplates as $val => $tpl)
                <button type="button"
                        @click="selected = '{{ $val }}'"
                        :class="selected === '{{ $val }}' ? 'bg-white shadow font-semibold text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 py-1.5 rounded-lg text-xs transition whitespace-nowrap">
                    {{ $tpl['sub'] }}
                </button>
                @endforeach
            </div>

            {{-- Scaled iframe previews --}}
            @foreach($availableTemplates as $val => $tpl)
            <div x-show="selected === '{{ $val }}'" x-cloak
                 x-ref="previewWrap_{{ $val }}"
                 class="relative w-full rounded-xl overflow-hidden border border-gray-100 shadow-sm bg-gray-50"
                 style="padding-top: 52.5%">
                @if($invitation)
                <iframe src="{{ route('invitations.preview') }}?template={{ $val }}"
                        class="absolute top-0 left-0 rounded-xl pointer-events-none"
                        style="width:800px; height:1200px; transform-origin: top left;"
                        x-init="$nextTick(() => { const w = $el.parentElement.offsetWidth; $el.style.transform = 'scale(' + (w/800) + ')'; $el.parentElement.style.paddingTop = (w * 1200/800) + 'px'; })"
                        scrolling="no" frameborder="0">
                </iframe>
                @else
                <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 text-center px-6">
                    <span class="text-4xl">👁</span>
                    <p class="text-sm font-semibold text-gray-600">Fill in your details first</p>
                    <p class="text-xs text-gray-400">Save the form on the left to see a live preview of your invitation card here.</p>
                </div>
                @endif
            </div>
            @endforeach
            @else
            <div class="flex flex-col items-center justify-center h-48 gap-3 text-center px-6 bg-gray-50 rounded-xl border border-gray-100">
                <span class="text-4xl">🎞️</span>
                <p class="text-sm font-semibold text-gray-500 capitalize">{{ $weddingType }} templates coming soon</p>
            </div>
            @endif

            @if($invitation)
            <div class="mt-4 flex gap-2">
                <a href="{{ route('invitations.preview') }}"
                   target="_blank"
                   class="flex-1 text-center py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow hover:shadow-md transition">
                    🖨️ Open Full Preview
                </a>
            </div>
            @endif
        </div>
    </div>

</div>

@endsection
