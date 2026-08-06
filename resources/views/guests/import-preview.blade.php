@extends('layouts.app')
@section('title', 'Import Preview')
@section('page-title', 'Guest Import Preview')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Summary banner --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-5 text-center">
            <p class="text-3xl font-black text-gray-900">{{ count($valid) + count($invalid) }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Rows Found</p>
        </div>
        <div class="bg-green-50 rounded-2xl border border-green-200 card-shadow p-5 text-center">
            <p class="text-3xl font-black text-green-600">{{ count($valid) }}</p>
            <p class="text-xs text-green-600 mt-1 font-semibold">✅ Ready to Import</p>
        </div>
        <div class="bg-red-50 rounded-2xl border border-red-200 card-shadow p-5 text-center">
            <p class="text-3xl font-black text-red-500">{{ count($invalid) }}</p>
            <p class="text-xs text-red-500 mt-1 font-semibold">❌ Has Errors (will be skipped)</p>
        </div>
    </div>

    {{-- Info banner --}}
    <div class="mb-6 flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
        <span class="text-base mt-0.5">💡</span>
        <p class="text-xs text-blue-700 leading-relaxed">
            Review the results below. <span class="font-semibold">Valid rows</span> will be imported when you click "Import Guests".
            <span class="font-semibold">Invalid rows</span> will be skipped — fix them in your CSV and re-upload to add them.
        </p>
    </div>

    {{-- Invalid rows --}}
    @if(count($invalid) > 0)
    <div class="bg-white rounded-2xl border border-red-200 card-shadow mb-6 overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 bg-red-50 border-b border-red-100">
            <span class="text-lg">❌</span>
            <h3 class="font-bold text-red-700">{{ count($invalid) }} Row{{ count($invalid) > 1 ? 's' : '' }} with Errors — Will Be Skipped</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Row</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Name</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Group</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Type</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Issues</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($invalid as $row)
                    <tr class="bg-red-50/30">
                        <td class="px-4 py-3 text-xs font-bold text-red-500">#{{ $row['row'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $row['name'] ?: '—' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $row['group_raw'] ?: '—' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $row['invitation_type'] ?: '—' }}</td>
                        <td class="px-4 py-3">
                            @foreach($row['errors'] as $error)
                            <p class="text-xs text-red-600 font-medium">• {{ $error }}</p>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Valid rows --}}
    @if(count($valid) > 0)
    <div class="bg-white rounded-2xl border border-green-200 card-shadow mb-6 overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 bg-green-50 border-b border-green-100">
            <span class="text-lg">✅</span>
            <h3 class="font-bold text-green-700">{{ count($valid) }} Guest{{ count($valid) > 1 ? 's' : '' }} Ready to Import</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Name</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Side</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Type</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Invited</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Phone</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Email</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($valid as $row)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $row['name'] }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full
                                {{ strtolower($row['group_raw']) === 'bride side' ? 'bg-pink-50 text-pink-700' : 'bg-blue-50 text-blue-700' }}">
                                {{ strtolower($row['group_raw']) === 'bride side' ? '👰' : '🤵' }} {{ $row['group_raw'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500 capitalize">{{ $row['invitation_type'] }}</td>
                        <td class="px-4 py-3 text-center font-bold text-gray-700">{{ $row['total_invited'] }}</td>
                        <td class="px-4 py-3 text-xs text-gray-400">{{ $row['phone'] ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-400">{{ $row['email'] ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-400 max-w-[150px] truncate">{{ $row['notes'] ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Action buttons --}}
    <div class="flex items-center justify-between gap-4">
        <a href="{{ route('guests.index') }}"
           class="px-6 py-2.5 text-sm font-medium text-gray-600 border border-gray-200 rounded-full hover:bg-gray-50 transition">
            ← Cancel
        </a>
        <div class="flex items-center gap-3">
            @if(count($invalid) > 0)
            <p class="text-xs text-gray-400">{{ count($invalid) }} row{{ count($invalid) > 1 ? 's' : '' }} with errors will be skipped</p>
            @endif
            <form method="POST" action="{{ route('guests.import') }}">
                @csrf
                <button type="submit"
                        class="px-8 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow-md hover:shadow-lg transition">
                    Import {{ count($valid) }} Guest{{ count($valid) > 1 ? 's' : '' }} →
                </button>
            </form>
        </div>
    </div>

    @else
    {{-- All rows invalid --}}
    <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-12 text-center">
        <p class="text-4xl mb-4">😕</p>
        <p class="text-base font-semibold text-gray-700">No valid rows found</p>
        <p class="text-sm text-gray-400 mt-1 mb-6">All rows in your CSV have errors. Fix them and try again.</p>
        <a href="{{ route('guests.index') }}"
           class="inline-block px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow-md hover:shadow-lg transition">
            ← Back to Guests
        </a>
    </div>
    @endif

</div>
@endsection
