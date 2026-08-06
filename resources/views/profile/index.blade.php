@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- ===== ACCOUNT INFO ===== --}}
    <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-6">

        {{-- Header --}}
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 btn-gradient rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-md flex-shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $user->email }}</p>
                <span class="inline-block mt-1 text-xs font-semibold px-2.5 py-0.5 rounded-full
                    {{ $user->isPro() ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $user->isPro() ? '⭐ Pro Plan' : 'Free Plan' }}
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update-info') }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Full Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-2.5 border {{ $errors->has('name') ? 'border-red-300' : 'border-gray-200' }} rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email Address <span class="text-red-400">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-4 py-2.5 border {{ $errors->has('email') ? 'border-red-300' : 'border-gray-200' }} rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Phone <span class="text-gray-400 font-normal">(optional)</span></label>
                <x-phone-input name="phone" :value="old('phone', $user->phone)" :error="$errors->has('phone')" />
                @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="pt-1">
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow-md hover:shadow-lg transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- ===== CHANGE PASSWORD ===== --}}
    <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-6" id="password">

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-xl flex-shrink-0">🔒</div>
            <div>
                <h2 class="font-bold text-gray-900">Change Password</h2>
                <p class="text-xs text-gray-400 mt-0.5">Use a strong password you don't use elsewhere</p>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update-password') }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Current Password <span class="text-red-400">*</span></label>
                <input type="password" name="current_password" required
                       class="w-full px-4 py-2.5 border {{ $errors->has('current_password') ? 'border-red-300' : 'border-gray-200' }} rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">New Password <span class="text-red-400">*</span></label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2.5 border {{ $errors->has('password') ? 'border-red-300' : 'border-gray-200' }} rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Confirm New Password <span class="text-red-400">*</span></label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
            </div>

            <div class="pt-1">
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow-md hover:shadow-lg transition">
                    Update Password
                </button>
            </div>
        </form>
    </div>

    {{-- ===== ACCOUNT INFO (read-only) ===== --}}
    <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-xl flex-shrink-0">ℹ️</div>
            <h2 class="font-bold text-gray-900">Account Details</h2>
        </div>
        <div class="space-y-3">
            <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                <span class="text-xs font-semibold text-gray-500">Member Since</span>
                <span class="text-sm text-gray-700">{{ $user->created_at->format('d M Y') }}</span>
            </div>
            <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                <span class="text-xs font-semibold text-gray-500">Current Plan</span>
                <span class="text-sm font-semibold {{ $user->isPro() ? 'text-amber-600' : 'text-gray-600' }}">
                    {{ $user->isPro() ? '⭐ Pro' : 'Free' }}
                </span>
            </div>
            <div class="flex items-center justify-between py-2.5">
                <span class="text-xs font-semibold text-gray-500">Wedding Workspace</span>
                <span class="text-sm text-gray-700">
                    {{ $user->wedding ? $user->wedding->bride_name . ' & ' . $user->wedding->groom_name : 'Not set up yet' }}
                </span>
            </div>

        </div>
    </div>

</div>
@endsection
