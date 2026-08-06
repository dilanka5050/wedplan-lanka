@extends('admin.layout')
@section('title', 'Settings')
@section('heading', 'Settings')

@section('content')

<div class="max-w-3xl space-y-6">

    {{-- Debug Mode --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-start justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0
                    {{ $debugMode ? 'bg-amber-50' : 'bg-gray-50' }}">
                    {{ $debugMode ? '🐛' : '🛡️' }}
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm">Debug Mode</h3>
                    <p class="text-xs text-gray-500 mt-1 max-w-md">
                        When <strong>enabled</strong>, real Laravel error details are shown instead of branded error pages.
                        Disable when done so customers see the friendly error page.
                    </p>
                    <div class="mt-2">
                        @if($debugMode)
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700 bg-amber-100 px-3 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Debug mode is ON
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-green-700 bg-green-100 px-3 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            Debug mode is OFF
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.settings.debug') }}" class="flex-shrink-0">
                @csrf
                <button class="px-5 py-2.5 text-sm font-semibold rounded-lg transition
                    {{ $debugMode ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                    {{ $debugMode ? 'Disable Debug' : 'Enable Debug' }}
                </button>
            </form>
        </div>
    </div>

    {{-- Digital Invitation Templates --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="mb-5">
            <h3 class="font-semibold text-gray-900 text-sm">Digital Invitation Templates</h3>
            <p class="text-xs text-gray-500 mt-1">
                Enable or disable templates globally. A template cannot be disabled if any customer has an upcoming wedding using it.
            </p>
        </div>

        <div class="space-y-3">
            @foreach($templateStats as $key => $stat)
            @php $canDisable = !$stat['has_active']; @endphp
            <div class="flex items-center gap-4 p-4 rounded-lg border
                {{ $stat['enabled'] ? 'border-gray-200 bg-gray-50' : 'border-red-100 bg-red-50' }}">

                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-base flex-shrink-0
                    {{ $stat['enabled'] ? 'bg-white border border-gray-200' : 'bg-red-100' }}">
                    🎞️
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm font-semibold text-gray-900">{{ $stat['label'] }}</span>
                        <span class="text-xs text-gray-400 font-mono">({{ $key }})</span>
                        @if(!$stat['enabled'])
                        <span class="text-xs font-semibold text-red-600 bg-red-100 px-2 py-0.5 rounded-full">Disabled</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $stat['desc'] }}</p>
                    <div class="flex items-center gap-3 mt-1 flex-wrap">
                        @if($stat['count'] > 0)
                        <span class="text-xs text-gray-600">
                            👥 <strong>{{ $stat['count'] }}</strong> {{ $stat['count'] === 1 ? 'customer' : 'customers' }}
                        </span>
                        @if($stat['latest_date'])
                        <span class="text-xs {{ $stat['has_active'] ? 'text-amber-700 font-semibold' : 'text-gray-400' }}">
                            📅 Latest: {{ $stat['latest_date']->format('M j, Y') }}
                            <span class="{{ $stat['has_active'] ? 'text-amber-600' : 'text-gray-400' }}">
                                ({{ $stat['has_active'] ? 'upcoming' : 'past' }})
                            </span>
                        </span>
                        @endif
                        @else
                        <span class="text-xs text-gray-400">No customers using this template</span>
                        @endif
                    </div>
                </div>

                <div class="flex-shrink-0">
                    @if($stat['enabled'])
                        @if($stat['has_active'])
                        <button disabled
                                title="Cannot disable — upcoming wedding on {{ $stat['latest_date']?->format('M j, Y') }}"
                                class="px-4 py-2 text-xs font-semibold rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                            🔒 Can't Disable
                        </button>
                        @else
                        <form method="POST" action="{{ route('admin.settings.template') }}"
                              onsubmit="return confirm('Disable \'{{ addslashes($stat['label']) }}\'? Customers won\'t see it in the template picker.')">
                            @csrf
                            <input type="hidden" name="key" value="{{ $key }}">
                            <button class="px-4 py-2 text-xs font-semibold rounded-lg bg-red-100 hover:bg-red-200 text-red-700 transition">
                                Disable
                            </button>
                        </form>
                        @endif
                    @else
                    <form method="POST" action="{{ route('admin.settings.template') }}">
                        @csrf
                        <input type="hidden" name="key" value="{{ $key }}">
                        <button class="px-4 py-2 text-xs font-semibold rounded-lg bg-green-100 hover:bg-green-200 text-green-700 transition">
                            Enable
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

@endsection
