@extends('admin.layout')
@section('title', 'Users')
@section('heading', 'Users')

@section('content')

{{-- Search --}}
<form method="GET" class="mb-4 flex gap-2">
    <input type="text" name="search" value="{{ $search }}" placeholder="Search name or email…"
           class="flex-1 max-w-sm px-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
    <button class="px-4 py-2 text-sm bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition">Search</button>
    @if($search)
    <a href="{{ route('admin.users') }}" class="px-4 py-2 text-sm bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">Clear</a>
    @endif
</form>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Name</th>
                    <th class="px-5 py-3 text-left">Email</th>
                    <th class="px-5 py-3 text-left">Phone</th>
                    <th class="px-5 py-3 text-left">Plan</th>
                    <th class="px-5 py-3 text-left">Wedding</th>
                    <th class="px-5 py-3 text-left">Wedding Date</th>
                    <th class="px-5 py-3 text-left">Joined</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50" x-data="{ open: false }">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $user->email }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $user->phone ?? '—' }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                            {{ $user->isPro() ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $user->activeSubscription?->plan?->name ?? 'Free' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">
                        {{ $user->wedding ? $user->wedding->bride_name.' & '.$user->wedding->groom_name : '—' }}
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">
                        {{ $user->wedding?->wedding_date?->format('d M Y') ?? '—' }}
                    </td>
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 transition">Edit</a>

                            <form method="POST" action="{{ route('admin.users.reset-password', $user) }}"
                                  onsubmit="return confirm('Reset password for {{ addslashes($user->name) }}?')">
                                @csrf
                                <button class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-700 hover:bg-amber-100 transition">Reset PW</button>
                            </form>

                            @if($user->isPro())
                            <form method="POST" action="{{ route('admin.users.upgrade', $user) }}"
                                  onsubmit="return confirm('Downgrade {{ addslashes($user->name) }} to Free?')">
                                @csrf
                                <input type="hidden" name="plan" value="free">
                                <button class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-red-50 text-red-700 hover:bg-red-100 transition">→ Free</button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('admin.users.upgrade', $user) }}"
                                  onsubmit="return confirm('Upgrade {{ addslashes($user->name) }} to Pro?')">
                                @csrf
                                <input type="hidden" name="plan" value="pro">
                                <button class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-green-50 text-green-700 hover:bg-green-100 transition">→ Pro</button>
                            </form>
                            @endif

                            @if($user->wedding?->allow_admin_access)
                            <a href="{{ route('impersonate.start', $user->id) }}"
                               onclick="return confirm('Access {{ addslashes($user->name) }}\'s wedding workspace?')"
                               class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-purple-50 text-purple-700 hover:bg-purple-100 transition">Access</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
