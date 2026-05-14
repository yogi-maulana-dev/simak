<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' — SIMAK UML' : config('app.name', 'SIMAK UML') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --uml-green-dark:  #0d4a2a;
                --uml-green:       #1a6b3e;
                --uml-green-mid:   #22873e;
                --uml-green-light: #34a85a;
            }

            /* Custom scrollbar */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #1a6b3e55; border-radius: 99px; }
            ::-webkit-scrollbar-thumb:hover { background: #1a6b3e99; }

            /* No scrollbar utility */
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 h-full">

        {{-- ── Navigation ── --}}
        @include('layouts.navigation')

        {{-- ── Page Heading (opsional) ── --}}
        @isset($header)
            <header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center gap-3">
                    <div class="w-1 h-6 rounded-full bg-gradient-to-b from-[#1a6b3e] to-[#22873e]"></div>
                    <h1 class="text-lg font-bold text-gray-800 dark:text-gray-100">
                        {{ $header }}
                    </h1>
                </div>
            </header>
        @endisset

        {{-- ── Main Content ── --}}
        <main class="min-h-[calc(100vh-4rem)]">
            {{ $slot }}
        </main>

        {{-- ── Footer ── --}}
        <footer class="bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 py-4 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded bg-[#1a6b3e] flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-500">
                        SIMAK — Sistem Informasi Manajemen Akademik
                    </span>
                </div>
                <p class="text-xs text-gray-400 dark:text-gray-600">
                    © {{ date('Y') }} Universitas Muhammadiyah Lampung
                </p>
            </div>
        </footer>

    <script>
        (function () {
            if (!navigator.geolocation) return;
            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    var exp = new Date(Date.now() + 15 * 60 * 1000).toUTCString();
                    document.cookie = '_geo_lat=' + pos.coords.latitude  + '; path=/; expires=' + exp + '; SameSite=Strict';
                    document.cookie = '_geo_lng=' + pos.coords.longitude + '; path=/; expires=' + exp + '; SameSite=Strict';
                    document.cookie = '_geo_acc=' + pos.coords.accuracy  + '; path=/; expires=' + exp + '; SameSite=Strict';
                },
                null,
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 300000 }
            );
        })();
    </script>
    </body>
</html>
