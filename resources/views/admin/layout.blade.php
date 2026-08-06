<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Admin') — WedPlan Lanka</title>
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak]{display:none}</style>
</head>
<body class="bg-gray-100 font-sans" x-data="{ sidebarOpen: false }">

{{-- Mobile overlay --}}
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false"
     class="fixed inset-0 bg-black/40 z-20 lg:hidden"></div>

{{-- Sidebar --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed top-0 left-0 h-full w-60 bg-gray-900 z-30 flex flex-col transition-transform duration-200 lg:translate-x-0">

    <div class="px-5 py-4 border-b border-gray-700">
        <p class="text-white font-bold text-sm">WedPlan Lanka</p>
        <p class="text-gray-400 text-xs mt-0.5">Admin Panel</p>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        @php
            $nav = [
                ['route' => 'admin.dashboard', 'icon' => '📊', 'label' => 'Dashboard'],
                ['route' => 'admin.users',     'icon' => '👥', 'label' => 'Users'],
                ['route' => 'admin.weddings',  'icon' => '💍', 'label' => 'Weddings'],
                ['route' => 'admin.settings',  'icon' => '⚙️',  'label' => 'Settings'],
            ];
        @endphp
        @foreach($nav as $item)
        <a href="{{ route($item['route']) }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
               {{ request()->routeIs($item['route']) ? 'bg-pink-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            <span>{{ $item['icon'] }}</span>
            <span>{{ $item['label'] }}</span>
        </a>
        @endforeach
    </nav>

    <div class="px-3 py-4 border-t border-gray-700 space-y-1">
        <a href="{{ route('dashboard') }}" target="_blank"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-400 hover:bg-gray-800 hover:text-white transition">
            <span>🌐</span><span>Customer App</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-400 hover:bg-gray-800 hover:text-white transition">
                <span>🚪</span><span>Logout</span>
            </button>
        </form>
    </div>
</aside>

{{-- Main --}}
<div class="lg:ml-60 min-h-screen flex flex-col">

    {{-- Top bar --}}
    <header class="bg-white border-b border-gray-200 px-4 py-3 flex items-center gap-3 sticky top-0 z-10">
        <button @click="sidebarOpen=!sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <h1 class="text-sm font-semibold text-gray-800 flex-1">@yield('heading', 'Admin')</h1>
        <span class="text-xs text-gray-400">{{ auth()->user()->name }}</span>
    </header>

    {{-- Flash messages --}}
    <div class="px-6 pt-4 space-y-2">
        @if(session('success'))
        <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-2.5 rounded-lg">
            <span>✅</span> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-2.5 rounded-lg">
            <span>❌</span> {{ session('error') }}
        </div>
        @endif
        @if(session('reset_password'))
        <div class="flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-2.5 rounded-lg font-mono">
            <span>🔑</span> {{ session('reset_password') }}
        </div>
        @endif
    </div>

    {{-- Page content --}}
    <main class="flex-1 p-6">
        @yield('content')
    </main>

</div>
</body>
</html>
