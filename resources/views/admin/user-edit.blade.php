@extends('admin.layout')
@section('title', 'Edit User')
@section('heading', 'Edit User')

@section('content')

<div class="max-w-lg">
    <a href="{{ route('admin.users') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">← Back to Users</a>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-semibold text-gray-800 mb-5">{{ $user->name }}</h2>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">New Password <span class="text-gray-400 font-normal">(leave blank to keep current)</span></label>
                <input type="password" name="password"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
                @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-pink-600 rounded-xl hover:bg-pink-700 transition">
                    Save Changes
                </button>
                <a href="{{ route('admin.users') }}"
                   class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
