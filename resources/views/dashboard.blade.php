<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ── Welcome Banner ── --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#0d4a2a] via-[#1a6b3e] to-[#22873e] p-6 sm:p-8 shadow-lg">
                {{-- Dekorasi --}}
                <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-white/5 translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>
                <div class="absolute bottom-0 left-1/2 w-48 h-48 rounded-full bg-white/5 translate-y-1/2 pointer-events-none"></div>

                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-green-200/70 text-sm font-medium mb-1">Selamat datang kembali 👋</p>
                        <h2 class="text-2xl sm:text-3xl font-bold text-white">
                            {{ auth()->user()->name }}
                        </h2>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/15 text-white text-xs font-semibold border border-white/20">
                                {{ auth()->user()->roleBadge() }}
                            </span>
                            @if (auth()->user()->prodi)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/10 text-green-200 text-xs border border-white/15">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    {{ auth()->user()->prodi }}
                                </span>
                            @endif
                            @if (auth()->user()->kode_lamp)
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-white/10 text-green-200/70 text-[11px] font-mono border border-white/10">
                                    {{ auth()->user()->kode_lamp }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('explorer') }}"
                       class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-[#1a6b3e] font-semibold text-sm hover:bg-green-50 transition-colors shadow-lg flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        Buka Explorer
                    </a>
                </div>
            </div>

            {{-- ── Quick Access Cards ── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                {{-- Explorer Card --}}
                <a href="{{ route('explorer') }}"
                   class="group bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-6 hover:border-[#1a6b3e] hover:shadow-lg transition-all duration-200">
                    <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center mb-4 group-hover:bg-[#1a6b3e] transition-colors duration-200">
                        <svg class="w-6 h-6 text-[#1a6b3e] group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Explorer Dokumen</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola folder dan file prodi Anda</p>
                    <div class="mt-4 flex items-center gap-1 text-[#1a6b3e] text-sm font-medium">
                        Buka <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>

                {{-- Profil Card --}}
                <a href="{{ route('profile.edit') }}"
                   class="group bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-6 hover:border-[#1a6b3e] hover:shadow-lg transition-all duration-200">
                    <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center mb-4 group-hover:bg-[#1a6b3e] transition-colors duration-200">
                        <svg class="w-6 h-6 text-[#1a6b3e] group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Profil Saya</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ubah data dan kata sandi akun</p>
                    <div class="mt-4 flex items-center gap-1 text-[#1a6b3e] text-sm font-medium">
                        Edit <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>

                {{-- Admin Card (super_admin only) --}}
                @if (auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.folder-permissions') }}"
                       class="group bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-6 hover:border-amber-500 hover:shadow-lg transition-all duration-200">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center mb-4 group-hover:bg-amber-500 transition-colors duration-200">
                            <svg class="w-6 h-6 text-amber-600 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Kelola Hak Akses</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Atur permission folder per prodi & user</p>
                        <div class="mt-4 flex items-center gap-1 text-amber-600 text-sm font-medium">
                            Kelola <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>
                @else
                    {{-- Info card untuk user biasa --}}
                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-6">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Informasi Akun</h3>
                        <div class="space-y-1.5 mt-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Role</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ auth()->user()->roleBadge() }}</span>
                            </div>
                            @if (auth()->user()->prodi)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Prodi</span>
                                    <span class="font-medium text-gray-800 dark:text-gray-200">{{ auth()->user()->prodi }}</span>
                                </div>
                            @endif
                            @if (auth()->user()->kode_lamp)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Kode</span>
                                    <span class="font-mono font-medium text-gray-800 dark:text-gray-200">{{ auth()->user()->kode_lamp }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
