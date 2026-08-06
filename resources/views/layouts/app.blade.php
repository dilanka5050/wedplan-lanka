<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .playfair { font-family: 'Playfair Display', serif; }
        [x-cloak] { display: none !important; }
        .sidebar-link { display:flex; align-items:center; gap:12px; padding:10px 14px; border-radius:12px; font-size:14px; font-weight:500; transition:all 0.2s; color:#6b7280; }
        .sidebar-link:hover { background:#fdf2f8; color:#db2777; }
        .sidebar-link.active { background:linear-gradient(135deg,#fce7f3,#f3e8ff); color:#db2777; font-weight:600; }
        .sidebar-link .icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:16px; background:#f9fafb; flex-shrink:0; transition:all 0.2s; }
        .sidebar-link.active .icon { background:linear-gradient(135deg,#db2777,#9333ea); }
        .sidebar-link:hover .icon { background:#fce7f3; }
        .btn-gradient { background:linear-gradient(135deg,#db2777,#9333ea); }
        .text-gradient { background:linear-gradient(135deg,#db2777,#9333ea); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .scrollbar-hide::-webkit-scrollbar { display:none; }
        .scrollbar-hide { -ms-overflow-style:none; scrollbar-width:none; }
        .card-shadow { box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04); }
    </style>
</head>
<body class="bg-[#f8f7ff] min-h-screen" x-data="{ sidebarOpen: false, upgradeOpen: false }">

    <!-- Global loading overlay -->
    <div id="loading-overlay" class="hidden fixed inset-0 z-[9999] bg-black/40 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl px-10 py-8 flex flex-col items-center gap-4">
            <div class="w-12 h-12 rounded-full border-4 border-pink-200 border-t-pink-600 animate-spin"></div>
            <p class="text-sm font-semibold text-gray-700">Please wait...</p>
        </div>
    </div>

    <!-- Mobile overlay -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false"
         class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden"></div>

    <!-- ===== SIDEBAR ===== -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-white flex flex-col transform transition-transform duration-300 lg:translate-x-0 lg:fixed"
           style="box-shadow: 4px 0 24px rgba(0,0,0,0.06);">

        <!-- Logo -->
        <div class="flex items-center gap-3 px-5 h-16 border-b border-gray-100 flex-shrink-0">
            <div class="w-9 h-9 btn-gradient rounded-xl flex items-center justify-center text-lg shadow-md">💍</div>
            <div>
                <p class="playfair font-bold text-gray-900 text-base leading-tight">WedPlan</p>
                <p class="text-xs text-pink-500 font-semibold tracking-wide">LANKA</p>
            </div>
            <button @click="sidebarOpen=false" class="ml-auto lg:hidden text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Wedding info pill -->
        @auth
        @if(auth()->user()->wedding)
        <div class="mx-4 mt-4 p-3 rounded-2xl bg-gradient-to-r from-pink-50 to-purple-50 border border-pink-100">
            <p class="text-xs text-gray-400 font-medium mb-0.5">YOUR WEDDING</p>
            <p class="text-sm font-bold text-gray-800 truncate">{{ auth()->user()->wedding->bride_name }} & {{ auth()->user()->wedding->groom_name }}</p>
            @if(auth()->user()->wedding->wedding_date)
            <p class="text-xs text-pink-500 mt-0.5 font-medium">{{ auth()->user()->wedding->wedding_date->format('d M Y') }}</p>
            @endif
        </div>
        @endif
        @endauth

        <!-- Nav -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto scrollbar-hide">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2">Main</p>

            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span> Dashboard
            </a>
            <a href="{{ route('tasks.index') }}" class="sidebar-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                <span class="icon">✅</span> Checklist
            </a>
            <a href="{{ route('budgets.index') }}" class="sidebar-link {{ request()->routeIs('budgets.*') ? 'active' : '' }}">
                <span class="icon">💰</span> Budget
            </a>
            <a href="{{ route('guests.index') }}" class="sidebar-link {{ request()->routeIs('guests.*') ? 'active' : '' }}">
                <span class="icon">👥</span> Guests
            </a>
            <a href="{{ route('vault.index') }}" class="sidebar-link {{ request()->routeIs('vault.*') ? 'active' : '' }}">
                <span class="icon">📁</span> Vault
            </a>
            <a href="{{ route('notes.index') }}" class="sidebar-link {{ request()->routeIs('notes.*') ? 'active' : '' }}">
                <span class="icon">📝</span> Notes
            </a>

            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest px-3 mt-5 mb-2">Pro Features</p>

            @auth
            @if(auth()->user()->isPro())
            <a href="{{ route('timelines.index') }}" class="sidebar-link {{ request()->routeIs('timelines.*') ? 'active' : '' }}">
                <span class="icon">📅</span> Agenda
            </a>
            <a href="{{ route('invitations.index') }}" class="sidebar-link {{ request()->routeIs('invitations.*') ? 'active' : '' }}">
                <span class="icon">💌</span> Invitation
            </a>
            <a href="{{ route('digital-invitations.index') }}" class="sidebar-link {{ request()->routeIs('digital-invitations.*') ? 'active' : '' }}">
                <span class="icon">✨</span> Digital Invite
            </a>
            <a href="{{ route('seating.index') }}" class="sidebar-link {{ request()->routeIs('seating.*') ? 'active' : '' }}">
                <span class="icon">🪑</span> Seating Plan
            </a>
            @else
            <div class="sidebar-link opacity-50 cursor-not-allowed">
                <span class="icon">📅</span>
                <span>Agenda</span>
                <span class="ml-auto text-xs bg-amber-100 text-amber-600 font-bold px-2 py-0.5 rounded-full">PRO</span>
            </div>
            <div class="sidebar-link opacity-50 cursor-not-allowed">
                <span class="icon">💌</span>
                <span>Invitation</span>
                <span class="ml-auto text-xs bg-amber-100 text-amber-600 font-bold px-2 py-0.5 rounded-full">PRO</span>
            </div>
            <div class="sidebar-link opacity-50 cursor-not-allowed">
                <span class="icon">✨</span>
                <span>Digital Invite</span>
                <span class="ml-auto text-xs bg-amber-100 text-amber-600 font-bold px-2 py-0.5 rounded-full">PRO</span>
            </div>
            <div class="sidebar-link opacity-50 cursor-not-allowed">
                <span class="icon">🪑</span>
                <span>Seating Plan</span>
                <span class="ml-auto text-xs bg-amber-100 text-amber-600 font-bold px-2 py-0.5 rounded-full">PRO</span>
            </div>
            @endif
            @endauth


        </nav>

        <!-- Upgrade banner (free users) -->
        @auth
        @if(!auth()->user()->isPro())
        <div class="mx-3 mb-4 p-4 btn-gradient rounded-2xl text-white">
            <p class="text-xs font-bold mb-1">✨ Upgrade to Pro</p>
            <p class="text-xs opacity-80 mb-3">Unlock timeline, unlimited guests & more</p>
            <button @click="upgradeOpen = true" class="block w-full text-center bg-white text-pink-600 text-xs font-bold py-1.5 rounded-lg hover:bg-pink-50 transition">Upgrade Now</button>
        </div>
        @endif
        @endauth

        <!-- User footer -->
        @auth
        <div class="border-t border-gray-100 px-4 py-3 flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('profile.index') }}"
               class="w-9 h-9 btn-gradient rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0 hover:opacity-90 transition">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </a>
            <div class="flex-1 min-w-0">
                <a href="{{ route('profile.index') }}" class="text-sm font-semibold text-gray-800 truncate block hover:text-pink-600 transition">{{ auth()->user()->name }}</a>
                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->isPro() ? '⭐ Pro Plan' : 'Free Plan' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Logout" class="text-gray-400 hover:text-red-400 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
        @endauth
    </aside>

    <!-- ===== MAIN AREA ===== -->
    <div class="lg:ml-64 min-h-screen flex flex-col">

        <!-- Impersonation banner (admin viewing as customer) -->
        @if(session('impersonating_user_id'))
        @php $impersonatedWedding = auth()->user()->wedding; @endphp
        <div class="bg-orange-500 text-white px-4 lg:px-8 py-2.5 flex items-center justify-between gap-4 flex-shrink-0">
            <div class="flex items-center gap-2">
                <span class="text-lg">🔑</span>
                <p class="text-sm font-semibold">
                    You are managing
                    <span class="font-black">
                        {{ $impersonatedWedding?->bride_name }} & {{ $impersonatedWedding?->groom_name }}
                    </span>'s wedding on their behalf.
                    <span class="font-normal opacity-80 ml-1">All actions are logged.</span>
                </p>
            </div>
            <a href="{{ route('impersonate.stop') }}"
               class="flex-shrink-0 bg-white text-orange-600 text-xs font-bold px-4 py-1.5 rounded-full hover:bg-orange-50 transition whitespace-nowrap">
                Exit &rarr; Admin Panel
            </a>
        </div>
        @endif

        <!-- Customer warning banner (admin access is enabled) -->
        @auth
        @if(!session('impersonating_user_id') && auth()->user()->wedding?->allow_admin_access)
        <div class="bg-amber-50 border-b border-amber-200 px-4 lg:px-8 py-2.5 flex items-center justify-between gap-4 flex-shrink-0">
            <div class="flex items-center gap-2">
                <span class="text-base">🔓</span>
                <p class="text-xs text-amber-800">
                    <span class="font-semibold">Admin access is enabled.</span>
                    Your wedding planner can currently view and edit your wedding data.
                    @if(auth()->user()->wedding->last_accessed_at)
                    Last accessed {{ auth()->user()->wedding->last_accessed_at->diffForHumans() }}.
                    @endif
                </p>
            </div>
            <form method="POST" action="{{ route('wedding.update') }}" class="flex-shrink-0">
                @csrf @method('PUT')
                <input type="hidden" name="bride_name" value="{{ auth()->user()->wedding->bride_name }}">
                <input type="hidden" name="groom_name" value="{{ auth()->user()->wedding->groom_name }}">
                <input type="hidden" name="wedding_type" value="{{ auth()->user()->wedding->wedding_type->value }}">
                <input type="hidden" name="wedding_date" value="{{ auth()->user()->wedding->wedding_date?->format('Y-m-d') }}">
                <input type="hidden" name="venue" value="{{ auth()->user()->wedding->venue }}">
                <input type="hidden" name="allow_admin_access" value="0">
                <button type="submit"
                        class="text-xs font-bold text-amber-700 bg-amber-100 border border-amber-300 px-3 py-1.5 rounded-full hover:bg-amber-200 transition whitespace-nowrap">
                    Revoke Access
                </button>
            </form>
        </div>
        @endif
        @endauth

        <!-- Topbar -->
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-100 h-16 flex items-center px-4 lg:px-8 gap-4"
                style="box-shadow:0 1px 0 rgba(0,0,0,0.06);">
            <button @click="sidebarOpen=true" class="lg:hidden p-2 rounded-xl hover:bg-gray-100 text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <!-- Page title -->
            <div class="flex-1">
                <h1 class="text-base font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            </div>

            <!-- Right side -->
            <div class="flex items-center gap-3">
                @auth
                @if(auth()->user()->wedding)
                <div class="hidden sm:flex items-center gap-2 bg-pink-50 border border-pink-100 rounded-full px-3 py-1.5">
                    <span class="text-xs">💍</span>
                    @if(auth()->user()->wedding->wedding_date)
                    <span class="text-xs font-semibold text-pink-600">
                        {{ max(0, now()->diffInDays(auth()->user()->wedding->wedding_date, false)) }} days left
                    </span>
                    @else
                    <span class="text-xs font-semibold text-pink-600">Date not set</span>
                    @endif
                </div>
                @endif
                @endauth

                <div class="relative" x-data="{ menuOpen: false }" @click.outside="menuOpen = false">
                    <button @click="menuOpen = !menuOpen"
                            class="w-9 h-9 btn-gradient rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md hover:opacity-90 transition">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </button>

                    <div x-show="menuOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-50"
                         style="transform-origin: top right;">

                        <div class="px-4 py-2.5 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->isPro() ? '⭐ Pro Plan' : 'Free Plan' }}</p>
                        </div>

                        <a href="{{ route('profile.index') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition">
                            <span class="text-base">👤</span> Profile
                        </a>
                        <a href="{{ route('wedding.edit') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition">
                            <span class="text-base">⚙️</span> Settings
                        </a>

                        <div class="border-t border-gray-100 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="flex items-center gap-2.5 w-full px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition">
                                    <span class="text-base">🚪</span> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash messages -->
        <div class="px-4 lg:px-8 pt-4">
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm mb-2">
                <span class="text-lg">✅</span> {{ session('success') }}
                <button @click="show=false" class="ml-auto text-green-400 hover:text-green-600">&times;</button>
            </div>
            @endif
            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm mb-2">
                <span class="text-lg">⚠️</span> {{ session('error') }}
                <button @click="show=false" class="ml-auto text-red-400 hover:text-red-600">&times;</button>
            </div>
            @endif
        </div>

        <!-- Page content -->
        <main class="flex-1 px-4 lg:px-8 py-4 pb-10">
            @yield('content')
        </main>
    </div>

    <!-- ===== UPGRADE TO PRO MODAL ===== -->
    <div x-show="upgradeOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.self="upgradeOpen = false">
        <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-8 text-center">

            <div class="w-16 h-16 btn-gradient rounded-2xl flex items-center justify-center text-3xl mx-auto mb-5 shadow-lg">⭐</div>
            <h3 class="playfair text-2xl font-bold text-gray-900 mb-2">Upgrade to Pro</h3>
            <p class="text-sm text-gray-500 mb-6">Unlock unlimited guests, wedding agenda,<br>digital invitations, seating plan & more.</p>

            <div class="bg-gradient-to-br from-pink-50 to-purple-50 rounded-2xl p-4 mb-6">
                <p class="text-2xl font-black text-gray-900">
                    LKR {{ number_format($proPlan?->price ?? 2999, 0) }}
                    <span class="text-sm font-normal text-gray-400">/ wedding</span>
                </p>
                <p class="text-xs text-gray-400 mt-1">One-time payment &middot; Lifetime access</p>
            </div>

            <p class="text-sm font-semibold text-gray-700 mb-4">Contact us to upgrade:</p>

            <a href="https://wa.me/94704653343?text=Hi%2C%20I%20would%20like%20to%20upgrade%20to%20WedPlan%20Lanka%20Pro"
               target="_blank"
               class="flex items-center justify-center gap-3 w-full py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-full shadow-md transition mb-5">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Chat on WhatsApp
            </a>

            <button @click="upgradeOpen = false"
                    class="text-xs text-gray-400 hover:text-gray-600 transition">
                Maybe later
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('guestSearch', { q: '' });
        });
    </script>

    <script>
        (function () {
            const overlay = document.getElementById('loading-overlay');

            function showOverlay() {
                overlay.classList.remove('hidden');
            }

            // Patch programmatic form.submit() — this does NOT fire the submit event natively
            const nativeSubmit = HTMLFormElement.prototype.submit;
            HTMLFormElement.prototype.submit = function () {
                if (!this.closest('[data-no-loading]')) showOverlay();
                nativeSubmit.call(this);
            };

            // Catch declarative form submissions (type=submit button clicks)
            document.addEventListener('submit', function (e) {
                if (e.target.closest('[data-no-loading]')) return;
                showOverlay();
            });

            // Catch internal navigation links
            document.addEventListener('click', function (e) {
                const a = e.target.closest('a[href]');
                if (!a) return;
                const href = a.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript')) return;
                if (a.target === '_blank') return;
                if (a.closest('[data-no-loading]')) return;
                showOverlay();
            });

            // Hide on bfcache restore (browser back/forward)
            window.addEventListener('pageshow', function (e) {
                overlay.classList.add('hidden');
            });
        })();
    </script>

    @stack('scripts')

</body>
</html>
