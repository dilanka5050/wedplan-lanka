@extends('admin.layout')
@section('title', 'Weddings')
@section('heading', 'Weddings')

@section('content')

<form method="GET" class="mb-4 flex gap-2">
    <input type="text" name="search" value="{{ $search }}" placeholder="Search bride, groom or owner…"
           class="flex-1 max-w-sm px-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
    <button class="px-4 py-2 text-sm bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition">Search</button>
    @if($search)
    <a href="{{ route('admin.weddings') }}" class="px-4 py-2 text-sm bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">Clear</a>
    @endif
</form>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Wedding</th>
                    <th class="px-5 py-3 text-left">Owner</th>
                    <th class="px-5 py-3 text-left">Plan</th>
                    <th class="px-5 py-3 text-left">Date</th>
                    <th class="px-5 py-3 text-center">Files</th>
                    <th class="px-5 py-3 text-center">Limit</th>
                    <th class="px-5 py-3 text-left">Last Access</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($weddings as $wedding)
                @php
                    $limit = $wedding->custom_upload_limit
                        ?? $wedding->user->activeSubscription?->plan?->max_uploads ?? 0;
                    $pct = $limit > 0 ? round(($wedding->files_count / $limit) * 100) : 0;
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-900">{{ $wedding->bride_name }} & {{ $wedding->groom_name }}</p>
                        @if($wedding->isVaultArchived())
                        <span class="text-xs text-red-500 font-semibold">Vault Archived</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $wedding->user->name }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                            {{ $wedding->user->isPro() ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $wedding->user->activeSubscription?->plan?->name ?? 'Free' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">
                        {{ $wedding->wedding_date?->format('d M Y') ?? '—' }}
                    </td>
                    <td class="px-5 py-3 text-center font-bold text-gray-800">{{ $wedding->files_count }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-xs {{ $pct >= 100 ? 'text-red-600 font-bold' : ($pct >= 80 ? 'text-amber-600 font-semibold' : 'text-gray-500') }}">
                            {{ $wedding->files_count }}/{{ $limit }}
                            @if($wedding->custom_upload_limit)<span class="text-amber-500">★</span>@endif
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-400 text-xs">
                        @if($wedding->last_accessed_at)
                            {{ $wedding->last_accessed_at->diffForHumans() }}<br>
                            <span class="text-gray-300">by {{ $wedding->lastAccessedBy?->name ?? '?' }}</span>
                        @else
                            Never
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="{{ route('admin.weddings.edit', $wedding) }}"
                               class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 transition">Edit</a>

                            <form method="POST" action="{{ route('admin.weddings.archive', $wedding) }}"
                                  onsubmit="return confirm('{{ $wedding->isVaultArchived() ? 'Unarchive vault?' : 'Archive and DELETE all files? This cannot be undone.' }}')">
                                @csrf
                                <button class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium {{ $wedding->isVaultArchived() ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-red-50 text-red-700 hover:bg-red-100' }} transition">
                                    {{ $wedding->isVaultArchived() ? 'Unarchive' : 'Archive Vault' }}
                                </button>
                            </form>

                            @if($wedding->invitation)
                            <a href="{{ route('admin.digital-invitations.customize', $wedding) }}"
                               class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-purple-50 text-purple-700 hover:bg-purple-100 transition">Customize Inv.</a>
                            @endif

                            @if($wedding->allow_admin_access)
                            <a href="{{ route('impersonate.start', $wedding->user_id) }}"
                               onclick="return confirm('Access this wedding workspace?')"
                               class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-700 hover:bg-amber-100 transition">Access</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400">No weddings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($weddings->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $weddings->links() }}
    </div>
    @endif
</div>

@endsection
