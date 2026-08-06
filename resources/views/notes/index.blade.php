@extends('layouts.app')
@section('title', 'Notes')
@section('page-title', 'Wedding Notes')

@section('content')

{{-- Header + Add button --}}
<div class="bg-white rounded-2xl border border-gray-100 card-shadow mb-6" x-data="{ open: false }">
    <div class="flex items-center justify-between px-6 py-4">
        <div>
            <h3 class="font-bold text-gray-900">My Notes</h3>
            <p class="text-xs text-gray-400 mt-0.5">Vendor contacts, ideas, reminders — anything you need to remember</p>
        </div>
        <button @click="open = !open"
                class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-pink-600 to-purple-600 text-white text-sm font-semibold rounded-full shadow-md hover:shadow-lg transition">
            <span x-text="open ? '✕ Cancel' : '+ Add Note'"></span>
        </button>
    </div>

    {{-- Add form --}}
    <div x-show="open" x-cloak class="border-t border-gray-100 px-6 py-5">
        <form method="POST" action="{{ route('notes.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Title <span class="text-red-400">*</span></label>
                <input type="text" name="title" placeholder="e.g. Photographer contact, Venue deposit reminder"
                       required maxlength="255"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Content</label>
                <textarea name="content" rows="4" maxlength="5000"
                          placeholder="Write your note here..."
                          class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none resize-none"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" @click="open = false"
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 border border-gray-200 rounded-full hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow-md hover:shadow-lg transition">
                    Save Note
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Notes grid --}}
@if($notes->isEmpty())
<div class="bg-white rounded-2xl border border-gray-100 card-shadow p-16 text-center">
    <p class="text-5xl mb-4">📝</p>
    <p class="text-base font-semibold text-gray-700">No notes yet</p>
    <p class="text-sm text-gray-400 mt-1">Click "Add Note" to jot down vendor contacts, ideas, or reminders.</p>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    @foreach($notes as $note)
    <div class="bg-white rounded-2xl border border-gray-100 card-shadow flex flex-col"
         x-data="{ editing: false }">

        {{-- View state --}}
        <div x-show="!editing" class="flex flex-col flex-1 p-5">
            <div class="flex items-start justify-between gap-3 mb-3">
                <h4 class="font-bold text-gray-900 leading-snug flex-1">{{ $note->title }}</h4>
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button @click="editing = true"
                            class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-300 hover:text-pink-500 hover:bg-pink-50 transition"
                            title="Edit">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/>
                        </svg>
                    </button>
                    <form method="POST" action="{{ route('notes.destroy', $note) }}"
                          onsubmit="return confirm('Delete this note?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-300 hover:text-red-400 hover:bg-red-50 transition"
                                title="Delete">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            @if($note->content)
            <p class="text-sm text-gray-600 leading-relaxed flex-1 whitespace-pre-wrap">{{ $note->content }}</p>
            @else
            <p class="text-sm text-gray-300 italic flex-1">No content</p>
            @endif

            <p class="text-xs text-gray-300 mt-4 pt-3 border-t border-gray-50">
                {{ $note->updated_at->diffForHumans() }}
            </p>
        </div>

        {{-- Edit state --}}
        <div x-show="editing" x-cloak class="p-5 bg-pink-50/30 rounded-2xl flex-1">
            <form method="POST" action="{{ route('notes.update', $note) }}" class="space-y-3 flex flex-col h-full">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Title</label>
                    <input type="text" name="title" value="{{ $note->title }}" required maxlength="255"
                           class="w-full px-3 py-2 border border-pink-300 bg-white rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Content</label>
                    <textarea name="content" rows="5" maxlength="5000"
                              class="w-full px-3 py-2 border border-pink-300 bg-white rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none resize-none">{{ $note->content }}</textarea>
                </div>
                <div class="flex gap-2 pt-1">
                    <button type="button"
                            @click="editing = false"
                            class="flex-1 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-full hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 py-2 text-xs font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow hover:shadow-md transition">
                        Save
                    </button>
                </div>
            </form>
        </div>

    </div>
    @endforeach
</div>
@endif

@endsection
