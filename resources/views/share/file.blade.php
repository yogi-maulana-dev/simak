<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $file->original_name }} — SIMAK</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
        }
    </style>
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 flex items-center justify-center p-4">
    <div class="max-w-lg w-full bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="p-6 text-center">
            <div class="mx-auto w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-1 break-all">{{ $file->original_name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ukuran: {{ $file->formattedSize() }}</p>
            <p class="text-xs text-gray-400 mt-1">Dibagikan dengan izin: <span class="font-semibold">{{ $permission === 'view' ? 'Lihat saja' : 'Lihat & Unduh' }}</span></p>

            <div class="mt-6 flex flex-wrap gap-3 justify-center">
                @if($permission === 'view')
                    <a href="{{ route('share.view', [$token, $file->id]) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Lihat File
                    </a>
                @else
                    <a href="{{ route('share.view', [$token, $file->id]) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Lihat
                    </a>
                    <a href="{{ route('share.download', [$token, $file->id]) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Unduh
                    </a>
                @endif
            </div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-3 text-center text-xs text-gray-500">
            Link ini berlaku hingga {{ $expiresAt ? \Carbon\Carbon::parse($expiresAt)->format('d M Y H:i') : 'selamanya' }}
        </div>
    </div>
</body>
</html>