<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita — SIMAK UML</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-950 text-gray-800 dark:text-gray-200">

{{-- Nav simple --}}
<header class="bg-[#0a3d22] shadow-md sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
        <a href="/" class="flex items-center gap-2 text-white">
            <x-application-logo class="w-6 h-6 fill-current"/>
            <span class="font-extrabold text-sm">SIMAK UML</span>
        </a>
        <nav class="flex items-center gap-4 text-sm text-white/80">
            <a href="/" class="hover:text-white transition-colors">Beranda</a>
            <a href="{{ route('news.index') }}" class="text-yellow-300 font-semibold">Berita</a>
        </nav>
    </div>
</header>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Berita & Pengumuman</h1>
        <p class="text-sm text-gray-500 mt-1">Informasi terkini dari Universitas Muhammadiyah Lampung</p>
    </div>

    @if ($news->isEmpty())
        <div class="bg-white dark:bg-gray-900 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-800 p-16 text-center">
            <p class="text-gray-400">Belum ada berita yang dipublikasikan.</p>
        </div>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($news as $item)
                <a href="{{ route('news.show', $item->slug) }}"
                   class="group block bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden hover:shadow-xl transition-all">
                    <div class="aspect-[16/9] bg-gray-100 dark:bg-gray-800 overflow-hidden">
                        @if ($item->thumbnail_path)
                            <img src="{{ $item->thumbnailUrl() }}" alt="{{ $item->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#0a3d22] to-[#1a6b3e] flex items-center justify-center">
                                <svg class="w-12 h-12 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex items-center gap-2 text-[11px] text-gray-400 mb-2">
                            @if ($item->category)
                                <span class="px-2 py-0.5 rounded-full bg-[#0d4a2a]/10 text-[#0d4a2a] dark:text-emerald-400 font-semibold capitalize">{{ $item->category }}</span>
                            @endif
                            <span>{{ $item->published_at?->translatedFormat('d M Y') }}</span>
                            <span>· 👁 {{ $item->views }}</span>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-[#0d4a2a] dark:group-hover:text-emerald-400 transition-colors line-clamp-2 leading-snug">
                            {{ $item->title }}
                        </h3>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed">{{ $item->excerpt }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $news->links() }}
        </div>
    @endif
</div>

<footer class="bg-[#062414] text-white/60 border-t border-white/10 mt-12">
    <div class="max-w-7xl mx-auto px-4 py-6 text-center text-xs">
        © {{ date('Y') }} Universitas Muhammadiyah Lampung. All rights reserved.
    </div>
</footer>

</body>
</html>