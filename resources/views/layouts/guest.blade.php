<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} — @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .playfair { font-family: 'Playfair Display', serif; }
        .btn-gradient { background: linear-gradient(135deg, #db2777, #9333ea); }
        .text-gradient { background: linear-gradient(135deg, #db2777, #9333ea); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .hero-gradient { background: linear-gradient(135deg, #fff0f6 0%, #fdf2ff 50%, #fff7ed 100%); }
    </style>
</head>
<body class="hero-gradient min-h-screen flex items-center justify-center p-4">

    {{-- Decorative blobs --}}
    <div class="fixed top-0 left-0 w-72 h-72 bg-pink-200 rounded-full opacity-20 blur-3xl -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
    <div class="fixed bottom-0 right-0 w-96 h-96 bg-purple-200 rounded-full opacity-20 blur-3xl translate-x-1/3 translate-y-1/3 pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3 mb-4">
                <div class="w-12 h-12 btn-gradient rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-pink-200">💍</div>
                <div class="text-left">
                    <p class="playfair text-xl font-bold text-gray-900 leading-tight">WedPlan <span class="text-gradient">Lanka</span></p>
                    <p class="text-xs text-gray-400 font-medium">Plan your perfect Sri Lankan wedding</p>
                </div>
            </a>
        </div>

        {{-- Flash messages --}}
        @if(session('error'))
        <div class="mb-4 flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm">
            <span class="text-lg flex-shrink-0">⚠️</span> {{ session('error') }}
        </div>
        @endif
        @if(session('success'))
        <div class="mb-4 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm">
            <span class="text-lg flex-shrink-0">✅</span> {{ session('success') }}
        </div>
        @endif

        {{-- Page content --}}
        @yield('content')

        {{-- Footer --}}
        <p class="text-center text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} WedPlan Lanka · Made with ❤️ in Sri Lanka 🇱🇰
        </p>
    </div>

</body>
</html>
