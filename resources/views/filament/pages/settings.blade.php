<x-filament-panels::page>

    <div class="space-y-6">

        {{-- Debug Mode Card --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 p-6">
            <div class="flex items-start justify-between gap-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0
                        {{ $this->debug_mode ? 'bg-amber-50' : 'bg-gray-50' }}">
                        {{ $this->debug_mode ? '🐛' : '🛡️' }}
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Debug Mode</h3>
                        <p class="text-sm text-gray-500 mt-1 max-w-lg">
                            When <strong>enabled</strong>, real Laravel error details are shown instead of the branded error pages.
                            Use this to diagnose issues, then <strong>disable</strong> it when done so customers see the friendly error page.
                        </p>
                        <div class="mt-3">
                            @if($this->debug_mode)
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700 bg-amber-100 px-3 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Debug mode is ON — real errors visible
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-green-700 bg-green-100 px-3 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                Debug mode is OFF — branded error pages active
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <button
                        wire:click="toggleDebug"
                        class="px-5 py-2.5 text-sm font-semibold rounded-lg transition
                            {{ $this->debug_mode
                                ? 'bg-amber-500 hover:bg-amber-600 text-white'
                                : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                        {{ $this->debug_mode ? 'Disable Debug' : 'Enable Debug' }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Digital Invitation Templates --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 p-6">
            <div class="mb-5">
                <h3 class="text-base font-semibold text-gray-900">Digital Invitation Templates</h3>
                <p class="text-sm text-gray-500 mt-1">
                    Enable or disable templates globally. A template cannot be disabled if any customer has an upcoming wedding using it.
                </p>
            </div>

            <div class="space-y-3">
                @foreach($this->templateStats as $key => $stat)
                @php
                    $canDisable = !$stat['has_active'];
                    $isEnabled  = $stat['enabled'];
                @endphp
                <div class="flex items-center gap-4 p-4 rounded-lg border
                    {{ $isEnabled ? 'border-gray-200 bg-gray-50' : 'border-red-100 bg-red-50' }}">

                    {{-- Template identity --}}
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-lg flex-shrink-0
                        {{ $isEnabled ? 'bg-white border border-gray-200' : 'bg-red-100' }}">
                        🎞️
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-semibold text-gray-900">{{ $stat['label'] }}</span>
                            <span class="text-xs text-gray-400 font-mono">({{ $key }})</span>
                            @if(!$isEnabled)
                            <span class="text-xs font-semibold text-red-600 bg-red-100 px-2 py-0.5 rounded-full">Disabled</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $stat['desc'] }}</p>

                        {{-- Usage info --}}
                        <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                            @if($stat['count'] > 0)
                                <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                                    👥 <strong>{{ $stat['count'] }}</strong> {{ $stat['count'] === 1 ? 'customer' : 'customers' }}
                                </span>
                                @if($stat['latest_date'])
                                    <span class="inline-flex items-center gap-1 text-xs
                                        {{ $stat['has_active'] ? 'text-amber-700 font-semibold' : 'text-gray-400' }}">
                                        📅 Latest wedding: {{ $stat['latest_date']->format('M j, Y') }}
                                        @if($stat['has_active'])
                                            <span class="text-amber-600">(upcoming)</span>
                                        @else
                                            <span class="text-gray-400">(past)</span>
                                        @endif
                                    </span>
                                @endif
                            @else
                                <span class="text-xs text-gray-400">No customers using this template</span>
                            @endif
                        </div>
                    </div>

                    {{-- Toggle button --}}
                    <div class="flex-shrink-0">
                        @if($isEnabled)
                            @if($stat['has_active'])
                            <button disabled
                                title="Cannot disable — {{ $stat['count'] }} customer(s) have an upcoming wedding on {{ $stat['latest_date']?->format('M j, Y') }}"
                                class="px-4 py-2 text-xs font-semibold rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                                🔒 Can't Disable
                            </button>
                            @else
                            <button
                                wire:click="toggleTemplate('{{ $key }}')"
                                wire:confirm="Disable '{{ $stat['label'] }}'? Customers won't see it in the template picker."
                                class="px-4 py-2 text-xs font-semibold rounded-lg bg-red-100 hover:bg-red-200 text-red-700 transition">
                                Disable
                            </button>
                            @endif
                        @else
                            <button
                                wire:click="toggleTemplate('{{ $key }}')"
                                class="px-4 py-2 text-xs font-semibold rounded-lg bg-green-100 hover:bg-green-200 text-green-700 transition">
                                Enable
                            </button>
                        @endif
                    </div>

                </div>
                @endforeach
            </div>
        </div>

    </div>

</x-filament-panels::page>
