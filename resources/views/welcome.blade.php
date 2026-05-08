<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0d4a2a">

    <title>SIMAK — Universitas Muhammadiyah Lampung</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-950 text-gray-800 dark:text-gray-200">

{{-- ════════════════════════════════════════════════════════════
     TOP NAV
════════════════════════════════════════════════════════════ --}}
<header x-data="{ scrolled: false }"
        @scroll.window="scrolled = window.scrollY > 50"
        :class="scrolled ? 'bg-[#0a3d22] shadow-lg' : 'bg-gradient-to-b from-black/40 to-transparent'"
        class="fixed top-0 inset-x-0 z-40 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center group-hover:bg-white/25 transition-colors">
                    <x-application-logo class="w-6 h-6 fill-current text-white"/>
                </div>
                <div class="hidden sm:block">
                    <p class="text-white font-extrabold text-sm leading-tight">SIMAK</p>
                    <p class="text-white/70 text-[9px] uppercase tracking-widest leading-tight">Universitas Muhammadiyah Lampung</p>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-1">
                <a href="#beranda" class="px-3 py-2 text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-colors">Beranda</a>
                <a href="#berita" class="px-3 py-2 text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-colors">Berita</a>
                <a href="#tentang" class="px-3 py-2 text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-colors">Tentang</a>
            </nav>

            @auth
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-[#0d4a2a] bg-yellow-300 hover:bg-yellow-200 shadow-md transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-[#0d4a2a] bg-yellow-300 hover:bg-yellow-200 shadow-md transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span class="hidden sm:inline">Masuk</span>
                </a>
            @endauth
        </div>
    </div>
</header>

{{-- ════════════════════════════════════════════════════════════
     HERO SLIDER
════════════════════════════════════════════════════════════ --}}
<section id="beranda" class="relative">
    @if ($sliders->isNotEmpty())
        <div x-data="{
                current: 0,
                total:   {{ $sliders->count() }},
                interval: null,
                next()  { this.current = (this.current + 1) % this.total; },
                prev()  { this.current = (this.current - 1 + this.total) % this.total; },
                start() { this.interval = setInterval(() => this.next(), 6000); },
                stop()  { clearInterval(this.interval); this.interval = null; },
             }"
             x-init="start()"
             @mouseenter="stop()" @mouseleave="start()"
             class="relative h-[70vh] sm:h-[78vh] lg:h-[88vh] min-h-[420px] overflow-hidden">

            @foreach ($sliders as $i => $s)
                <div x-show="current === {{ $i }}"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute inset-0">

                    <img src="{{ $s->imageUrl() }}" alt="{{ $s->title }}"
                         class="absolute inset-0 w-full h-full object-cover">

                    <div class="absolute inset-0 bg-gradient-to-r from-[#062414]/95 via-[#0a3d22]/70 to-[#0a3d22]/30"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

                    <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
                        <div class="max-w-2xl text-white">
                            <span class="inline-block px-3 py-1 rounded-full bg-yellow-400/20 border border-yellow-300/30 text-yellow-200 text-xs font-semibold uppercase tracking-widest mb-4">
                                Slide #{{ $i + 1 }}
                            </span>
                            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold leading-tight drop-shadow-lg">
                                {{ $s->title }}
                            </h1>
                            @if ($s->subtitle)
                                <p class="mt-4 text-base sm:text-lg text-white/90 leading-relaxed drop-shadow">
                                    {{ $s->subtitle }}
                                </p>
                            @endif
                            @if ($s->link_url)
                                <a href="{{ $s->link_url }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-2 mt-6 px-6 py-3 rounded-xl text-sm font-semibold text-[#0d4a2a]
                                          bg-yellow-300 hover:bg-yellow-200 shadow-xl transition-all hover:scale-105">
                                    {{ $s->link_label ?? 'Pelajari Lebih Lanjut' }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            @if ($sliders->count() > 1)
                <button @click="prev()"
                        class="hidden md:flex absolute left-4 lg:left-6 top-1/2 -translate-y-1/2 z-10
                               w-12 h-12 rounded-full bg-white/15 backdrop-blur-sm hover:bg-white/30 border border-white/20
                               items-center justify-center text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button @click="next()"
                        class="hidden md:flex absolute right-4 lg:right-6 top-1/2 -translate-y-1/2 z-10
                               w-12 h-12 rounded-full bg-white/15 backdrop-blur-sm hover:bg-white/30 border border-white/20
                               items-center justify-center text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>

                <div class="absolute bottom-6 inset-x-0 flex items-center justify-center gap-2 z-10">
                    @foreach ($sliders as $i => $s)
                        <button @click="current = {{ $i }}"
                                :class="current === {{ $i }} ? 'bg-yellow-300 w-10' : 'bg-white/40 hover:bg-white/60 w-2.5'"
                                class="h-2.5 rounded-full transition-all duration-300"></button>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        <div class="relative h-[60vh] min-h-[400px] bg-gradient-to-br from-[#062414] via-[#0a3d22] to-[#1a6b3e] flex items-center">
            <div class="absolute inset-0 opacity-30"
                 style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 40px 40px;"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
                <h1 class="text-4xl sm:text-6xl font-extrabold drop-shadow-lg">SIMAK <span class="text-yellow-300">UML</span></h1>
                <p class="mt-4 text-lg text-white/85 max-w-2xl">Sistem Informasi Akademik Universitas Muhammadiyah Lampung</p>
            </div>
        </div>
    @endif
</section>

{{-- ════════════════════════════════════════════════════════════
     STATS BAR
════════════════════════════════════════════════════════════ --}}
<section class="bg-white dark:bg-gray-900 border-y border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach ([
                ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'value' => 'AMAN', 'label' => 'Sertifikasi 2FA'],
                ['icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z', 'value' => '24/7', 'label' => 'Akses Online'],
                ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'value' => 'BAIK', 'label' => 'Akreditasi'],
                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'value' => 'AKTIF', 'label' => 'Pengguna Sistem'],
            ] as $s)
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto rounded-xl bg-[#0d4a2a]/10 dark:bg-emerald-900/20 flex items-center justify-center mb-2">
                        <svg class="w-6 h-6 text-[#0d4a2a] dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/>
                        </svg>
                    </div>
                    <p class="text-xl sm:text-2xl font-extrabold text-gray-900 dark:text-white">{{ $s['value'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $s['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════
     BERITA
════════════════════════════════════════════════════════════ --}}
<section id="berita" class="py-12 sm:py-16 bg-gray-50 dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-end justify-between flex-wrap gap-4 mb-8">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-[#0d4a2a]/10 dark:bg-emerald-900/30 text-[#0d4a2a] dark:text-emerald-400 text-xs font-bold uppercase tracking-widest mb-2">
                    Informasi Terkini
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white">
                    Berita & <span class="text-[#0d4a2a] dark:text-emerald-400">Pengumuman</span>
                </h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Update terbaru dari Universitas Muhammadiyah Lampung.
                </p>
            </div>
            @if ($featuredNews || $latestNews->isNotEmpty())
                <a href="{{ route('news.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#0d4a2a] dark:text-emerald-400 hover:underline">
                    Lihat Semua Berita
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            @endif
        </div>

        @if ($featuredNews)
            <div class="grid lg:grid-cols-3 gap-6">

                <a href="{{ route('news.show', $featuredNews->slug) }}"
                   class="lg:col-span-2 group block rounded-2xl overflow-hidden bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-xl transition-all">
                    <div class="aspect-[16/9] bg-gray-100 dark:bg-gray-800 relative overflow-hidden">
                        @if ($featuredNews->thumbnail_path)
                            <img src="{{ $featuredNews->thumbnailUrl() }}" alt="{{ $featuredNews->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#0a3d22] to-[#1a6b3e] flex items-center justify-center">
                                <svg class="w-20 h-20 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4">
                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-yellow-300 text-[#0d4a2a] shadow-md">
                                ⭐ Utama
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400 mb-2">
                            @if ($featuredNews->category)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#0d4a2a]/10 dark:bg-emerald-900/30 text-[#0d4a2a] dark:text-emerald-400 capitalize">
                                    {{ $featuredNews->category }}
                                </span>
                            @endif
                            <span>{{ $featuredNews->published_at?->translatedFormat('d M Y') }}</span>
                            <span>· 👁 {{ $featuredNews->views }}</span>
                        </div>
                        <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white group-hover:text-[#0d4a2a] dark:group-hover:text-emerald-400 transition-colors line-clamp-2">
                            {{ $featuredNews->title }}
                        </h3>
                        <p class="mt-3 text-sm text-gray-600 dark:text-gray-400 line-clamp-3 leading-relaxed">
                            {{ $featuredNews->excerpt }}
                        </p>
                        <div class="mt-4 flex items-center gap-1.5 text-sm font-semibold text-[#0d4a2a] dark:text-emerald-400">
                            Baca selengkapnya
                            <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                    </div>
                </a>

                <div class="space-y-4">
                    @forelse ($latestNews as $n)
                        <a href="{{ route('news.show', $n->slug) }}"
                           class="group flex gap-3 p-3 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-[#0d4a2a]/30 hover:shadow-md transition-all">
                            <div class="w-24 h-20 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800 flex-shrink-0">
                                @if ($n->thumbnail_path)
                                    <img src="{{ $n->thumbnailUrl() }}" alt="" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[#0a3d22] to-[#1a6b3e] flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                @if ($n->category)
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#0d4a2a] dark:text-emerald-400 capitalize">
                                        {{ $n->category }}
                                    </span>
                                @endif
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-2 group-hover:text-[#0d4a2a] dark:group-hover:text-emerald-400 transition-colors leading-snug">
                                    {{ $n->title }}
                                </h4>
                                <p class="mt-1 text-[11px] text-gray-400">
                                    {{ $n->published_at?->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <p class="text-sm text-gray-400 italic">Belum ada berita lain.</p>
                    @endforelse
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-900 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-800 p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada berita yang dipublikasikan.</p>
            </div>
        @endif
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════
     TENTANG / CTA
════════════════════════════════════════════════════════════ --}}
<section id="tentang" class="relative py-16 bg-gradient-to-br from-[#062414] via-[#0a3d22] to-[#1a6b3e] text-white overflow-hidden">
    <div class="absolute inset-0 opacity-30"
         style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 36px 36px;"></div>
    <div class="absolute -top-20 -left-20 w-96 h-96 bg-emerald-400/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-yellow-300/10 rounded-full blur-3xl"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block px-3 py-1 rounded-full bg-yellow-300/20 border border-yellow-300/30 text-yellow-200 text-xs font-bold uppercase tracking-widest mb-4">
            Tentang SIMAK
        </span>
        <h2 class="text-3xl sm:text-4xl font-extrabold leading-tight">
            Sistem Informasi Akademik
            <span class="block text-yellow-300 mt-1">Universitas Muhammadiyah Lampung</span>
        </h2>
        <p class="mt-4 max-w-2xl mx-auto text-base text-white/85 leading-relaxed">
            Platform digital untuk mengelola dokumen akreditasi, arsip akademik,
            dan informasi institusional secara terpusat. Dilengkapi dengan
            keamanan berlapis, kontrol akses berbasis peran, audit log lengkap,
            dan autentikasi dua faktor.
        </p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-[#0d4a2a]
                          bg-yellow-300 hover:bg-yellow-200 shadow-xl transition-all hover:scale-105">
                    Buka Dashboard
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-[#0d4a2a]
                          bg-yellow-300 hover:bg-yellow-200 shadow-xl transition-all hover:scale-105">
                    Masuk ke Sistem
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                </a>
            @endauth
            <a href="{{ route('news.index') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-white
                      bg-white/10 hover:bg-white/20 border border-white/20 transition-all">
                Baca Berita
            </a>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════════════ --}}
<footer class="bg-[#062414] text-white/70 border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center">
                    <x-application-logo class="w-5 h-5 fill-current text-white"/>
                </div>
                <div>
                    <p class="text-white font-bold text-sm">SIMAK UML</p>
                    <p class="text-[11px] text-white/50">Sistem Informasi Akademik</p>
                </div>
            </div>
            <p class="text-xs text-white/50">
                © {{ date('Y') }} Universitas Muhammadiyah Lampung. All rights reserved.
            </p>
        </div>
    </div>
</footer>

</body>
</html>
