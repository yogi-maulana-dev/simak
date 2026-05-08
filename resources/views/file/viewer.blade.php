<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $file->original_name }} — Pratinjau SIMAK</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Sembunyikan toolbar download bawaan browser pada PDF iframe */
        iframe { border: none; }

        /* Nonaktifkan selection di seluruh halaman */
        body { -webkit-user-select: none; user-select: none; }
    </style>
</head>
<body
    class="font-sans antialiased h-full bg-gray-950 flex flex-col"
    style="height:100dvh"

    {{-- Blokir klik kanan di seluruh halaman --}}
    oncontextmenu="return false"
>

{{-- ── Top bar ──────────────────────────────────────────────────────────── --}}
<header class="flex-shrink-0 h-11 bg-gray-900 border-b border-gray-800 px-4 flex items-center gap-3">

    {{-- Tombol kembali --}}
    <button onclick="window.close(); history.back();"
            class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700 transition-colors flex-shrink-0"
            title="Kembali">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    <div class="w-px h-4 bg-gray-700 flex-shrink-0"></div>

    {{-- Ikon & nama file --}}
    <div class="flex items-center gap-2 min-w-0 flex-1">
        @php
            $ext = $file->extension();
            $iconColor = match($ext) {
                'pdf'        => 'text-red-400',
                'doc','docx' => 'text-blue-400',
                'xls','xlsx' => 'text-green-400',
                'ppt','pptx' => 'text-orange-400',
                default      => 'text-gray-400',
            };
            $iconLabel = match($ext) {
                'pdf'        => 'PDF',
                'doc','docx' => 'DOC',
                'xls','xlsx' => 'XLS',
                'ppt','pptx' => 'PPT',
                default      => strtoupper($ext),
            };
        @endphp
        <div class="relative flex-shrink-0">
            <svg class="w-6 h-6 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                      clip-rule="evenodd"/>
            </svg>
            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 text-[6px] font-bold text-white leading-none">
                {{ $iconLabel }}
            </span>
        </div>
        <span class="text-sm font-medium text-white truncate">{{ $file->original_name }}</span>
        <span class="flex-shrink-0 text-xs text-gray-500">{{ $file->formattedSize() }}</span>
    </div>

    {{-- Badge Lihat Saja --}}
    <span class="flex-shrink-0 inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold
                 text-amber-400 bg-amber-400/10 border border-amber-400/20 rounded-full">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                     -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        Lihat Saja
    </span>
</header>

{{-- ── Viewer area ──────────────────────────────────────────────────────── --}}
<div class="flex-1 overflow-hidden relative">

    @if ($isOffice)
        {{-- Office: MS Office Online Viewer --}}
        <iframe
            src="{{ $viewerUrl }}"
            class="w-full h-full"
            allowfullscreen
            sandbox="allow-scripts allow-same-origin allow-forms allow-popups"
        ></iframe>
    @else
        {{-- PDF: iframe inline stream --}}
        <iframe
            src="{{ $viewerUrl }}#toolbar=0&navpanes=0&scrollbar=1&view=FitH"
            class="w-full h-full"
            allowfullscreen
        ></iframe>
    @endif

    {{-- Overlay transparan di atas iframe untuk blokir klik kanan pada konten --}}
    <div class="absolute inset-0 pointer-events-none select-none" style="z-index:1"></div>
</div>

{{-- ── Footer info ──────────────────────────────────────────────────────── --}}
<div class="flex-shrink-0 h-7 bg-gray-900 border-t border-gray-800 flex items-center justify-center">
    <p class="text-[11px] text-gray-600">
        Dokumen ini hanya untuk ditampilkan — © SIMAK UML {{ date('Y') }}
    </p>
</div>

<script>
    // Blokir shortcut download / print / save
    document.addEventListener('keydown', function (e) {
        // Ctrl/Cmd + S, P, Shift+P, U
        if ((e.ctrlKey || e.metaKey) && ['s','p','u'].includes(e.key.toLowerCase())) {
            e.preventDefault();
            return false;
        }
        // F12 (DevTools) — sekedar deterrent
        if (e.key === 'F12') {
            e.preventDefault();
            return false;
        }
    });

    // Blokir drag
    document.addEventListener('dragstart', e => e.preventDefault());
</script>

</body>
</html>
