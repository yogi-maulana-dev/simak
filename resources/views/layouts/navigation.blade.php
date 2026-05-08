<nav x-data="{ open: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 10)"
     :class="scrolled ? 'shadow-lg' : 'shadow-sm'"
     class="bg-gradient-to-r from-[#0d4a2a] to-[#1a6b3e] border-b border-[#0a3d22] sticky top-0 z-50 transition-shadow duration-300">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- ── Logo & Brand ── --}}
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    {{-- Logo UML placeholder — ganti src dengan logo asli --}}
                    <div class="w-9 h-9 rounded-lg bg-white/15 border border-white/20 flex items-center justify-center group-hover:bg-white/25 transition-colors duration-200">
                        <x-application-logo class="w-6 h-6 fill-current text-white" />
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-white font-bold text-sm leading-tight tracking-wide">SIMAK</p>
                        <p class="text-green-200/70 text-[10px] leading-tight tracking-wider uppercase">Universitas Muhammadiyah Lampung</p>
                    </div>
                </a>

                {{-- Divider --}}
                <div class="hidden sm:block w-px h-8 bg-white/20"></div>

                {{-- Desktop Nav Links --}}
                <div class="hidden sm:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                       @class([
                           'flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                           'bg-white/20 text-white shadow-sm' => request()->routeIs('dashboard'),
                           'text-green-100 hover:bg-white/10 hover:text-white' => !request()->routeIs('dashboard'),
                       ])>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('explorer') }}"
                       @class([
                           'flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                           'bg-white/20 text-white shadow-sm' => request()->routeIs('explorer'),
                           'text-green-100 hover:bg-white/10 hover:text-white' => !request()->routeIs('explorer'),
                       ])>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        Explorer
                    </a>

                    @if (auth()->user()->isSuperAdmin())
                        {{-- Admin dropdown --}}
                        <div class="relative" x-data="{ adminOpen: false }" @click.outside="adminOpen = false">
                            <button @click="adminOpen = !adminOpen"
                                    @class([
                                        'flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                                        'bg-white/20 text-white shadow-sm' => request()->routeIs('admin.*'),
                                        'text-green-100 hover:bg-white/10 hover:text-white' => !request()->routeIs('admin.*'),
                                    ])>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Admin
                                <svg class="w-3 h-3 text-green-200/70 transition-transform duration-200"
                                     :class="adminOpen ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="adminOpen"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute left-0 mt-2 w-52 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50"
                                 style="display:none">

                                {{-- Konten --}}
                                <div class="px-2 py-1.5 border-b border-gray-100 dark:border-gray-700">
                                    <p class="px-2 py-1 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Konten</p>
                                    <a href="{{ route('admin.news') }}"
                                       @class([
                                           'flex items-center gap-2.5 px-2 py-2 rounded-xl text-sm transition-colors',
                                           'bg-[#0d4a2a]/10 text-[#0d4a2a] dark:bg-emerald-900/30 dark:text-emerald-400 font-semibold' => request()->routeIs('admin.news'),
                                           'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' => !request()->routeIs('admin.news'),
                                       ])>
                                        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                        Kelola Berita
                                    </a>
                                    <a href="{{ route('admin.sliders') }}"
                                       @class([
                                           'flex items-center gap-2.5 px-2 py-2 rounded-xl text-sm transition-colors',
                                           'bg-[#0d4a2a]/10 text-[#0d4a2a] dark:bg-emerald-900/30 dark:text-emerald-400 font-semibold' => request()->routeIs('admin.sliders'),
                                           'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' => !request()->routeIs('admin.sliders'),
                                       ])>
                                        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Kelola Slider
                                    </a>
                                    <a href="{{ route('admin.arsip') }}"
                                       @class([
                                           'flex items-center gap-2.5 px-2 py-2 rounded-xl text-sm transition-colors',
                                           'bg-[#0d4a2a]/10 text-[#0d4a2a] dark:bg-emerald-900/30 dark:text-emerald-400 font-semibold' => request()->routeIs('admin.arsip'),
                                           'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' => !request()->routeIs('admin.arsip'),
                                       ])>
                                        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                        </svg>
                                        Link Arsip
                                    </a>
                                </div>

                                {{-- Pengguna & Sistem --}}
                                <div class="px-2 py-1.5">
                                    <p class="px-2 py-1 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Sistem</p>
                                    <a href="{{ route('admin.folder-permissions') }}"
                                       @class([
                                           'flex items-center gap-2.5 px-2 py-2 rounded-xl text-sm transition-colors',
                                           'bg-[#0d4a2a]/10 text-[#0d4a2a] dark:bg-emerald-900/30 dark:text-emerald-400 font-semibold' => request()->routeIs('admin.folder-permissions'),
                                           'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' => !request()->routeIs('admin.folder-permissions'),
                                       ])>
                                        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        Kelola Akses
                                    </a>
                                    <a href="{{ route('admin.users') }}"
                                       @class([
                                           'flex items-center gap-2.5 px-2 py-2 rounded-xl text-sm transition-colors',
                                           'bg-[#0d4a2a]/10 text-[#0d4a2a] dark:bg-emerald-900/30 dark:text-emerald-400 font-semibold' => request()->routeIs('admin.users'),
                                           'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' => !request()->routeIs('admin.users'),
                                       ])>
                                        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        Kelola User
                                    </a>
                                    <a href="{{ route('admin.activity-logs') }}"
                                       @class([
                                           'flex items-center gap-2.5 px-2 py-2 rounded-xl text-sm transition-colors',
                                           'bg-[#0d4a2a]/10 text-[#0d4a2a] dark:bg-emerald-900/30 dark:text-emerald-400 font-semibold' => request()->routeIs('admin.activity-logs'),
                                           'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' => !request()->routeIs('admin.activity-logs'),
                                       ])>
                                        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                        Log Aktivitas
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── Right: User Dropdown ── --}}
            <div class="hidden sm:flex items-center gap-3">

                {{-- Badge Role --}}
                <span @class([
                    'hidden md:inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold',
                    'bg-amber-400/20 text-amber-200 border border-amber-400/30' => auth()->user()->isSuperAdmin(),
                    'bg-blue-400/20 text-blue-200 border border-blue-400/30' => auth()->user()->isAdminProdi(),
                    'bg-white/15 text-green-100 border border-white/20' => auth()->user()->role === 'user',
                ])>
                    @if (auth()->user()->isSuperAdmin())
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                    {{ auth()->user()->roleBadge() }}
                </span>

                {{-- Dropdown --}}
                <div class="relative" x-data="{ dropOpen: false }" @click.outside="dropOpen = false">
                    <button @click="dropOpen = !dropOpen"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 hover:border-white/30 transition-all duration-200 group">
                        {{-- Avatar --}}
                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-green-300 to-emerald-500 flex items-center justify-center text-xs font-bold text-white shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-white leading-tight">{{ auth()->user()->name }}</p>
                            @if(auth()->user()->prodi)
                                <p class="text-[10px] text-green-200/70 leading-tight truncate max-w-[120px]">{{ auth()->user()->prodi }}</p>
                            @endif
                        </div>
                        <svg class="w-3.5 h-3.5 text-green-200/70 transition-transform duration-200"
                             :class="dropOpen ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="dropOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50"
                         style="display:none">

                        {{-- User info --}}
                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                        </div>

                        <div class="p-1.5">
                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-green-900/20 hover:text-green-700 dark:hover:text-green-400 transition-colors group">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profil Saya
                            </a>

                            <div class="my-1 h-px bg-gray-100 dark:bg-gray-700"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors group">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Hamburger (Mobile) ── --}}
            <div class="flex items-center sm:hidden">
                <button @click="open = !open"
                        class="p-2 rounded-xl text-green-100 hover:bg-white/15 transition-colors focus:outline-none">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Mobile Menu ── --}}
    <div :class="{'block': open, 'hidden': !open}"
         class="hidden sm:hidden border-t border-white/10 bg-[#0a3d22]">

        {{-- User Info Mobile --}}
        <div class="px-4 py-3 flex items-center gap-3 border-b border-white/10">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-300 to-emerald-500 flex items-center justify-center text-sm font-bold text-white">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                <p class="text-xs text-green-200/60">{{ auth()->user()->email }}</p>
            </div>
        </div>

        {{-- Mobile Nav Links --}}
        <div class="px-3 py-2 space-y-0.5">
            <a href="{{ route('dashboard') }}"
               @class([
                   'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors',
                   'bg-white/20 text-white' => request()->routeIs('dashboard'),
                   'text-green-100 hover:bg-white/10' => !request()->routeIs('dashboard'),
               ])>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('explorer') }}"
               @class([
                   'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors',
                   'bg-white/20 text-white' => request()->routeIs('explorer'),
                   'text-green-100 hover:bg-white/10' => !request()->routeIs('explorer'),
               ])>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                Explorer
            </a>

            @if (auth()->user()->isSuperAdmin())
                {{-- Admin section divider --}}
                <div class="h-px bg-white/10 my-1"></div>
                <p class="px-3 py-1 text-[10px] font-bold text-green-300/50 uppercase tracking-widest">Admin</p>

                {{-- Konten --}}
                <a href="{{ route('admin.news') }}"
                   @class([
                       'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors',
                       'bg-white/20 text-white' => request()->routeIs('admin.news'),
                       'text-green-100 hover:bg-white/10' => !request()->routeIs('admin.news'),
                   ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    Kelola Berita
                </a>
                <a href="{{ route('admin.sliders') }}"
                   @class([
                       'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors',
                       'bg-white/20 text-white' => request()->routeIs('admin.sliders'),
                       'text-green-100 hover:bg-white/10' => !request()->routeIs('admin.sliders'),
                   ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Kelola Slider
                </a>
                <a href="{{ route('admin.arsip') }}"
                   @class([
                       'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors',
                       'bg-white/20 text-white' => request()->routeIs('admin.arsip'),
                       'text-green-100 hover:bg-white/10' => !request()->routeIs('admin.arsip'),
                   ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Link Arsip
                </a>

                <div class="h-px bg-white/10 my-1"></div>

                <a href="{{ route('admin.folder-permissions') }}"
                   @class([
                       'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors',
                       'bg-white/20 text-white' => request()->routeIs('admin.folder-permissions'),
                       'text-green-100 hover:bg-white/10' => !request()->routeIs('admin.folder-permissions'),
                   ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Kelola Akses
                </a>
                <a href="{{ route('admin.users') }}"
                   @class([
                       'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors',
                       'bg-white/20 text-white' => request()->routeIs('admin.users'),
                       'text-green-100 hover:bg-white/10' => !request()->routeIs('admin.users'),
                   ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Kelola User
                </a>
                <a href="{{ route('admin.activity-logs') }}"
                   @class([
                       'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors',
                       'bg-white/20 text-white' => request()->routeIs('admin.activity-logs'),
                       'text-green-100 hover:bg-white/10' => !request()->routeIs('admin.activity-logs'),
                   ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Log Aktivitas
                </a>
            @endif

            <div class="h-px bg-white/10 my-1"></div>

            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium text-green-100 hover:bg-white/10 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profil Saya
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium text-red-300 hover:bg-red-500/10 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
        <div class="pb-2"></div>
    </div>
</nav>
