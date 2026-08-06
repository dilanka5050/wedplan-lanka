@extends('layouts.app')
@section('title', 'Wedding Settings')
@section('page-title', 'Wedding Settings')

@section('content')
<div class="max-w-2xl"
     x-data="{
        currentType: '{{ $wedding->wedding_type->value }}',
        selectedType: '{{ $wedding->wedding_type->value }}',
        wipeMode: null,
        showModal: false,
        typeLabels: { sinhala: 'Sinhala', tamil: 'Tamil', muslim: 'Muslim', christian: 'Christian' },
        onTypeChange(val) {
            this.selectedType = val;
            if (val !== this.currentType) {
                this.wipeMode = null;
                this.showModal = true;
            }
        },
        cancelTypeChange() {
            this.selectedType = this.currentType;
            this.showModal = false;
            this.wipeMode = null;
            // Reset the select element visually
            document.getElementById('wedding_type_select').value = this.currentType;
        }
     }">

    {{-- Confirmation Modal --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8" @click.stop>

            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">⚠️</div>
                <h3 class="text-lg font-bold text-gray-900">Update Wedding Checklist?</h3>
                <p class="text-sm text-gray-500 mt-2">
                    You're changing from
                    <span class="font-semibold text-pink-600" x-text="typeLabels[currentType]"></span>
                    to
                    <span class="font-semibold text-pink-600" x-text="typeLabels[selectedType]"></span>.
                    <br>How should we update your checklist?
                </p>
            </div>

            <div class="space-y-3 mb-6">
                {{-- Option: Wipe defaults only --}}
                <label class="cursor-pointer block">
                    <input type="radio" x-model="wipeMode" value="defaults" class="sr-only peer">
                    <div class="border-2 rounded-2xl p-4 transition peer-checked:border-pink-500 peer-checked:bg-pink-50 border-gray-200 hover:border-pink-300">
                        <div class="flex items-start gap-3">
                            <span class="text-xl mt-0.5">🔄</span>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Replace default tasks only</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Removes the pre-built checklist tasks and adds new ones for
                                    <span class="font-medium" x-text="typeLabels[selectedType]"></span>.
                                    Your custom tasks are kept.
                                </p>
                            </div>
                        </div>
                    </div>
                </label>

                {{-- Option: Wipe everything --}}
                <label class="cursor-pointer block">
                    <input type="radio" x-model="wipeMode" value="all" class="sr-only peer">
                    <div class="border-2 rounded-2xl p-4 transition peer-checked:border-red-400 peer-checked:bg-red-50 border-gray-200 hover:border-red-300">
                        <div class="flex items-start gap-3">
                            <span class="text-xl mt-0.5">🗑️</span>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Clear entire checklist</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Removes <span class="font-medium text-red-600">all tasks</span> including your custom ones,
                                    then adds a fresh checklist for
                                    <span class="font-medium" x-text="typeLabels[selectedType]"></span>.
                                </p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="button" @click="cancelTypeChange()"
                        class="flex-1 py-2.5 text-sm font-medium text-gray-600 border border-gray-200 rounded-full hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="button"
                        :disabled="!wipeMode"
                        @click="showModal = false"
                        :class="wipeMode ? 'bg-gradient-to-r from-pink-600 to-purple-600 text-white shadow-md hover:shadow-lg' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                        class="flex-1 py-2.5 text-sm font-semibold rounded-full transition">
                    Confirm & Save
                </button>
            </div>
        </div>
    </div>

    {{-- Settings Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-6">
        <h2 class="font-bold text-gray-900 mb-5">Wedding Details</h2>

        <form method="POST" action="{{ route('wedding.update') }}" class="space-y-4">
            @csrf @method('PUT')

            {{-- Hidden wipe_mode — set by Alpine when user confirms --}}
            <input type="hidden" name="wipe_mode" :value="wipeMode">

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
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Wedding Type</label>
                <select id="wedding_type_select" name="wedding_type"
                        @change="onTypeChange($event.target.value)"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                    @foreach(['sinhala','tamil','muslim','christian'] as $type)
                        <option value="{{ $type }}" {{ $wedding->wedding_type->value === $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Changing this will prompt you to update your checklist.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Wedding Date</label>
                <input type="date" name="wedding_date" value="{{ old('wedding_date', $wedding->wedding_date?->format('Y-m-d')) }}"
                       min="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Venue</label>
                <input type="text" name="venue" value="{{ old('venue', $wedding->venue) }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none"
                       placeholder="e.g. Cinnamon Grand, Colombo">
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow-md hover:shadow-lg transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Support Access Section --}}
    <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-6 mt-6">
        <div class="flex items-start gap-3 mb-5">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-xl flex-shrink-0">🔑</div>
            <div>
                <h2 class="font-bold text-gray-900">Support Access</h2>
                <p class="text-xs text-gray-400 mt-0.5">Control whether your wedding planner can access your workspace</p>
            </div>
        </div>

        <form method="POST" action="{{ route('wedding.update') }}" class="space-y-4">
            @csrf @method('PUT')
            {{-- Pass current values so the main form fields aren't wiped --}}
            <input type="hidden" name="bride_name" value="{{ $wedding->bride_name }}">
            <input type="hidden" name="groom_name" value="{{ $wedding->groom_name }}">
            <input type="hidden" name="wedding_type" value="{{ $wedding->wedding_type->value }}">
            <input type="hidden" name="wedding_date" value="{{ $wedding->wedding_date?->format('Y-m-d') }}">
            <input type="hidden" name="venue" value="{{ $wedding->venue }}">

            {{-- Toggle --}}
            <div class="flex items-center justify-between p-4 rounded-xl border
                {{ $wedding->allow_admin_access ? 'bg-amber-50 border-amber-200' : 'bg-gray-50 border-gray-200' }}">
                <div class="flex-1">
                    <p class="text-sm font-semibold {{ $wedding->allow_admin_access ? 'text-amber-800' : 'text-gray-700' }}">
                        {{ $wedding->allow_admin_access ? '🔓 Admin access is currently enabled' : '🔒 Admin access is disabled' }}
                    </p>
                    <p class="text-xs mt-1 {{ $wedding->allow_admin_access ? 'text-amber-600' : 'text-gray-400' }}">
                        {{ $wedding->allow_admin_access
                            ? 'Your wedding planner can view and edit your wedding data on your behalf.'
                            : 'Only you can access your wedding workspace. Enable this if you need help from your planner.' }}
                    </p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer ml-4">
                    <input type="hidden" name="allow_admin_access" value="0">
                    <input type="checkbox" name="allow_admin_access" value="1" class="sr-only peer"
                           {{ $wedding->allow_admin_access ? 'checked' : '' }}
                           onchange="document.getElementById('loading-overlay').classList.remove('hidden'); this.form.submit()">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer
                                peer-checked:after:translate-x-full peer-checked:after:border-white
                                after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                after:bg-white after:border-gray-300 after:border after:rounded-full
                                after:h-5 after:w-5 after:transition-all
                                peer-checked:bg-amber-500"></div>
                </label>
            </div>

            {{-- Audit trail --}}
            @if($wedding->last_accessed_at)
            <div class="flex items-center gap-3 px-4 py-3 bg-blue-50 border border-blue-100 rounded-xl">
                <span class="text-base">📋</span>
                <p class="text-xs text-blue-700">
                    <span class="font-semibold">Last accessed</span> by
                    <span class="font-semibold">{{ $wedding->lastAccessedBy?->name ?? 'Admin' }}</span>
                    on {{ $wedding->last_accessed_at->format('d M Y') }} at {{ $wedding->last_accessed_at->format('h:i A') }}
                </p>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection
