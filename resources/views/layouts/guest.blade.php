<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0d4a2a">

    <title>{{ $title ?? 'SIMAK' }} — Universitas Muhammadiyah Lampung</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes blob {
            0%   { transform: translate(0, 0) scale(1); }
            33%  { transform: translate(30px, -50px) scale(1.1); }
            66%  { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0, 0) scale(1); }
        }
        .animate-blob       { animation: blob 12s infinite; }
        .animation-delay-2  { animation-delay: 2s; }
        .animation-delay-4  { animation-delay: 4s; }

        .bg-uml-pattern {
            background-image:
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.05) 1px, transparent 1px),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 32px 32px;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-950">

<div class="min-h-screen flex flex-col lg:flex-row">

    {{-- LEFT SIDE --}}
    <aside class="relative hidden lg:flex lg:w-[45%] xl:w-[42%]
              bg-[#062414]
              flex-col p-12 overflow-hidden">

    <!-- Background gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-black/40 via-black/25 to-black/50"></div>

    <!-- Pattern halus -->
    <div class="absolute inset-0 opacity-40"
         style="background-image:
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.06) 1px, transparent 1px),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.06) 1px, transparent 1px);
                background-size: 34px 34px;">
    </div>

    <!-- Blob ornamen -->
    <div class="absolute top-0 -left-10 w-72 h-72 bg-emerald-400/20 rounded-full blur-3xl animate-blob"></div>
    <div class="absolute bottom-10 right-0 w-80 h-80 bg-amber-300/15 rounded-full blur-3xl animate-blob animation-delay-2"></div>
    <div class="absolute top-1/3 left-1/3 w-72 h-72 bg-lime-200/10 rounded-full blur-3xl animate-blob animation-delay-4"></div>

    <!-- Content -->
    <div class="relative z-10 flex flex-col h-full">

        <!-- Logo + Brand -->
        <a href="/" class="flex items-center gap-3 group">
            <div class="w-14 h-14 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center
                        group-hover:bg-white/25 transition-colors duration-200 shadow-lg">
                <x-application-logo class="w-9 h-9 fill-current text-white"/>
            </div>
            <div>
                <p class="text-white font-extrabold text-xl leading-tight tracking-wide">SIMAK</p>
                <p class="text-white/70 text-[11px] leading-tight tracking-widest uppercase">
                    Universitas Muhammadiyah Lampung
                </p>
            </div>
        </a>

        <!-- Tagline -->
        <div class="flex-1 flex flex-col justify-center max-w-md">
            <h1 class="text-4xl xl:text-5xl font-extrabold text-white leading-tight tracking-tight">
                Sistem
                <span class="block text-amber-200 drop-shadow-sm">
                    Informasi Akademik
                </span>
            </h1>

            <p class="mt-5 text-[15px] text-white/80 leading-relaxed">
                Kelola dokumen akreditasi dan arsip akademik secara terpusat,
                aman, dan mudah diakses kapan saja.
            </p>

            <!-- Fitur unggulan -->
            <div class="mt-8 space-y-4">
                @foreach ([
                    ['icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z', 'text' => 'Manajemen file & folder akreditasi'],
                    ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'text' => 'Berbagi aman dengan kontrol akses'],
                    ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'text' => 'Audit log & autentikasi 2 faktor'],
                ] as $f)
                    <div class="flex items-center gap-3 text-white/85">
                        <div class="w-9 h-9 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium leading-snug">
                            {{ $f['text'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Bottom -->
        <div class="border-t border-white/15 pt-6">
            <p class="text-xs text-white/65 italic leading-relaxed">
                "Sebaik-baik manusia adalah yang paling bermanfaat bagi sesamanya"
            </p>
            <p class="mt-2 text-[11px] text-white/45">
                © {{ date('Y') }} Universitas Muhammadiyah Lampung
            </p>
        </div>

    </div>
</aside>

    {{-- RIGHT SIDE --}}
    <main class="flex-1 flex flex-col">

        {{-- Header mobile --}}
        <header class="lg:hidden bg-gradient-to-r from-[#062b18] to-[#0f5b33] px-5 py-4 flex items-center gap-3 shadow-lg">
            <div class="w-10 h-10 rounded-xl bg-white/20 border border-white/25 flex items-center justify-center shadow-md">
                <x-application-logo class="w-6 h-6 fill-current text-white"/>
            </div>
            <div>
                <p class="text-white font-extrabold text-base leading-tight">SIMAK</p>
                <p class="text-white/70 text-[10px] uppercase tracking-widest leading-tight">
                    Universitas Muhammadiyah Lampung
                </p>
            </div>
        </header>

        {{-- Form area --}}
        <div class="flex-1 flex items-center justify-center p-5 sm:p-8 lg:p-10 bg-gray-50 dark:bg-gray-950">
            <div class="w-full max-w-md">

                {{-- Card Form --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200/70 dark:border-gray-800 p-7">
                    {{ $slot }}
                </div>

                {{-- Small text info --}}
                <p class="mt-5 text-center text-xs text-gray-500 dark:text-gray-400">
                    Pastikan akun Anda terdaftar untuk dapat mengakses sistem.
                </p>

            </div>
        </div>

        {{-- Footer mobile --}}
        <footer class="lg:hidden px-5 py-4 text-center text-[11px] text-gray-500 dark:text-gray-500 bg-gray-50 dark:bg-gray-950 border-t border-gray-200 dark:border-gray-800">
            © {{ date('Y') }} Universitas Muhammadiyah Lampung
        </footer>
    </main>

</div>

</body>
</html>