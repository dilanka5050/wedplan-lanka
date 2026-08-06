@extends('admin.layout')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')

{{-- Stats grid --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    @php
        $stats = [
            ['icon'=>'👥','label'=>'Total Couples',   'value'=>$totalCouples,  'sub'=>"{$proUsers} Pro · {$freeUsers} Free", 'color'=>'pink'],
            ['icon'=>'🆕','label'=>'New This Week',   'value'=>$newThisWeek,   'sub'=>'Registrations',                       'color'=>'green'],
            ['icon'=>'⭐','label'=>'Pro Subscribers', 'value'=>$proUsers,      'sub'=>$totalCouples > 0 ? round(($proUsers/$totalCouples)*100).'% conversion' : '—', 'color'=>'amber'],
            ['icon'=>'💍','label'=>'Total Weddings',  'value'=>$totalWeddings, 'sub'=>"{$upcoming} upcoming",                'color'=>'rose'],
            ['icon'=>'📁','label'=>'Files Uploaded',  'value'=>$totalFiles,    'sub'=>"{$storageMB} MB used",               'color'=>'purple'],
        ];
        $colors = ['pink'=>'bg-pink-50 text-pink-600','green'=>'bg-green-50 text-green-600','amber'=>'bg-amber-50 text-amber-600','rose'=>'bg-rose-50 text-rose-600','purple'=>'bg-purple-50 text-purple-600'];
    @endphp
    @foreach($stats as $s)
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center text-lg {{ $colors[$s['color']] }}">{{ $s['icon'] }}</div>
            <span class="text-xs text-gray-500 font-medium">{{ $s['label'] }}</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $s['value'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $s['sub'] }}</p>
    </div>
    @endforeach
</div>

{{-- Recent Registrations --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800 text-sm">Recent Couple Registrations</h2>
        <a href="{{ route('admin.users') }}" class="text-xs text-pink-600 hover:underline">View all →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Name</th>
                    <th class="px-5 py-3 text-left">Email</th>
                    <th class="px-5 py-3 text-left">Plan</th>
                    <th class="px-5 py-3 text-left">Wedding</th>
                    <th class="px-5 py-3 text-left">Date</th>
                    <th class="px-5 py-3 text-left">Registered</th>
                    <th class="px-5 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recentUsers as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $user->email }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                            {{ $user->isPro() ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $user->activeSubscription?->plan?->name ?? 'Free' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">
                        {{ $user->wedding ? $user->wedding->bride_name.' & '.$user->wedding->groom_name : '—' }}
                    </td>
                    <td class="px-5 py-3 text-gray-500">
                        {{ $user->wedding?->wedding_date?->format('d M Y') ?? '—' }}
                    </td>
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $user->created_at->diffForHumans() }}</td>
                    <td class="px-5 py-3">
                        @if(!$user->isPro())
                        <form method="POST" action="{{ route('admin.users.upgrade', $user) }}">
                            @csrf
                            <input type="hidden" name="plan" value="pro">
                            <button class="text-xs text-green-600 hover:underline font-medium">Upgrade</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-8 text-center text-gray-400 text-sm">No registrations yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Storage Alerts --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800 text-sm">Storage Usage</h2>
        <a href="{{ route('admin.weddings') }}" class="text-xs text-pink-600 hover:underline">View all →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Wedding</th>
                    <th class="px-5 py-3 text-left">Couple</th>
                    <th class="px-5 py-3 text-left">Plan</th>
                    <th class="px-5 py-3 text-center">Files</th>
                    <th class="px-5 py-3 text-center">Limit</th>
                    <th class="px-5 py-3 text-center">Usage</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($storageAlerts as $row)
                @php $w = $row['wedding_obj']; @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $w->bride_name }} & {{ $w->groom_name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $w->user->name }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                            {{ $w->user->isPro() ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $w->user->activeSubscription?->plan?->name ?? 'Free' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center font-bold text-gray-800">{{ $row['files_count'] }}</td>
                    <td class="px-5 py-3 text-center text-gray-500">
                        {{ $row['effective_limit'] }}
                        @if($w->custom_upload_limit) <span class="text-amber-500 text-xs">★</span> @endif
                    </td>
                    <td class="px-5 py-3 text-center">
                        @php $pct = $row['usage_pct']; @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                            {{ $pct >= 100 ? 'bg-red-100 text-red-700' : ($pct >= 80 ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                            {{ $pct }}%
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400 text-sm">No file usage yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
