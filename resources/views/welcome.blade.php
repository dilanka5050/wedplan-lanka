<!DOCTYPE html>
<html lang="en" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- ===== PRIMARY SEO ===== --}}
    <title>WedPlan Lanka — #1 Wedding Planning App for Sri Lankan Couples</title>
    <meta name="description" content="Plan your perfect Sri Lankan wedding with WedPlan Lanka. Smart checklists for Sinhala, Tamil, Muslim & Christian weddings. Budget tracker, guest manager, Poruwa ceremony planner & digital invitations. Free to start.">
    <meta name="keywords" content="Sri Lankan wedding planner, wedding planning app Sri Lanka, Poruwa ceremony planner, Sinhala wedding checklist, Tamil wedding planner, Muslim wedding Sri Lanka, Christian wedding planner, wedding budget tracker LKR, wedding guest list Sri Lanka, digital wedding invitation Sri Lanka">
    <meta name="author" content="WedPlan Lanka">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#db2777">
    <link rel="canonical" href="{{ url('/') }}">
    <link rel="icon" type="image/png" href="{{ url('/webasset/logo_with backgound.png') }}">

    {{-- ===== OPEN GRAPH (Facebook, WhatsApp, LinkedIn) ===== --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="WedPlan Lanka — #1 Wedding Planning App for Sri Lankan Couples">
    <meta property="og:description" content="Plan your perfect Sri Lankan wedding with smart checklists, budget tracker, guest manager & digital invitations. Supports Sinhala, Tamil, Muslim & Christian weddings. Free to start.">
    <meta property="og:image" content="{{ url('/webasset/cover_image.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="WedPlan Lanka - Sri Lankan Wedding Planning Platform">
    <meta property="og:site_name" content="WedPlan Lanka">
    <meta property="og:locale" content="en_LK">

    {{-- ===== TWITTER CARD ===== --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="WedPlan Lanka — #1 Wedding Planning App for Sri Lankan Couples">
    <meta name="twitter:description" content="Plan your perfect Sri Lankan wedding with smart checklists, budget tracker, guest manager & digital invitations. Free to start.">
    <meta name="twitter:image" content="{{ url('/webasset/cover_image.png') }}">
    <meta name="twitter:image:alt" content="WedPlan Lanka - Sri Lankan Wedding Planning Platform">

    {{-- ===== GEO / LANGUAGE ===== --}}
    <meta name="geo.region" content="LK">
    <meta name="geo.placename" content="Sri Lanka">
    <meta name="language" content="English">
    <link rel="alternate" hreflang="en-lk" href="{{ url('/') }}">
    <link rel="alternate" hreflang="x-default" href="{{ url('/') }}">

    {{-- ===== JSON-LD STRUCTURED DATA ===== --}}
    <script type="application/ld+json">
    @verbatim
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "WebSite",
                "@id": "@endverbatim{{ url('/') }}@verbatim#website",
                "url": "@endverbatim{{ url('/') }}@verbatim",
                "name": "WedPlan Lanka",
                "description": "The all-in-one wedding planning platform for Sri Lankan couples",
                "inLanguage": "en-LK",
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": "@endverbatim{{ url('/') }}@verbatim?q={search_term_string}",
                    "query-input": "required name=search_term_string"
                }
            },
            {
                "@type": "SoftwareApplication",
                "name": "WedPlan Lanka",
                "url": "@endverbatim{{ url('/') }}@verbatim",
                "description": "All-in-one wedding planning platform for Sri Lankan couples. Supports Sinhala, Tamil, Muslim and Christian weddings with smart checklists, budget tracking, guest management and digital invitations.",
                "applicationCategory": "LifestyleApplication",
                "operatingSystem": "Web",
                "offers": [
                    {
                        "@type": "Offer",
                        "name": "Free Plan",
                        "price": "0",
                        "priceCurrency": "LKR",
                        "description": "Free wedding planning with up to 30 guests and 20 tasks"
                    },
                    {
                        "@type": "Offer",
                        "name": "Pro Plan",
                        "price": "2999",
                        "priceCurrency": "LKR",
                        "description": "Unlimited guests, tasks, digital invitations, wedding agenda and more"
                    }
                ],
                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "5",
                    "reviewCount": "500",
                    "bestRating": "5",
                    "worstRating": "1"
                }
            },
            {
                "@type": "Organization",
                "@id": "@endverbatim{{ url('/') }}@verbatim#organization",
                "name": "WedPlan Lanka",
                "url": "@endverbatim{{ url('/') }}@verbatim",
                "logo": {
                    "@type": "ImageObject",
                    "url": "@endverbatim{{ url('/webasset/logo_without_background.png') }}@verbatim"
                },
                "contactPoint": {
                    "@type": "ContactPoint",
                    "contactType": "customer support",
                    "email": "hello@wedplanlanka.com",
                    "availableLanguage": ["English", "Sinhala", "Tamil"]
                },
                "areaServed": {
                    "@type": "Country",
                    "name": "Sri Lanka"
                }
            },
            {
                "@type": "FAQPage",
                "mainEntity": [
                    {
                        "@type": "Question",
                        "name": "Is WedPlan Lanka free to use?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Yes, WedPlan Lanka has a free plan that includes up to 30 guests, 20 tasks, budget tracker, and wedding dashboard. No credit card required."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "Does WedPlan Lanka support Sinhala, Tamil, Muslim and Christian weddings?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Yes, WedPlan Lanka supports all Sri Lankan wedding traditions including Sinhala Poruwa ceremonies, Tamil Thali ceremonies, Muslim Nikah ceremonies, and Christian church weddings with pre-built checklists for each."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "What is included in the Pro plan?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "The Pro plan (LKR 2,999 per wedding) includes unlimited guests, unlimited tasks, digital wedding invitations with 3 templates, wedding agenda with Nekath time support, separate bride and groom guest lists, file uploads, and priority support."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "Can I create digital wedding invitations with WedPlan Lanka?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Yes, Pro users can create beautiful digital wedding invitations using 3 professionally designed templates. Fill in your details and the invitation is generated instantly and can be printed or saved as PDF."
                        }
                    }
                ]
            }
        ]
    }
    @endverbatim
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .playfair { font-family: 'Playfair Display', serif; }
        .hero-gradient { background: linear-gradient(135deg, #fff0f6 0%, #fdf2ff 40%, #fff7ed 100%); }
        .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card-hover:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(236,72,153,0.15); }
        .glow { box-shadow: 0 0 40px rgba(236,72,153,0.25); }
        .text-gradient { background: linear-gradient(135deg, #db2777, #9333ea); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .btn-gradient { background: linear-gradient(135deg, #db2777, #9333ea); }
        .btn-gradient:hover { background: linear-gradient(135deg, #be185d, #7e22ce); }
        .floating { animation: float 4s ease-in-out infinite; }
        .floating-delay { animation: float 4s ease-in-out 1s infinite; }
        @@keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
        .fade-in { animation: fadeIn 0.8s ease forwards; }
        @@keyframes fadeIn { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        .nav-blur { backdrop-filter: blur(12px); background: rgba(255,255,255,0.85); }
        .section-divider { background: linear-gradient(90deg, transparent, #f9a8d4, transparent); height: 1px; }
        .badge { background: linear-gradient(135deg, #fce7f3, #f3e8ff); }
    </style>
</head>
<body class="bg-white overflow-x-hidden">

    <!-- Skip to main content (accessibility + SEO) -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-pink-600 focus:text-white focus:rounded-lg focus:text-sm focus:font-semibold">Skip to main content</a>

    <!-- Navbar -->
    <header role="banner">
    <nav aria-label="Main navigation" class="fixed top-0 left-0 right-0 z-50 nav-blur border-b border-pink-100" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center">
                <img src="{{ url('/webasset/logo_without_background.png') }}" alt="WedPlan Lanka" class="h-10 w-auto">
            </a>
            <div class="hidden md:flex items-center space-x-8">
                <a href="#features" class="text-sm text-gray-600 hover:text-pink-600 font-medium transition">Features</a>
                <a href="#how-it-works" class="text-sm text-gray-600 hover:text-pink-600 font-medium transition">How It Works</a>
                <a href="#pricing" class="text-sm text-gray-600 hover:text-pink-600 font-medium transition">Pricing</a>
                <a href="#faq" class="text-sm text-gray-600 hover:text-pink-600 font-medium transition">FAQ</a>
                <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-pink-600 font-medium transition">Login</a>
                <a href="{{ route('register') }}" class="btn-gradient text-white text-sm font-semibold px-5 py-2.5 rounded-full shadow-lg shadow-pink-200 hover:shadow-pink-300 transition">Get Started Free</a>
            </div>
            <button @click="open = !open" class="md:hidden text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
        <div x-show="open" class="md:hidden px-6 pb-4 space-y-3 border-t border-pink-50">
            <a href="#features" class="block text-sm text-gray-600 py-2">Features</a>
            <a href="#pricing" class="block text-sm text-gray-600 py-2">Pricing</a>
            <a href="#faq" class="block text-sm text-gray-600 py-2">FAQ</a>
            <a href="{{ route('login') }}" class="block text-sm text-gray-600 py-2">Login</a>
            <a href="{{ route('register') }}" class="block btn-gradient text-white text-sm font-semibold px-5 py-2.5 rounded-full text-center">Get Started Free</a>
        </div>
    </nav>
    </header>

    <!-- Hero Section -->
    <main id="main-content">
    <section aria-label="Hero" class="hero-gradient min-h-screen flex items-center pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 py-20 grid lg:grid-cols-2 gap-16 items-center">
            <div class="fade-in">
                <div class="badge inline-flex items-center px-4 py-2 rounded-full text-sm font-medium text-pink-700 mb-6">
                    🇱🇰 &nbsp; Built for Sri Lankan Couples
                </div>
                <h1 class="playfair text-5xl lg:text-7xl font-black text-gray-900 leading-tight">
                    Plan Your<br>
                    <span class="text-gradient">Dream Wedding</span><br>
                    <span class="text-gray-800">Effortlessly</span>
                </h1>
                <p class="mt-6 text-lg text-gray-600 leading-relaxed max-w-lg">
                    From Poruwa ceremonies to Christian receptions — WedPlan Lanka is the all-in-one wedding planning platform designed for Sri Lankan couples.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('register') }}" class="btn-gradient text-white font-semibold px-8 py-4 rounded-full shadow-xl shadow-pink-200 hover:shadow-pink-300 transition text-center text-base">
                        ✨ Start Planning Free
                    </a>
                    <a href="#how-it-works" class="flex items-center justify-center gap-2 px-8 py-4 rounded-full border-2 border-pink-200 text-pink-700 font-semibold hover:bg-pink-50 transition text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        See How It Works
                    </a>
                </div>
                <div class="mt-10 flex items-center gap-6">
                    <div class="flex -space-x-3">
                        @foreach(['🧑', '👩', '🧑', '👩'] as $emoji)
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-200 to-purple-200 border-2 border-white flex items-center justify-center text-sm">{{ $emoji }}</div>
                        @endforeach
                    </div>
                    <div>
                        <div class="flex text-amber-400 text-sm">★★★★★</div>
                        <p class="text-xs text-gray-500 mt-0.5">Loved by 500+ Sri Lankan couples</p>
                    </div>
                </div>
            </div>

            <!-- Hero Visual -->
            <div class="relative hidden lg:block">
                <div class="floating relative z-10">
                    <!-- Dashboard Preview Card -->
                    <div class="bg-white rounded-3xl shadow-2xl p-6 glow">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <p class="text-xs text-gray-400 font-medium">WEDDING COUNTDOWN</p>
                                <p class="text-3xl font-black text-gray-900 mt-1">127 <span class="text-lg font-medium text-gray-500">days to go</span></p>
                            </div>
                            <div class="w-14 h-14 btn-gradient rounded-2xl flex items-center justify-center text-2xl shadow-lg">💍</div>
                        </div>
                        <div class="grid grid-cols-3 gap-3 mb-5">
                            <div class="bg-pink-50 rounded-xl p-3 text-center">
                                <p class="text-xl font-bold text-pink-600">68%</p>
                                <p class="text-xs text-gray-500 mt-0.5">Progress</p>
                            </div>
                            <div class="bg-purple-50 rounded-xl p-3 text-center">
                                <p class="text-xl font-bold text-purple-600">142</p>
                                <p class="text-xs text-gray-500 mt-0.5">Guests</p>
                            </div>
                            <div class="bg-amber-50 rounded-xl p-3 text-center">
                                <p class="text-xl font-bold text-amber-600">24</p>
                                <p class="text-xs text-gray-500 mt-0.5">Tasks</p>
                            </div>
                        </div>
                        <div class="space-y-2.5">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Upcoming Tasks</p>
                            @foreach([['Book Poruwa decorator','high','🌸'],['Confirm caterer menu','medium','🍽️'],['Send invitations','high','💌']] as [$task,$pri,$icon])
                            <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-3 py-2.5">
                                <span class="text-base">{{ $icon }}</span>
                                <span class="text-sm text-gray-700 flex-1">{{ $task }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $pri === 'high' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600' }}">{{ ucfirst($pri) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Floating badges -->
                <div class="floating-delay absolute -top-4 -right-4 bg-white rounded-2xl shadow-xl px-4 py-3 flex items-center gap-2 z-20">
                    <span class="text-xl">✅</span>
                    <div><p class="text-xs font-bold text-gray-800">Task Completed!</p><p class="text-xs text-gray-400">Book photographer</p></div>
                </div>
                <div class="floating absolute -bottom-4 -left-4 bg-white rounded-2xl shadow-xl px-4 py-3 flex items-center gap-2 z-20">
                    <span class="text-xl">👥</span>
                    <div><p class="text-xs font-bold text-gray-800">New RSVP</p><p class="text-xs text-green-500">Accepted · Bride Side</p></div>
                </div>
                <!-- Decorative blobs -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-pink-200 rounded-full opacity-20 blur-3xl -z-10"></div>
                <div class="absolute top-0 right-0 w-48 h-48 bg-purple-200 rounded-full opacity-20 blur-2xl -z-10"></div>
            </div>
        </div>
    </section>

    <!-- Wedding Types Banner -->
    <section aria-label="Supported wedding traditions" class="bg-white py-10 border-y border-gray-100">
        <div class="max-w-5xl mx-auto px-6">
            <p class="text-center text-sm font-semibold text-gray-400 uppercase tracking-widest mb-8">Supports All Sri Lankan Wedding Traditions</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach([['🪔','Sinhala','Poruwa Ceremony'],['🌺','Tamil','Thali Ceremony'],['☪️','Muslim','Nikah Ceremony'],['✝️','Christian','Church Wedding']] as [$icon,$type,$sub])
                <div class="flex flex-col items-center p-5 rounded-2xl bg-gradient-to-b from-gray-50 to-white border border-gray-100 hover:border-pink-200 hover:shadow-md transition">
                    <span class="text-3xl mb-2">{{ $icon }}</span>
                    <p class="font-bold text-gray-800 text-sm">{{ $type }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $sub }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" aria-label="Features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="text-center mb-16">
                <span class="badge inline-block px-4 py-1.5 rounded-full text-sm font-semibold text-purple-700 mb-4">Everything You Need</span>
                <h2 class="playfair text-4xl lg:text-5xl font-bold text-gray-900">Powerful Features for<br><span class="text-gradient">Your Perfect Day</span></h2>
                <p class="mt-4 text-gray-500 max-w-xl mx-auto">All the tools you need to plan, organize, and execute your dream Sri Lankan wedding — in one beautiful platform.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['✅','Smart Checklist','bg-pink-50','text-pink-600','Pre-built templates for every Sri Lankan wedding type. Track tasks by category — Venue, Photography, Poruwa, Catering & more.'],
                    ['💰','Budget Tracker','bg-purple-50','text-purple-600','Track estimated vs actual costs in LKR. Get a clear picture of your spending with beautiful summary cards.'],
                    ['👥','Guest Manager','bg-blue-50','text-blue-600','Manage your full guest list with RSVP tracking, group sorting (Bride/Groom side), and dietary requirements.'],
                    ['📅','Wedding Agenda','bg-amber-50','text-amber-600','Plan your ceremony schedule with Nekath time support. Drag-and-drop event ordering for the perfect flow.'],
                    ['📁','File Storage','bg-green-50','text-green-600','Upload inspiration images, contracts, and documents. Keep everything organized in one secure place.'],
                    ['📊','Live Dashboard','bg-rose-50','text-rose-600','See your wedding progress at a glance — countdown timer, task completion, budget summary, and guest count.'],
                    ['📝','Wedding Notes','bg-teal-50','text-teal-600','Jot down ideas, vendor details, and reminders in one place. Keep all your wedding thoughts organized and accessible anytime.'],
                    ['💌','Wedding Cards & Digital Invitations','bg-fuchsia-50','text-fuchsia-600','Generate printable wedding cards from professionally designed templates. Also create animated digital invitations to share instantly via WhatsApp or any platform.'],
                    ['🪑','Seating Plan','bg-orange-50','text-orange-600','Arrange your reception tables with an intuitive seating planner. Assign guests to seats and manage table layouts with ease.'],
                ] as [$icon,$title,$bg,$color,$desc])
                <div class="card-hover {{ $bg }} rounded-2xl p-7 border border-transparent hover:border-pink-100">
                    <div class="text-3xl mb-4">{{ $icon }}</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $title }}</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" aria-label="How it works" class="py-24 hero-gradient">
        <div class="max-w-5xl mx-auto px-6 lg:px-10">
            <div class="text-center mb-16">
                <span class="badge inline-block px-4 py-1.5 rounded-full text-sm font-semibold text-pink-700 mb-4">Simple & Easy</span>
                <h2 class="playfair text-4xl lg:text-5xl font-bold text-gray-900">Get Started in<br><span class="text-gradient">3 Simple Steps</span></h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8 relative">
                <div class="hidden md:block absolute top-10 left-1/4 right-1/4 h-0.5 bg-gradient-to-r from-pink-200 via-purple-200 to-pink-200"></div>
                @foreach([
                    ['01','Create Account','Register for free in seconds. No credit card required.','🎉'],
                    ['02','Setup Your Wedding','Enter your wedding details and choose your ceremony type.','💒'],
                    ['03','Start Planning','Use smart checklists, budget tools, and guest manager.','🚀'],
                ] as [$num,$title,$desc,$emoji])
                <div class="relative text-center">
                    <div class="w-20 h-20 btn-gradient rounded-2xl flex items-center justify-center text-3xl mx-auto mb-5 shadow-lg shadow-pink-200 relative z-10">{{ $emoji }}</div>
                    <div class="absolute top-0 right-6 w-7 h-7 bg-white border-2 border-pink-300 rounded-full flex items-center justify-center text-xs font-black text-pink-600">{{ $num }}</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $title }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" aria-label="Pricing" class="py-24 bg-white">
        <div class="max-w-5xl mx-auto px-6 lg:px-10">
            <div class="text-center mb-16">
                <span class="badge inline-block px-4 py-1.5 rounded-full text-sm font-semibold text-purple-700 mb-4">Simple Pricing</span>
                <h2 class="playfair text-4xl lg:text-5xl font-bold text-gray-900">Start Free,<br><span class="text-gradient">Upgrade When Ready</span></h2>
            </div>
            <div class="grid md:grid-cols-2 gap-8 max-w-3xl mx-auto">
                <!-- Free Plan -->
                <div class="rounded-3xl border-2 border-gray-100 p-8 hover:border-pink-200 transition">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Free Plan</p>
                    <p class="playfair text-4xl font-black text-gray-900">LKR 0 <span class="text-lg font-normal text-gray-400">/ forever</span></p>
                    <p class="text-sm text-gray-500 mt-2 mb-6">Perfect to get started</p>
                    <ul class="space-y-3 mb-8">
                        @foreach(['Up to 30 guests','Up to 20 tasks','Up to 5 budget items','5 file uploads (images & docs)','Basic checklist','Wedding dashboard','Wedding notes'] as $f)
                        <li class="flex items-center gap-3 text-sm text-gray-700"><span class="text-green-500 font-bold">✓</span>{{ $f }}</li>
                        @endforeach
                        @foreach(['Wedding agenda & Nekath','Digital invitations','Invitation templates','Seating plan','Guest bulk import & export','Public RSVP links','Collaboration'] as $f)
                        <li class="flex items-center gap-3 text-sm text-gray-400"><span class="text-gray-300">✕</span>{{ $f }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center py-3 rounded-full border-2 border-pink-300 text-pink-600 font-semibold hover:bg-pink-50 transition">Get Started Free</a>
                </div>
                <!-- Pro Plan -->
                <div class="rounded-3xl border-2 border-pink-400 p-8 relative overflow-hidden shadow-xl shadow-pink-100" x-data="{ upgradeOpen: false }">
                    <div class="absolute top-5 right-5 bg-gradient-to-r from-pink-500 to-purple-500 text-white text-xs font-bold px-3 py-1 rounded-full">POPULAR</div>
                    <p class="text-sm font-semibold text-pink-600 uppercase tracking-wide mb-2">Pro Plan</p>
                    <p class="playfair text-4xl font-black text-gray-900">LKR {{ $proPlan ? number_format($proPlan->price, 0) : '2,999' }} <span class="text-lg font-normal text-gray-400">/ wedding</span></p>
                    <p class="text-sm text-gray-500 mt-2 mb-6">Everything for your big day</p>
                    <ul class="space-y-3 mb-8">
                        @foreach(['Unlimited guests','Unlimited tasks','Unlimited budget items','File uploads (100 files)','Basic checklist','Wedding dashboard','Wedding notes','Wedding agenda + Nekath time','Digital invitations','Invitation templates','Seating plan','Separate bride & groom lists','Guest bulk import & export','Public RSVP links per guest','Collaboration','Priority support'] as $f)
                        <li class="flex items-center gap-3 text-sm text-gray-700"><span class="text-green-500 font-bold">✓</span>{{ $f }}</li>
                        @endforeach
                    </ul>
                    <button @click="upgradeOpen = true" class="block w-full text-center py-3 rounded-full btn-gradient text-white font-semibold shadow-lg shadow-pink-200 hover:shadow-pink-300 transition">Upgrade to Pro</button>

                    <!-- Upgrade Modal -->
                    <div x-show="upgradeOpen" x-cloak
                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                         @click.self="upgradeOpen = false">
                        <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-8 text-center">
                            <div class="w-16 h-16 btn-gradient rounded-2xl flex items-center justify-center text-3xl mx-auto mb-5 shadow-lg">⭐</div>
                            <h3 class="playfair text-2xl font-bold text-gray-900 mb-2">Upgrade to Pro</h3>
                            <p class="text-sm text-gray-500 mb-6">Unlock unlimited guests, wedding agenda,<br>digital invitations, seating plan & more.</p>
                            <div class="bg-gradient-to-br from-pink-50 to-purple-50 rounded-2xl p-4 mb-6">
                                <p class="text-2xl font-black text-gray-900">
                                    LKR {{ $proPlan ? number_format($proPlan->price, 0) : '2,999' }}
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
                            <button @click="upgradeOpen = false" class="text-xs text-gray-400 hover:text-gray-600 transition">Maybe later</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section aria-label="Customer reviews" class="py-24 hero-gradient">
        <div class="max-w-6xl mx-auto px-6 lg:px-10">
            <div class="text-center mb-14">
                <h2 class="playfair text-4xl font-bold text-gray-900">Loved by Sri Lankan <span class="text-gradient">Couples</span></h2>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach([
                    ['Nimasha & Kasun','Sinhala Wedding · Colombo','WedPlan Lanka made our Poruwa planning so easy! The checklist templates were perfect for our traditional ceremony. Highly recommend!','⭐⭐⭐⭐⭐'],
                    ['Priya & Arjun','Tamil Wedding · Jaffna','The budget tracker saved us from overspending. We could see exactly where every rupee was going. Amazing tool for Tamil weddings!','⭐⭐⭐⭐⭐'],
                    ['Fathima & Ahmed','Muslim Wedding · Kandy','Managing our Nikah ceremony details and Walima guest list was a breeze. The RSVP tracking feature is brilliant!','⭐⭐⭐⭐⭐'],
                ] as [$name,$type,$quote,$stars])
                <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 card-hover">
                    <p class="text-sm mb-1">{{ $stars }}</p>
                    <p class="text-gray-700 text-sm leading-relaxed mt-3 mb-5">"{{ $quote }}"</p>
                    <div class="flex items-center gap-3 border-t border-gray-50 pt-4">
                        <div class="w-10 h-10 btn-gradient rounded-full flex items-center justify-center text-white font-bold text-sm">{{ substr($name, 0, 1) }}</div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $name }}</p>
                            <p class="text-xs text-gray-400">{{ $type }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" aria-label="Frequently asked questions" class="py-24 bg-white" itemscope itemtype="https://schema.org/FAQPage">
        <div class="max-w-3xl mx-auto px-6 lg:px-10">
            <div class="text-center mb-12">
                <span class="badge inline-block px-4 py-1.5 rounded-full text-sm font-semibold text-pink-700 mb-4">FAQ</span>
                <h2 class="playfair text-4xl font-bold text-gray-900">Frequently Asked <span class="text-gradient">Questions</span></h2>
            </div>
            <div class="space-y-4" x-data="{ open: null }">
                @foreach([
                    ['Is WedPlan Lanka free to use?', 'Yes! WedPlan Lanka has a free plan that includes up to 30 guests, 20 tasks, 5 budget items, and a wedding dashboard. No credit card required. Upgrade to Pro anytime for LKR 2,999.'],
                    ['Does it support Sinhala, Tamil, Muslim and Christian weddings?', 'Absolutely. WedPlan Lanka supports all Sri Lankan wedding traditions — Sinhala Poruwa ceremonies, Tamil Thali ceremonies, Muslim Nikah ceremonies, and Christian church weddings — each with pre-built checklists tailored to that tradition.'],
                    ['What is included in the Pro plan?', 'The Pro plan (LKR 2,999 per wedding) includes unlimited guests, unlimited tasks, digital wedding invitations with 3 templates, wedding agenda with Nekath time support, separate bride and groom guest lists, file uploads up to 100 files, and priority support.'],
                    ['Can I create digital wedding invitations?', 'Yes! Pro users can create beautiful digital wedding invitations using 3 professionally designed templates. Fill in your details, choose a template, and your invitation is generated instantly — ready to print or save as PDF.'],
                    ['How does the guest management work?', 'Add guests individually or as couples/families. Track RSVP status (Accepted, Declined, Pending, Maybe), set confirmed headcounts per invitation, and organize guests by groups. Pro users get separate Bride Side and Groom Side lists.'],
                    ['Can the admin upgrade or downgrade my plan?', 'Yes. Your wedding planner or admin can upgrade you to Pro or downgrade back to Free at any time through the admin panel. Changes take effect immediately.'],
                ] as $i => [$q, $a])
                <div class="border border-gray-100 rounded-2xl overflow-hidden" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <button @click="open === {{ $i }} ? open = null : open = {{ $i }}"
                            class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-gray-50 transition"
                            :aria-expanded="open === {{ $i }}">
                        <span class="font-semibold text-gray-900 text-sm" itemprop="name">{{ $q }}</span>
                        <span class="text-pink-500 text-lg ml-4 flex-shrink-0" x-text="open === {{ $i }} ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === {{ $i }}" x-cloak class="px-6 pb-5" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <p class="text-sm text-gray-600 leading-relaxed" itemprop="text">{{ $a }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section aria-label="Call to action" class="py-24 bg-white">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="btn-gradient rounded-3xl p-12 lg:p-16 shadow-2xl shadow-pink-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                <div class="relative z-10">
                    <p class="text-5xl mb-4">💍</p>
                    <h2 class="playfair text-4xl lg:text-5xl font-black text-white mb-4">Your Dream Wedding<br>Starts Here</h2>
                    <p class="text-pink-100 text-lg mb-8 max-w-xl mx-auto">Join hundreds of Sri Lankan couples who are planning their perfect wedding with WedPlan Lanka.</p>
                    <a href="{{ route('register') }}" class="inline-block bg-white text-pink-600 font-bold px-10 py-4 rounded-full text-lg shadow-xl hover:shadow-2xl hover:scale-105 transition">
                        ✨ Start Planning Free Today
                    </a>
                    <p class="text-pink-200 text-sm mt-4">No credit card required · Free forever plan available</p>
                </div>
            </div>
        </div>
    </section>

    </main>

    <!-- Footer -->
    <footer role="contentinfo" aria-label="Site footer" class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="grid md:grid-cols-4 gap-8 mb-10">
                <div class="md:col-span-2">
                    <div class="flex items-center mb-4">
                        <img src="{{ url('/webasset/logo_without_background.png') }}" alt="WedPlan Lanka" class="h-10 w-auto brightness-0 invert">
                    </div>
                    <p class="text-sm leading-relaxed max-w-xs">The all-in-one wedding planning platform built for Sri Lankan couples. Plan every detail of your special day with ease.</p>
                </div>
                <div>
                    <p class="text-white font-semibold text-sm mb-4">Features</p>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-pink-400 transition">Smart Checklist</a></li>
                        <li><a href="#" class="hover:text-pink-400 transition">Budget Tracker</a></li>
                        <li><a href="#" class="hover:text-pink-400 transition">Guest Manager</a></li>
                        <li><a href="#" class="hover:text-pink-400 transition">Wedding Agenda</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-white font-semibold text-sm mb-4">Account</p>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('login') }}" class="hover:text-pink-400 transition">Login</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-pink-400 transition">Register</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-pink-400 transition">Free Plan</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-pink-400 transition">Pro Plan</a></li>
                    </ul>
                </div>
            </div>
            <div class="section-divider mb-6"></div>
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm">&copy; {{ date('Y') }} WedPlan Lanka. All rights reserved.</p>
                <p class="text-sm">Made with ❤️ in Sri Lanka 🇱🇰</p>
            </div>
        </div>
    </footer>

    <!-- Smooth scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                e.preventDefault();
                document.querySelector(a.getAttribute('href'))?.scrollIntoView({ behavior: 'smooth' });
            });
        });
    </script>
</body>
</html>
