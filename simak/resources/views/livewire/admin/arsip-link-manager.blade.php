<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

        {{-- Header --}}
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Link Arsip</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Salin link publik folder arsip untuk dibagikan. Semua orang yang punya link dapat melihat isi folder.
                </p>
            </div>
            <a href="{{ route('explorer') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white
                      bg-gradient-to-r from-[#0a3d22] to-[#1a6b3e] hover:from-[#082e1a] hover:to-[#155831]
                      shadow-md shadow-[#0d4a2a]/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                Kelola Folder
            </a>
        </div>

        {{-- Info banner --}}
        <div class="flex items-start gap-3 px-4 py-3 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-sm text-blue-700 dark:text-blue-300">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <span>
                Link arsip bersifat <strong>publik</strong> — siapa pun yang punya link bisa membuka dan mengunduh file tanpa login.
                Klik ikon salin <kbd class="px-1.5 py-0.5 rounded bg-blue-100 dark:bg-blue-800 font-mono text-[11px]">⧉</kbd> untuk menyalin link ke clipboard.
            </span>
        </div>

        {{-- Filter --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 flex flex-col sm:flex-row gap-3 items-start sm:items-center">
            <div class="relative flex-1 w-full">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search" type="search"
                       placeholder="Cari nama folder atau kode lampiran..."
                       class="w-full pl-9 pr-3 py-2.5 text-sm bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a]">
            </div>
            <label class="flex items-center gap-2 cursor-pointer shrink-0">
                <input type="checkbox" wire:model.live="showOnlyRoot"
                       class="rounded border-gray-300 text-[#0d4a2a] focus:ring-[#0d4a2a]/30">
                <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Hanya folder root</span>
            </label>
        </div>

        {{-- Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Folder</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Induk</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Isi</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Link Publik</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($this->folders as $folder)
                            <tr wire:key="folder-{{ $folder->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                {{-- Folder name --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0
                                            {{ $folder->parent_id === null
                                                ? 'bg-amber-100 dark:bg-amber-900/30'
                                                : 'bg-gray-100 dark:bg-gray-700' }}">
                                            <svg class="w-4 h-4 {{ $folder->parent_id === null ? 'text-amber-500' : 'text-gray-400' }}"
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 dark:text-white truncate max-w-[180px]"
                                               title="{{ $folder->name }}">
                                                {{ $folder->name }}
                                            </p>
                                            @if ($folder->kode_lamp)
                                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $folder->kode_lamp }}</p>
                                            @endif
                                            @if ($folder->parent_id === null)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 mt-0.5">
                                                    Root
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Parent --}}
                                <td class="px-4 py-3 hidden sm:table-cell text-xs text-gray-500 dark:text-gray-400">
                                    {{ $folder->parent?->name ?? '—' }}
                                </td>

                                {{-- Stats --}}
                                <td class="px-4 py-3 hidden md:table-cell">
                                    <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                            </svg>
                                            {{ $folder->children_count }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            {{ $folder->files_count }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Public link --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1.5 min-w-0"
                                         x-data="{ copied: false }"
                                         x-on:click.stop>
                                        <code class="text-[10px] text-gray-500 dark:text-gray-400 font-mono truncate max-w-[160px] hidden sm:block select-all"
                                              title="{{ route('arsip.show', $folder->uuid) }}">
                                            /arsip/{{ substr($folder->uuid, 0, 8) }}…
                                        </code>
                                        <button
                                            x-show="!copied"
                                            x-on:click="navigator.clipboard.writeText('{{ route('arsip.show', $folder->uuid) }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors flex-shrink-0"
                                            title="Salin link">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                        <span x-show="copied" x-cloak
                                              class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Disalin!
                                        </span>
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('arsip.show', $folder->uuid) }}" target="_blank"
                                           class="p-1.5 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors"
                                           title="Buka arsip (tab baru)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-sm text-gray-400 dark:text-gray-600">
                                    {{ $search ? 'Tidak ada folder yang cocok dengan pencarian.' : 'Belum ada folder. Buat folder terlebih dahulu di Explorer.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($this->folders->hasPages())
                <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                    {{ $this->folders->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
