<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $news->title }} — SIMAK UML</title>
    <meta name="description" content="{{ $news->excerpt }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-950 text-gray-800 dark:text-gray-200">

<header class="bg-[#0a3d22] shadow-md sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
        <a href="/" class="flex items-center gap-2 text-white">
            <x-application-logo class="w-6 h-6 fill-current"/>
            <span class="font-extrabold text-sm">SIMAK UML</span>
        </a>
        <nav class="flex items-center gap-4 text-sm text-white/80">
            <a href="/" class="hover:text-white transition-colors">Beranda</a>
            <a href="{{ route('news.index') }}" class="hover:text-white transition-colors">Berita</a>
        </nav>
    </div>
</header>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-gray-400 mb-6">
        <a href="/" class="hover:text-[#0d4a2a]">Beranda</a>
        <span>/</span>
        <a href="{{ route('news.index') }}" class="hover:text-[#0d4a2a]">Berita</a>
        <span>/</span>
        <span class="text-gray-600 dark:text-gray-300 truncate max-w-[200px]">{{ $news->title }}</span>
    </nav>

    {{-- Article --}}
    <article class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">

        {{-- Thumbnail --}}
        @if ($news->thumbnail_path)
            <div class="aspect-[16/6] overflow-hidden">
                <img src="{{ $news->thumbnailUrl() }}" alt="{{ $news->title }}"
                     class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-6 sm:p-8 lg:p-10">
            {{-- Meta --}}
            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-400 mb-4">
                @if ($news->category)
                    <span class="inline-block px-2.5 py-1 rounded-full bg-[#0d4a2a]/10 dark:bg-emerald-900/30 text-[#0d4a2a] dark:text-emerald-400 font-semibold capitalize">
                        {{ $news->category }}
                    </span>
                @endif
                <span>{{ $news->published_at?->translatedFormat('d F Y') }}</span>
                <span>· 👁 {{ number_format($news->views) }} tayangan</span>
            </div>

            {{-- Title --}}
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white leading-tight mb-4">
                {{ $news->title }}
            </h1>

            @if ($news->excerpt)
                <p class="text-base text-gray-500 dark:text-gray-400 italic border-l-4 border-[#0d4a2a] pl-4 mb-6 leading-relaxed">
                    {{ $news->excerpt }}
                </p>
            @endif

            <hr class="border-gray-100 dark:border-gray-800 mb-6">

            {{-- Body --}}
            <div class="prose prose-sm sm:prose dark:prose-invert max-w-none
                        prose-headings:font-bold prose-headings:text-gray-900 dark:prose-headings:text-white
                        prose-a:text-[#0d4a2a] dark:prose-a:text-emerald-400
                        prose-img:rounded-xl">
                {!! nl2br(e($news->body)) !!}
            </div>
        </div>
    </article>

    {{-- Related --}}
    @if ($related->isNotEmpty())
        <div class="mt-10">
            <h2 class="text-lg font-extrabold text-gray-900 dark:text-white mb-4">Berita Terkait</h2>
            <div class="grid sm:grid-cols-3 gap-4">
                @foreach ($related as $item)
                    <a href="{{ route('news.show', $item->slug) }}"
                       class="group block bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden hover:shadow-lg transition-all">
                        <div class="aspect-[16/9] bg-gray-100 dark:bg-gray-800 overflow-hidden">
                            @if ($item->thumbnail_path)
                                <img src="{{ $item->thumbnailUrl() }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#0a3d22] to-[#1a6b3e]"></div>
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="text-xs text-gray-400 mb-1">{{ $item->published_at?->format('d/m/Y') }}</p>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-[#0d4a2a] transition-colors line-clamp-2 leading-snug">
                                {{ $item->title }}
                            </h4>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('news.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-[#0d4a2a] dark:text-emerald-400 hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Berita
        </a>
    </div>
</div>

<footer class="bg-[#062414] text-white/60 border-t border-white/10 mt-12">
    <div class="max-w-7xl mx-auto px-4 py-6 text-center text-xs">
        © {{ date('Y') }} Universitas Muhammadiyah Lampung. All rights reserved.
    </div>
</footer>

</body>
</html>