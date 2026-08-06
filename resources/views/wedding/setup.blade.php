@extends('layouts.guest')
@section('title', 'Setup Wedding')
@section('content')
<div class="bg-white rounded-2xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Setup Your Wedding 💒</h2>
    <p class="text-gray-500 mb-6">Tell us about your special day</p>
    <form method="POST" action="{{ route('wedding.store') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bride's Name</label>
                <input type="text" name="bride_name" value="{{ old('bride_name') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                @error('bride_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Groom's Name</label>
                <input type="text" name="groom_name" value="{{ old('groom_name') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                @error('groom_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Wedding Type</label>
            <select name="wedding_type" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                <option value="sinhala">Sinhala</option>
                <option value="tamil">Tamil</option>
                <option value="muslim">Muslim</option>
                <option value="christian">Christian</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Wedding Date</label>
            <input type="date" name="wedding_date" value="{{ old('wedding_date') }}" min="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
            <input type="text" name="venue" value="{{ old('venue') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none" placeholder="e.g., Cinnamon Grand, Colombo">
        </div>
        <button type="submit" class="w-full py-2.5 bg-pink-600 text-white font-medium rounded-lg hover:bg-pink-700 transition">Create Wedding Workspace</button>
    </form>
</div>
@endsection
