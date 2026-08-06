<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error — WedPlan Lanka</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .playfair { font-family: 'Playfair Display', serif; }
        .btn-gradient { background: linear-gradient(135deg, #db2777, #9333ea); }
        .hero-gradient { background: linear-gradient(135deg, #fff0f6 0%, #fdf2ff 50%, #fff7ed 100%); }
    </style>
</head>
<body class="hero-gradient min-h-screen flex items-center justify-center p-6">
    <div class="text-center max-w-md">
        <div class="w-24 h-24 btn-gradient rounded-3xl flex items-center justify-center text-5xl mx-auto mb-6 shadow-xl shadow-pink-200">
            💍
        </div>
        <p class="text-8xl font-black text-pink-200 leading-none mb-2">500</p>
        <h1 class="playfair text-2xl font-bold text-gray-900 mb-3">Something Went Wrong</h1>
        <p class="text-gray-500 text-sm leading-relaxed mb-8">
            We're sorry — something unexpected happened on our end.
            Our team has been notified. Please try again in a moment.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            @auth
            <a href="{{ route('dashboard') }}"
               class="btn-gradient text-white font-semibold px-6 py-3 rounded-full shadow-lg shadow-pink-200 hover:shadow-pink-300 transition text-sm">
                ← Back to Dashboard
            </a>
            @else
            <a href="{{ url('/') }}"
               class="btn-gradient text-white font-semibold px-6 py-3 rounded-full shadow-lg shadow-pink-200 hover:shadow-pink-300 transition text-sm">
                ← Back to Home
            </a>
            @endauth
            <a href="mailto:hello@wedplanlanka.com"
               class="border-2 border-pink-200 text-pink-600 font-semibold px-6 py-3 rounded-full hover:bg-pink-50 transition text-sm">
                Contact Support
            </a>
        </div>
        <p class="text-xs text-gray-400 mt-8">WedPlan Lanka · Made with ❤️ in Sri Lanka 🇱🇰</p>
    </div>
</body>
</html>
