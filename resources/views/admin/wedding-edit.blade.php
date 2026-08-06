@extends('admin.layout')
@section('title', 'Edit Wedding')
@section('heading', 'Edit Wedding')

@section('content')

<div class="max-w-lg">
    <a href="{{ route('admin.weddings') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">← Back to Weddings</a>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-4">
        <h2 class="font-semibold text-gray-800 mb-5">{{ $wedding->bride_name }} & {{ $wedding->groom_name }}</h2>

        <form method="POST" action="{{ route('admin.weddings.update', $wedding) }}" class="space-y-4">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Bride's Name</label>
                    <input type="text" name="bride_name" value="{{ old('bride_name', $wedding->bride_name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Groom's Name</label>
                    <input type="text" name="groom_name" value="{{ old('groom_name', $wedding->groom_name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Wedding Date</label>
                <input type="date" name="wedding_date" value="{{ old('wedding_date', $wedding->wedding_date?->format('Y-m-d')) }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Venue</label>
                <input type="text" name="venue" value="{{ old('venue', $wedding->venue) }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                    Custom Upload Limit
                    <span class="text-gray-400 font-normal">(leave blank to use plan default)</span>
                </label>
                <input type="number" name="custom_upload_limit" min="1" max="9999"
                       value="{{ old('custom_upload_limit', $wedding->custom_upload_limit) }}"
                       placeholder="e.g. 200"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="allow_admin_access" id="allow_admin_access" value="1"
                       {{ old('allow_admin_access', $wedding->allow_admin_access) ? 'checked' : '' }}
                       class="w-4 h-4 text-pink-600 rounded">
                <label for="allow_admin_access" class="text-sm text-gray-700">Allow admin access to this wedding</label>
            </div>

            @if($wedding->last_accessed_at)
            <p class="text-xs text-gray-400">
                Last accessed by {{ $wedding->lastAccessedBy?->name ?? '?' }}
                on {{ $wedding->last_accessed_at->format('d M Y \a\t h:i A') }}
            </p>
            @endif

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-pink-600 rounded-xl hover:bg-pink-700 transition">
                    Save Changes
                </button>
                <a href="{{ route('admin.weddings') }}"
                   class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- Storage limit quick-set --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 text-sm mb-4">Quick Storage Override</h3>
        <form method="POST" action="{{ route('admin.weddings.storage', $wedding) }}" class="flex gap-3">
            @csrf
            <input type="number" name="custom_upload_limit" min="1" max="9999"
                   value="{{ $wedding->custom_upload_limit }}"
                   placeholder="New limit"
                   class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
            <button class="px-5 py-2.5 text-sm font-semibold text-white bg-gray-800 rounded-xl hover:bg-gray-900 transition">
                Set Limit
            </button>
        </form>
        <p class="text-xs text-gray-400 mt-2">
            Current: {{ $wedding->files->count() }} files used /
            {{ $wedding->custom_upload_limit ?? $wedding->user->activeSubscription?->plan?->max_uploads ?? '?' }} limit
            @if($wedding->custom_upload_limit)<span class="text-amber-500">★ custom</span>@endif
        </p>
    </div>
</div>

@endsection
