<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shareable->original_name }} — SIMAK</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 flex flex-col items-center justify-center p-4 min-h-screen">

    <div class="w-full max-w-lg">
        {{-- Logo / Brand --}}
        <div class="text-center mb-8">
            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">SIMAK</p>
            <p class="text-xs text-gray-400 dark:text-gray-600">File Berbagi</p>
        </div>

        {{-- Card file --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xl overflow-hidden">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-4">
                @php
                    $ext = $shareable->extension();
                    $iconColor = match($ext) {
                        'pdf'        => 'text-red-500',
                        'doc','docx' => 'text-blue-600',
                        'xls','xlsx' => 'text-green-600',
                        'ppt','pptx' => 'text-orange-500',
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
                    <svg class="w-14 h-14 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span class="absolute bottom-1 left-1/2 -translate-x-1/2 text-[9px] font-bold text-white leading-none">
                        {{ $iconLabel }}
                    </span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-gray-900 dark:text-white text-sm truncate" title="{{ $shareable->original_name }}">
                        {{ $shareable->original_name }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $shareable->formattedSize() }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        Dibagikan oleh <span class="font-medium text-gray-600 dark:text-gray-300">{{ $link->creator->name ?? '—' }}</span>
                    </p>
                </div>
            </div>

            {{-- Info --}}
            <div class="px-6 py-4 space-y-2 bg-gray-50 dark:bg-gray-900/40">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-500 dark:text-gray-400">Izin akses</span>
                    @if ($link->permission === 'download')
                        <span class="inline-flex items-center gap-1 text-green-700 dark:text-green-400 font-medium">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Lihat &amp; Unduh
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-blue-700 dark:text-blue-400 font-medium">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat saja
                        </span>
                    @endif
                </div>
                @if ($link->expires_at)
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500 dark:text-gray-400">Kedaluwarsa</span>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">
                            {{ $link->expires_at->translatedFormat('d M Y, H:i') }}
                        </span>
                    </div>
                @else
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500 dark:text-gray-400">Kedaluwarsa</span>
                        <span class="text-gray-400 dark:text-gray-500">Tidak ada batas</span>
                    </div>
                @endif
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-500 dark:text-gray-400">Dilihat</span>
                    <span class="text-gray-700 dark:text-gray-300 font-medium">{{ number_format($link->access_count) }}×</span>
                </div>
            </div>

            {{-- Aksi --}}
            <div class="px-6 py-5">
                @if ($link->permission === 'download')
                    <a href="{{ route('share.download', [$link->token, $shareable->id]) }}"
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-3 rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Unduh File
                    </a>
                @else
                    <div class="flex items-center justify-center gap-2 text-sm text-gray-500 dark:text-gray-400 py-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                        Unduhan tidak diizinkan untuk link ini
                    </div>
                @endif
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 dark:text-gray-600 mt-6">
            &copy; {{ date('Y') }} SIMAK · Sistem Informasi Akademik
        </p>
    </div>

</body>
</html>
