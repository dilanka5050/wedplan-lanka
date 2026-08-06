@extends('layouts.guest')
@section('title', 'Login')

@section('content')
<div class="bg-white rounded-3xl shadow-xl shadow-pink-100/50 border border-pink-50 p-8">

    @if(session('error'))
    <div class="flex items-start gap-2.5 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-6">
        <span class="text-base flex-shrink-0">⏳</span>
        <p class="text-xs text-amber-700 leading-relaxed font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <div class="mb-7">
        <h2 class="playfair text-2xl font-bold text-gray-900">Welcome Back</h2>
        <p class="text-sm text-gray-400 mt-1">Sign in to continue planning your wedding</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-300 bg-red-50' : 'border-gray-200' }} rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition">
            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Password</label>
            <input type="password" name="password" required
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none transition">
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-pink-600 focus:ring-pink-200">
                <span class="text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <button type="submit"
                class="w-full py-3 btn-gradient text-white font-semibold rounded-xl shadow-lg shadow-pink-200 hover:shadow-pink-300 transition text-sm">
            Sign In
        </button>

        {{-- Forgot password notice --}}
        <div class="flex items-start gap-2.5 bg-amber-50 border border-amber-100 rounded-xl px-4 py-3 mt-2">
            <span class="text-base flex-shrink-0 mt-0.5">🔒</span>
            <p class="text-xs text-amber-700 leading-relaxed">
                <span class="font-semibold">Forgot your password?</span>
                Contact us on
                <a href="tel:{{ config('app.support_phone') }}" class="font-bold text-amber-800 hover:underline">{{ config('app.support_phone') }}</a>
                and we'll reset it for you.
            </p>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-gray-50 text-center">
        <p class="text-sm text-gray-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-pink-600 font-semibold hover:text-pink-700 transition">Create one free</a>
        </p>
    </div>
</div>
@endsection
