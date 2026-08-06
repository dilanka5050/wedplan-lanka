@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ===== NO WEDDING STATE ===== --}}
@if(!$wedding)
<div class="max-w-lg mx-auto mt-16 text-center">
    <div class="w-24 h-24 bg-gradient-to-br from-pink-100 to-purple-100 rounded-3xl flex items-center justify-center text-5xl mx-auto mb-6 shadow-lg">💒</div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome to WedPlan Lanka!</h2>
    <p class="text-gray-500 mb-8">Let's set up your wedding workspace to get started with planning your perfect day.</p>
    <a href="{{ route('wedding.setup') }}"
       class="inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-semibold rounded-full shadow-xl shadow-pink-200 hover:shadow-pink-300 hover:scale-105 transition">
        ✨ Create My Wedding
    </a>
</div>

{{-- ===== DASHBOARD ===== --}}
@else

{{-- Welcome Header --}}
<div class="mb-6">
    <p class="text-sm text-gray-400 font-medium">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},</p>
    <h2 class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }} 👋</h2>
</div>

{{-- ===== HERO COUNTDOWN BANNER ===== --}}
@if($daysUntil && $daysUntil > 0)
<div class="relative overflow-hidden rounded-3xl mb-6 p-6 lg:p-8"
     style="background: linear-gradient(135deg, #db2777 0%, #9333ea 60%, #6366f1 100%);">
    <!-- Decorative circles -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
    <div class="absolute bottom-0 left-1/3 w-40 h-40 bg-white opacity-5 rounded-full translate-y-1/2"></div>

    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="text-pink-200 text-sm font-medium mb-1">💍 {{ $wedding->bride_name }} & {{ $wedding->groom_name }}</p>
            <div class="flex items-baseline gap-3">
                <span class="text-5xl lg:text-6xl font-black text-white">{{ $daysUntil }}</span>
                <span class="text-xl text-pink-200 font-medium">days to go</span>
            </div>
            <p class="text-pink-200 text-sm mt-1">
                {{ $wedding->wedding_date->format('l, d F Y') }} ·
                <span class="capitalize">{{ $wedding->wedding_type->value }} Wedding</span>
                @if($wedding->venue) · {{ $wedding->venue }} @endif
            </p>
        </div>
        <div class="flex gap-3">
            <div class="bg-white/15 backdrop-blur rounded-2xl px-5 py-3 text-center border border-white/20">
                <p class="text-2xl font-black text-white">{{ floor($daysUntil / 7) }}</p>
                <p class="text-xs text-pink-200 font-medium">Weeks</p>
            </div>
            <div class="bg-white/15 backdrop-blur rounded-2xl px-5 py-3 text-center border border-white/20">
                <p class="text-2xl font-black text-white">{{ floor($daysUntil / 30) }}</p>
                <p class="text-xs text-pink-200 font-medium">Months</p>
            </div>
        </div>
    </div>

    <!-- Progress bar -->
    <div class="relative z-10 mt-5">
        <div class="flex items-center justify-between mb-1.5">
            <span class="text-xs text-pink-200 font-medium">Planning Progress</span>
            <span class="text-xs text-white font-bold">{{ $progress }}%</span>
        </div>
        <div class="w-full bg-white/20 rounded-full h-2">
            <div class="bg-white h-2 rounded-full transition-all duration-700" style="width: {{ $progress }}%"></div>
        </div>
    </div>
</div>
@elseif($wedding->wedding_date && $daysUntil <= 0)
<div class="relative overflow-hidden rounded-3xl mb-6 p-6 bg-gradient-to-r from-amber-400 to-pink-500">
    <p class="text-white font-bold text-xl">🎉 Congratulations! Your wedding day has arrived!</p>
    <p class="text-white/80 text-sm mt-1">Wishing {{ $wedding->bride_name }} & {{ $wedding->groom_name }} a lifetime of happiness!</p>
</div>
@else
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex items-center gap-3">
    <span class="text-2xl">📅</span>
    <div>
        <p class="text-sm font-semibold text-amber-800">Wedding date not set</p>
        <p class="text-xs text-amber-600">Add your wedding date to see the countdown timer.</p>
    </div>
    <a href="{{ route('wedding.edit') }}" class="ml-auto text-xs font-bold text-amber-700 bg-amber-100 px-3 py-1.5 rounded-full hover:bg-amber-200 transition whitespace-nowrap">Set Date</a>
</div>
@endif

{{-- ===== ONBOARDING CHECKLIST (new users only) ===== --}}
@php
    $isNewUser = $progress === 0 && $guestCount === 0 && $budgetSummary['estimated'] === 0;
    $hasDate   = (bool) $wedding->wedding_date;
    $hasVenue  = (bool) $wedding->venue;
@endphp
@if($isNewUser)
<div class="bg-white rounded-2xl border border-pink-100 card-shadow p-6 mb-6">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-10 h-10 btn-gradient rounded-xl flex items-center justify-center text-lg shadow-md flex-shrink-0">🚀</div>
        <div>
            <h3 class="font-bold text-gray-900">Get started with your wedding plan</h3>
            <p class="text-xs text-gray-400 mt-0.5">Complete these steps to set up your workspace</p>
        </div>
    </div>
    <div class="space-y-3">
        {{-- Step 1: Wedding date --}}
        <a href="{{ route('wedding.edit') }}"
           class="flex items-center gap-4 p-4 rounded-xl border-2 transition
                  {{ $hasDate ? 'border-green-200 bg-green-50' : 'border-gray-200 hover:border-pink-300 hover:bg-pink-50/30' }}">
            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-sm font-black
                        {{ $hasDate ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-400' }}">
                {{ $hasDate ? '✓' : '1' }}
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold {{ $hasDate ? 'text-green-700 line-through' : 'text-gray-800' }}">Set your wedding date</p>
                <p class="text-xs {{ $hasDate ? 'text-green-500' : 'text-gray-400' }} mt-0.5">
                    {{ $hasDate ? 'Done — ' . $wedding->wedding_date->format('d M Y') : 'Add your date to start the countdown timer' }}
                </p>
            </div>
            @if(!$hasDate)
            <span class="text-xs font-semibold text-pink-600 bg-pink-50 px-3 py-1 rounded-full whitespace-nowrap">Set Date →</span>
            @endif
        </a>

        {{-- Step 2: Add first guest --}}
        <a href="{{ route('guests.index') }}"
           class="flex items-center gap-4 p-4 rounded-xl border-2 transition
                  {{ $guestCount > 0 ? 'border-green-200 bg-green-50' : 'border-gray-200 hover:border-pink-300 hover:bg-pink-50/30' }}">
            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-sm font-black
                        {{ $guestCount > 0 ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-400' }}">
                {{ $guestCount > 0 ? '✓' : '2' }}
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold {{ $guestCount > 0 ? 'text-green-700 line-through' : 'text-gray-800' }}">Add your first guest</p>
                <p class="text-xs {{ $guestCount > 0 ? 'text-green-500' : 'text-gray-400' }} mt-0.5">
                    {{ $guestCount > 0 ? 'Done — ' . $guestCount . ' guest(s) added' : 'Start building your guest list' }}
                </p>
            </div>
            @if(!$guestCount)
            <span class="text-xs font-semibold text-pink-600 bg-pink-50 px-3 py-1 rounded-full whitespace-nowrap">Add Guest →</span>
            @endif
        </a>

        {{-- Step 3: Add first budget item --}}
        <a href="{{ route('budgets.index') }}"
           class="flex items-center gap-4 p-4 rounded-xl border-2 transition
                  {{ $budgetSummary['estimated'] > 0 ? 'border-green-200 bg-green-50' : 'border-gray-200 hover:border-pink-300 hover:bg-pink-50/30' }}">
            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-sm font-black
                        {{ $budgetSummary['estimated'] > 0 ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-400' }}">
                {{ $budgetSummary['estimated'] > 0 ? '✓' : '3' }}
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold {{ $budgetSummary['estimated'] > 0 ? 'text-green-700 line-through' : 'text-gray-800' }}">Set your wedding budget</p>
                <p class="text-xs {{ $budgetSummary['estimated'] > 0 ? 'text-green-500' : 'text-gray-400' }} mt-0.5">
                    {{ $budgetSummary['estimated'] > 0 ? 'Done — LKR ' . number_format($budgetSummary['estimated']) . ' estimated' : 'Add your first budget item to track spending' }}
                </p>
            </div>
            @if(!$budgetSummary['estimated'])
            <span class="text-xs font-semibold text-pink-600 bg-pink-50 px-3 py-1 rounded-full whitespace-nowrap">Add Budget →</span>
            @endif
        </a>
    </div>

    {{-- Progress indicator --}}
    @php $stepsComplete = (int)$hasDate + (int)($guestCount > 0) + (int)($budgetSummary['estimated'] > 0); @endphp
    <div class="mt-4 flex items-center gap-3">
        <div class="flex-1 bg-gray-100 rounded-full h-1.5">
            <div class="h-1.5 rounded-full bg-gradient-to-r from-pink-500 to-purple-500 transition-all duration-700"
                 style="width: {{ round(($stepsComplete / 3) * 100) }}%"></div>
        </div>
        <span class="text-xs font-semibold text-gray-500">{{ $stepsComplete }}/3 complete</span>
    </div>
</div>
@endif

{{-- ===== STATS GRID ===== --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    {{-- Progress --}}
    <div class="bg-white rounded-2xl p-5 card-shadow border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-pink-50 rounded-xl flex items-center justify-center text-lg">📋</div>
            <span class="text-xs font-semibold text-pink-600 bg-pink-50 px-2 py-0.5 rounded-full">Tasks</span>
        </div>
        <p class="text-2xl font-black text-gray-900">{{ $progress }}<span class="text-base font-medium text-gray-400">%</span></p>
        <p class="text-xs text-gray-500 mt-0.5 mb-3">Planning complete</p>
        <div class="w-full bg-gray-100 rounded-full h-1.5">
            <div class="bg-gradient-to-r from-pink-500 to-purple-500 h-1.5 rounded-full" style="width:{{ $progress }}%"></div>
        </div>
    </div>

    {{-- Budget --}}
    <div class="bg-white rounded-2xl p-5 card-shadow border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-lg">💰</div>
            <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full">Budget</span>
        </div>
        <p class="text-xl font-black text-gray-900 leading-tight">
            LKR {{ number_format($budgetSummary['actual'] / 1000, 0) }}<span class="text-sm font-medium text-gray-400">k</span>
        </p>
        <p class="text-xs text-gray-500 mt-0.5 mb-3">of LKR {{ number_format($budgetSummary['estimated'] / 1000, 0) }}k estimated</p>
        @php $budgetPct = $budgetSummary['estimated'] > 0 ? min(100, ($budgetSummary['actual'] / $budgetSummary['estimated']) * 100) : 0; @endphp
        <div class="w-full bg-gray-100 rounded-full h-1.5">
            <div class="h-1.5 rounded-full {{ $budgetPct > 90 ? 'bg-red-400' : 'bg-gradient-to-r from-purple-500 to-pink-500' }}" style="width:{{ $budgetPct }}%"></div>
        </div>
    </div>

    {{-- Guests --}}
    <div class="bg-white rounded-2xl p-5 card-shadow border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-lg">👥</div>
            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">Guests</span>
        </div>
        <p class="text-2xl font-black text-gray-900">{{ $guestCount }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Total invited</p>
        <div class="mt-3 flex gap-1.5">
            @php
                $accepted = $wedding->guests()->where('rsvp_status','accepted')->count();
                $declined = $wedding->guests()->where('rsvp_status','declined')->count();
            @endphp
            @if($guestCount > 0)
            <div class="flex-1 bg-green-100 rounded-full h-1.5" title="Accepted">
                <div class="bg-green-400 h-1.5 rounded-full" style="width:{{ ($accepted/$guestCount)*100 }}%"></div>
            </div>
            @else
            <div class="flex-1 bg-gray-100 rounded-full h-1.5"></div>
            @endif
        </div>
        <p class="text-xs text-gray-400 mt-1">{{ $accepted }} accepted · {{ $declined }} declined</p>
    </div>

    {{-- Wedding Date --}}
    <div class="bg-white rounded-2xl p-5 card-shadow border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-lg">📅</div>
            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Date</span>
        </div>
        @if($wedding->wedding_date)
        <p class="text-lg font-black text-gray-900 leading-tight">{{ $wedding->wedding_date->format('d M') }}</p>
        <p class="text-sm font-semibold text-gray-600">{{ $wedding->wedding_date->format('Y') }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $wedding->wedding_date->format('l') }}</p>
        @else
        <p class="text-sm font-medium text-gray-400 mt-2">Not set yet</p>
        <a href="{{ route('wedding.edit') }}" class="text-xs text-pink-600 font-semibold mt-1 inline-block hover:underline">Set date →</a>
        @endif
    </div>
</div>

{{-- ===== BOTTOM GRID ===== --}}
<div class="grid lg:grid-cols-3 gap-6">

    {{-- Upcoming Tasks --}}
    <div class="lg:col-span-2 bg-white rounded-2xl card-shadow border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
            <div class="flex items-center gap-2">
                <span class="text-lg">✅</span>
                <h3 class="font-bold text-gray-900">Upcoming Tasks</h3>
            </div>
            <a href="{{ route('tasks.index') }}" class="text-xs font-semibold text-pink-600 hover:text-pink-700 bg-pink-50 px-3 py-1.5 rounded-full transition">View All →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($upcomingTasks as $task)
            <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50/50 transition">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0
                    {{ $task->priority->value === 'urgent' ? 'bg-red-100' : ($task->priority->value === 'high' ? 'bg-orange-100' : ($task->priority->value === 'medium' ? 'bg-yellow-100' : 'bg-green-100')) }}">
                    <span class="text-sm">
                        {{ $task->priority->value === 'urgent' ? '🔴' : ($task->priority->value === 'high' ? '🟠' : ($task->priority->value === 'medium' ? '🟡' : '🟢')) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $task->title }}</p>
                    <p class="text-xs text-gray-400">{{ $task->category?->name ?? 'General' }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    @if($task->due_date)
                    <p class="text-xs font-semibold {{ $task->due_date->isPast() ? 'text-red-500' : 'text-gray-500' }}">
                        {{ $task->due_date->format('d M') }}
                    </p>
                    @endif
                    <span class="text-xs px-2 py-0.5 rounded-full
                        {{ $task->status->value === 'in_progress' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500' }}">
                        {{ ucfirst(str_replace('_',' ',$task->status->value)) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="px-6 py-10 text-center">
                <p class="text-3xl mb-2">🎉</p>
                <p class="text-sm font-medium text-gray-500">All caught up! No pending tasks.</p>
                <a href="{{ route('tasks.index') }}" class="text-xs text-pink-600 font-semibold mt-2 inline-block hover:underline">Add tasks →</a>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Right Column --}}
    <div class="space-y-5">

        {{-- Budget Summary --}}
        <div class="bg-white rounded-2xl card-shadow border border-gray-100 p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-lg">💰</span>
                <h3 class="font-bold text-gray-900">Budget Summary</h3>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Estimated</span>
                    <span class="text-sm font-bold text-gray-800">LKR {{ number_format($budgetSummary['estimated']) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Spent</span>
                    <span class="text-sm font-bold text-pink-600">LKR {{ number_format($budgetSummary['actual']) }}</span>
                </div>
                <div class="h-px bg-gray-100"></div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-600">Remaining</span>
                    <span class="text-sm font-black {{ $budgetSummary['remaining'] >= 0 ? 'text-green-600' : 'text-red-500' }}">
                        LKR {{ number_format(abs($budgetSummary['remaining'])) }}
                        {{ $budgetSummary['remaining'] < 0 ? '⚠️' : '' }}
                    </span>
                </div>
            </div>
            <a href="{{ route('budgets.index') }}" class="mt-4 block text-center text-xs font-semibold text-purple-600 bg-purple-50 py-2 rounded-xl hover:bg-purple-100 transition">Manage Budget →</a>
        </div>

        {{-- Quick Links --}}
        <div class="bg-white rounded-2xl card-shadow border border-gray-100 p-5">
            <h3 class="font-bold text-gray-900 mb-3">Quick Actions</h3>
            <div class="grid grid-cols-3 gap-2">
                @foreach([
                    [route('tasks.index'),'✅','Checklist','bg-pink-50 text-pink-700'],
                    [route('guests.index'),'👥','Guests','bg-blue-50 text-blue-700'],
                    [route('budgets.index'),'💰','Budget','bg-purple-50 text-purple-700'],
                    [route('vault.index'),'📁','Vault','bg-green-50 text-green-700'],
                    [route('notes.index'),'📝','Notes','bg-amber-50 text-amber-700'],
                    [route('wedding.edit'),'⚙️','Settings','bg-gray-50 text-gray-700'],
                ] as [$url,$icon,$label,$cls])
                <a href="{{ $url }}" class="flex flex-col items-center gap-1.5 p-3 {{ $cls }} rounded-xl hover:opacity-80 transition text-center">
                    <span class="text-xl">{{ $icon }}</span>
                    <span class="text-xs font-semibold">{{ $label }}</span>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Wedding Type Badge --}}
        <div class="bg-gradient-to-br from-pink-50 to-purple-50 rounded-2xl border border-pink-100 p-5 text-center">
            <p class="text-3xl mb-2">
                {{ $wedding->wedding_type->value === 'sinhala' ? '🪔' : ($wedding->wedding_type->value === 'tamil' ? '🌺' : ($wedding->wedding_type->value === 'muslim' ? '☪️' : '✝️')) }}
            </p>
            <p class="text-sm font-bold text-gray-800 capitalize">{{ $wedding->wedding_type->value }} Wedding</p>
            <p class="text-xs text-gray-500 mt-0.5">
                {{ $wedding->wedding_type->value === 'sinhala' ? 'Poruwa Ceremony' : ($wedding->wedding_type->value === 'tamil' ? 'Thali Ceremony' : ($wedding->wedding_type->value === 'muslim' ? 'Nikah Ceremony' : 'Church Wedding')) }}
            </p>
            @if($wedding->venue)
            <p class="text-xs text-pink-600 font-medium mt-2">📍 {{ $wedding->venue }}</p>
            @endif
        </div>

    </div>
</div>

@endif
@endsection
