@extends('layouts.guest')
@section('title', 'Register')

@section('content')
<div class="bg-white rounded-3xl shadow-xl shadow-pink-100/50 border border-pink-50 p-8">

    <div class="mb-7">
        <h2 class="playfair text-2xl font-bold text-gray-900">Create Your Account</h2>
        <p class="text-sm text-gray-400 mt-1">Start planning your perfect Sri Lankan wedding — free</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Full Name <span class="text-red-400">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   placeholder="e.g. Sanduni Perera"
                   class="w-full px-4 py-3 border {{ $errors->has('name') ? 'border-red-300 bg-red-50' : 'border-gray-200' }} rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email Address <span class="text-red-400">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   placeholder="you@example.com"
                   class="w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-300 bg-red-50' : 'border-gray-200' }} rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition">
            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Phone Number <span class="text-red-400">*</span></label>
            <x-phone-input name="phone" :value="old('phone')" :required="true" :error="$errors->has('phone')" />
            @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div x-data="{ show: false }">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Password <span class="text-red-400">*</span></label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" name="password" required
                       class="w-full px-4 py-3 pr-11 border {{ $errors->has('password') ? 'border-red-300 bg-red-50' : 'border-gray-200' }} rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition">
                <button type="button" @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-500 transition">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div x-data="{ show: false }">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Confirm Password <span class="text-red-400">*</span></label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                       class="w-full px-4 py-3 pr-11 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition">
                <button type="button" @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-500 transition">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
        </div>

        <button type="submit"
                class="w-full py-3 btn-gradient text-white font-semibold rounded-xl shadow-lg shadow-pink-200 hover:shadow-pink-300 transition text-sm">
            ✨ Create My Account
        </button>

        <p class="text-xs text-gray-400 text-center">
            By registering you agree to our terms of service.
        </p>
    </form>

    <div class="mt-6 pt-6 border-t border-gray-50 text-center">
        <p class="text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}" class="text-pink-600 font-semibold hover:text-pink-700 transition">Sign in</a>
        </p>
    </div>
</div>
@endsection
