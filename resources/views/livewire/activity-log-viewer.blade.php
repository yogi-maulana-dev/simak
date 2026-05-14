<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

        {{-- ── Header ── --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Log Aktivitas</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Riwayat semua aktivitas pengguna sistem.</p>
        </div>

        {{-- ── Stats cards ── --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                <p class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Hari ini</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($this->todayCount) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                <p class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Login gagal (24j)</p>
                <p class="text-2xl font-bold {{ $this->loginFailedCount > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }} mt-1">
                    {{ $this->loginFailedCount }}
                </p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                <p class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total log</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($this->logs->total()) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                <p class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Pengguna aktif</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->userOptions->count() }}</p>
            </div>
        </div>

        {{-- ── Filter bar ── --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 space-y-3">
            {{-- Search --}}
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search" type="search"
                       placeholder="Cari deskripsi, nama, email, atau aksi..."
                       class="w-full pl-9 pr-3 py-2.5 text-sm bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl
                              text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
            </div>

            {{-- Filter row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

                <select wire:model.live="filterAction"
                        class="w-full px-3 py-2 text-sm bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-200">
                    @foreach ($this->actionGroups as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filterUser"
                        class="w-full px-3 py-2 text-sm bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-200">
                    <option value="">Semua pengguna</option>
                    @foreach ($this->userOptions as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filterSource"
                        class="w-full px-3 py-2 text-sm bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-200">
                    <option value="">Semua sumber</option>
                    <option value="app">Aplikasi</option>
                    <option value="trigger">DB Trigger</option>
                    <option value="system">Sistem</option>
                </select>

                <input wire:model.live="filterDate" type="date"
                       class="w-full px-3 py-2 text-sm bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-200">
            </div>

            @if($search || $filterAction || $filterUser || $filterSource || $filterDate)
                <button wire:click="resetFilters"
                        class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reset filter
                </button>
            @endif
        </div>

        {{-- ── Table ── --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pengguna</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">IP</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden xl:table-cell">Lokasi</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Sumber</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($this->logs as $log)
                            @php
                                $info = $log->actionInfo();
                                $colorMap = [
                                    'green'   => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                                    'red'     => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                    'blue'    => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                                    'indigo'  => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300',
                                    'purple'  => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',
                                    'yellow'  => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300',
                                    'orange'  => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300',
                                    'emerald' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                                    'gray'    => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                ];
                                $badge = $colorMap[$info['color']] ?? $colorMap['gray'];
                            @endphp
                            <tr wire:key="log-{{ $log->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">

                                {{-- Waktu --}}
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $log->created_at->translatedFormat('d M, H:i') }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                                </td>

                                {{-- Pengguna --}}
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if ($log->user_name || $log->user)
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0">
                                                {{ strtoupper(substr($log->user_name ?? $log->user->name ?? '?', 0, 2)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold text-gray-900 dark:text-gray-100 truncate max-w-[120px]">
                                                    {{ $log->user_name ?? $log->user->name }}
                                                </p>
                                                <p class="text-[10px] text-gray-400 truncate max-w-[120px]">
                                                    {{ $log->user_email ?? $log->user?->email }}
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">— anonim —</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $badge }}">
                                        {{ $info['label'] }}
                                    </span>
                                </td>

                                {{-- Deskripsi --}}
                                <td class="px-4 py-3">
                                    <p class="text-sm text-gray-700 dark:text-gray-200">{{ $log->description ?? '—' }}</p>
                                </td>

                                {{-- IP --}}
                                <td class="px-4 py-3 hidden lg:table-cell whitespace-nowrap">
                                    <span class="text-xs font-mono text-gray-500 dark:text-gray-400">{{ $log->ip_address ?? '—' }}</span>
                                </td>

                                {{-- Lokasi --}}
                                <td class="px-4 py-3 hidden xl:table-cell whitespace-nowrap">
                                    @if ($log->latitude !== null && $log->longitude !== null)
                                        <a href="https://www.google.com/maps?q={{ $log->latitude }},{{ $log->longitude }}"
                                           target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400 hover:underline font-mono"
                                           title="Buka di Google Maps">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ number_format($log->latitude, 5) }}, {{ number_format($log->longitude, 5) }}
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-600">—</span>
                                    @endif
                                </td>

                                {{-- Sumber --}}
                                <td class="px-4 py-3 hidden md:table-cell whitespace-nowrap">
                                    @if ($log->source === 'trigger')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/50" title="Dicatat oleh database trigger">
                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                            </svg>
                                            DB Trigger
                                        </span>
                                    @elseif ($log->source === 'system')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                            Sistem
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                                            App
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-sm text-gray-400 dark:text-gray-600">
                                    @if($search || $filterAction || $filterUser || $filterSource || $filterDate)
                                        Tidak ada log yang cocok dengan filter.
                                    @else
                                        Belum ada aktivitas tercatat.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($this->logs->hasPages())
                <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                    {{ $this->logs->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
