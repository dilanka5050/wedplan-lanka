@extends('layouts.app')
@section('title', 'Checklist')
@section('page-title', 'Wedding Checklist')

@section('content')

@php
    $priorityConfig = [
        'urgent' => ['label' => 'Urgent', 'bg' => 'bg-red-100',    'text' => 'text-red-600',    'dot' => 'bg-red-500'],
        'high'   => ['label' => 'High',   'bg' => 'bg-orange-100', 'text' => 'text-orange-600', 'dot' => 'bg-orange-400'],
        'medium' => ['label' => 'Medium', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-600', 'dot' => 'bg-yellow-400'],
        'low'    => ['label' => 'Low',    'bg' => 'bg-green-100',  'text' => 'text-green-600',  'dot' => 'bg-green-400'],
    ];
    $grouped = $tasks->groupBy(fn($t) => $t->category?->name ?? 'General');
    $total     = $tasks->count();
    $completed = $tasks->where('status.value', 'completed')->count();
    $progress  = $total > 0 ? round(($completed / $total) * 100) : 0;
@endphp

{{-- Progress bar --}}
<div class="bg-white rounded-2xl border border-gray-100 card-shadow px-6 py-4 mb-6">
    <div class="flex items-center justify-between mb-2">
        <p class="text-sm font-semibold text-gray-700">Overall Progress</p>
        <p class="text-sm font-black text-gray-900">{{ $completed }} / {{ $total }} tasks</p>
    </div>
    <div class="w-full bg-gray-100 rounded-full h-2.5">
        <div class="h-2.5 rounded-full bg-gradient-to-r from-pink-500 to-purple-500 transition-all duration-700"
             style="width: {{ $progress }}%"></div>
    </div>
    <p class="text-xs text-gray-400 mt-1.5">{{ $progress }}% complete</p>
</div>

{{-- Add Task Card --}}
<div class="bg-white rounded-2xl border border-gray-100 card-shadow mb-6" x-data="{ open: false }">
    <div class="flex items-center justify-between px-6 py-4">
        <h3 class="font-bold text-gray-900">Tasks</h3>
        <button @click="open = !open"
                class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-pink-600 to-purple-600 text-white text-sm font-semibold rounded-full shadow-md hover:shadow-lg transition">
            <span x-text="open ? '✕ Cancel' : '+ Add Task'"></span>
        </button>
    </div>

    <div x-show="open" x-cloak class="border-t border-gray-100 px-6 py-5">
        <form method="POST" action="{{ route('tasks.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            @csrf
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Task Title <span class="text-red-400">*</span></label>
                <input type="text" name="title" placeholder="e.g. Book Poruwa decorator" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Category</label>
                <select name="task_category_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                    <option value="">No category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Priority</label>
                <select name="priority" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                    <option value="medium">🟡 Medium</option>
                    <option value="low">🟢 Low</option>
                    <option value="high">🟠 High</option>
                    <option value="urgent">🔴 Urgent</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Due Date</label>
                <input type="date" name="due_date" min="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
            </div>
            <div class="md:col-span-3 flex justify-end items-end gap-3">
                <button type="button" @click="open = false"
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 border border-gray-200 rounded-full hover:bg-gray-50 transition">Cancel</button>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow-md hover:shadow-lg transition">
                    Add Task
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Info banner --}}
@if($tasks->count() > 0)
<div class="mb-4 flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
    <span class="text-base mt-0.5">💡</span>
    <p class="text-xs text-blue-700 leading-relaxed">
        <span class="font-semibold">Click the priority badge</span> on any task to change it to Low, Medium, High, or Urgent.
        Click the <span class="font-semibold">checkbox</span> to mark a task complete.
    </p>
</div>
@endif

{{-- Tasks grouped by category --}}
@forelse($grouped as $categoryName => $categoryTasks)
<div class="bg-white rounded-2xl border border-gray-100 card-shadow mb-4 overflow-hidden">

    {{-- Category header --}}
    <div class="flex items-center justify-between px-6 py-3 bg-gray-50 border-b border-gray-100">
        <div class="flex items-center gap-2">
            <span class="text-base">{{ $categoryTasks->first()->category?->icon ?? '📋' }}</span>
            <p class="text-sm font-bold text-gray-700">{{ $categoryName }}</p>
            <span class="text-xs text-gray-400 font-medium">
                ({{ $categoryTasks->where('status.value', 'completed')->count() }}/{{ $categoryTasks->count() }})
            </span>
        </div>
    </div>

    {{-- Task rows --}}
    <div class="divide-y divide-gray-50">
        @foreach($categoryTasks as $task)
        @php $p = $priorityConfig[$task->priority->value] ?? $priorityConfig['medium']; @endphp
        <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50/50 transition"
             x-data="{ editingPriority: false }">

            {{-- Checkbox --}}
            <form method="POST" action="{{ route('tasks.update', $task) }}" class="flex-shrink-0">
                @csrf @method('PUT')
                <input type="hidden" name="status"
                       value="{{ $task->status->value === 'completed' ? 'pending' : 'completed' }}">
                <button type="submit"
                        class="w-6 h-6 rounded-lg border-2 flex items-center justify-center transition
                               {{ $task->status->value === 'completed'
                                  ? 'bg-green-500 border-green-500 text-white shadow-sm'
                                  : 'border-gray-300 hover:border-pink-400' }}">
                    @if($task->status->value === 'completed')
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                    @endif
                </button>
            </form>

            {{-- Title + meta --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium {{ $task->status->value === 'completed' ? 'line-through text-gray-400' : 'text-gray-800' }} truncate">
                    {{ $task->title }}
                </p>
                @if($task->due_date)
                <p class="text-xs mt-0.5 {{ $task->due_date->isPast() && $task->status->value !== 'completed' ? 'text-red-400 font-semibold' : 'text-gray-400' }}">
                    📅 {{ $task->due_date->format('d M Y') }}
                    @if($task->due_date->isPast() && $task->status->value !== 'completed') · overdue @endif
                </p>
                @endif
            </div>

            {{-- Priority badge — click to edit --}}
            <div class="flex-shrink-0 relative">
                {{-- View: clickable badge --}}
                <button type="button" x-show="!editingPriority"
                        @click="editingPriority = true"
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold transition hover:opacity-80 {{ $p['bg'] }} {{ $p['text'] }}"
                        title="Click to change priority">
                    <span class="w-1.5 h-1.5 rounded-full {{ $p['dot'] }}"></span>
                    {{ $p['label'] }}
                    <svg class="w-2.5 h-2.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Edit: priority dropdown + tick + cancel --}}
                <form x-show="editingPriority" x-cloak method="POST"
                      action="{{ route('tasks.update', $task) }}"
                      class="flex items-center gap-1">
                    @csrf @method('PUT')
                    <select name="priority" autofocus
                            class="text-xs border border-pink-300 bg-pink-50 rounded-lg px-2 py-1.5 font-semibold focus:outline-none focus:ring-2 focus:ring-pink-300">
                        @foreach($priorityConfig as $val => $cfg)
                        <option value="{{ $val }}" {{ $task->priority->value === $val ? 'selected' : '' }}>
                            {{ $cfg['label'] }}
                        </option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="w-7 h-7 rounded-lg bg-green-500 hover:bg-green-600 text-white flex items-center justify-center transition shadow-sm"
                            title="Save">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                    <button type="button" @click="editingPriority = false"
                            class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition"
                            title="Cancel">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Delete --}}
            <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="flex-shrink-0">
                @csrf @method('DELETE')
                <button type="submit"
                        onclick="return confirm('Delete this task?')"
                        class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-300 hover:text-red-400 hover:bg-red-50 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@empty
<div class="bg-white rounded-2xl border border-gray-100 card-shadow p-16 text-center">
    <p class="text-5xl mb-4">✅</p>
    <p class="text-base font-semibold text-gray-700">No tasks yet</p>
    <p class="text-sm text-gray-400 mt-1">Click "Add Task" above to start building your wedding checklist.</p>
</div>
@endforelse

@endsection
