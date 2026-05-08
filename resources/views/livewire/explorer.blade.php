{{--
    resources/views/livewire/explorer.blade.php
    Livewire 4 · Laravel 11 · Tailwind CSS
    Sidebar tree dengan infinite nested folder (seperti Google Drive)
--}}

<div>

{{-- ── Toast notification ──────────────────────────────────────────────── --}}
<div
    x-data="{
        toasts: [],
        add(e) {
            const id = Date.now();
            this.toasts.push({ id, type: e.detail.type, message: e.detail.message });
            setTimeout(() => this.remove(id), 3500);
        },
        remove(id) { this.toasts = this.toasts.filter(t => t.id !== id); }
    }"
    @notify.window="add($event)"
    class="fixed top-4 right-4 z-[9999] flex flex-col gap-2 pointer-events-none"
    style="min-width:280px;max-width:380px"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-end="opacity-0"
            :class="{
                'bg-green-50 border-green-300 text-green-800': toast.type==='success',
                'bg-red-50 border-red-300 text-red-800':      toast.type==='error',
                'bg-yellow-50 border-yellow-300 text-yellow-800': toast.type==='warning',
                'bg-blue-50 border-blue-300 text-blue-800':   toast.type==='info',
            }"
            class="flex items-center gap-2.5 px-4 py-3 rounded-xl border shadow-lg text-sm font-medium pointer-events-auto"
        >
            <span x-show="toast.type === 'success'">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </span>
            <span x-show="toast.type === 'error'">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </span>
            <span x-show="['warning','info'].includes(toast.type)">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </span>
            <span x-text="toast.message" class="flex-1"></span>
            <button @click="remove(toast.id)" class="opacity-60 hover:opacity-100 transition-opacity">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </template>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     SHELL
══════════════════════════════════════════════════════════════════ --}}
<div class="relative flex h-[calc(100vh-4rem)] overflow-hidden bg-gray-50 dark:bg-gray-900" id="explorer-shell">

    {{-- Backdrop mobile (di-handle vanilla JS) --}}
    <div id="explorer-backdrop" onclick="explorerCloseSidebar()"
         class="absolute inset-0 bg-black/50" style="display:none;z-index:20"></div>

    {{-- ╔═══════════════════════╗
         ║  SIDEBAR — TREE VIEW  ║
         ╚═══════════════════════╝ --}}
    <aside id="explorer-sidebar"
           class="w-64 flex-shrink-0 flex flex-col bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 overflow-hidden">

        {{-- Sidebar header --}}
        <div class="px-3 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Folder</span>
            {{-- Tombol tutup — hanya muncul di mobile --}}
            <button type="button" onclick="explorerCloseSidebar()"
                    id="explorer-close-btn"
                    class="p-1 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700"
                    style="display:none" title="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Scrollable tree --}}
        <nav class="flex-1 overflow-y-auto py-2 px-2">

            {{-- Home --}}
            <button
                wire:click="goHome"
                @class([
                    'w-full flex items-center gap-2 px-2 py-1.5 rounded-lg text-sm transition-colors duration-100 mb-1',
                    'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-semibold' => ! $currentFolderId,
                    'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-700 dark:hover:text-gray-200' => (bool)$currentFolderId,
                ])
            >
                <svg @class(['w-4 h-4 flex-shrink-0', 'text-blue-500' => ! $currentFolderId, 'text-gray-400' => (bool)$currentFolderId])
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="font-medium">Home</span>
            </button>

            {{-- Divider --}}
            <div class="h-px bg-gray-100 dark:bg-gray-700 mx-1 mb-2"></div>

            {{-- Root folders — tree --}}
            @if ($this->rootFolders->isEmpty())
                <p class="px-3 py-2 text-xs text-gray-400 dark:text-gray-600 italic">Belum ada folder.</p>
            @else
                <ul class="space-y-0.5">
                    @foreach ($this->rootFolders as $rootFolder)
                        @include('livewire.partials.folder-tree-node', [
                            'folder'      => $rootFolder,
                            'depth'       => 0,
                            'currentId'   => $currentFolderId,
                            'expandedIds' => $expandedIds,
                        ])
                    @endforeach
                </ul>
            @endif
        </nav>

        {{-- Sidebar footer --}}
        <div class="px-3 py-2.5 border-t border-gray-200 dark:border-gray-700 space-y-0.5">
            <p class="text-[10px] text-gray-400 dark:text-gray-600">Maks. 10 MB per file</p>
            <p class="text-[10px] text-gray-400 dark:text-gray-600">PDF · Word · Excel · PowerPoint</p>
        </div>
    </aside>

    {{-- ╔══════════════════════════════════════════════════╗
         ║  MAIN AREA                                       ║
         ╚══════════════════════════════════════════════════╝ --}}
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        {{-- ── Toolbar ──────────────────────────────────────────── --}}
        <div class="flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-3 py-2 flex items-center gap-2">

            {{-- Tombol folder mobile --}}
            <button type="button" onclick="explorerToggleSidebar()"
                    id="explorer-toggle-btn"
                    class="p-1.5 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 flex-shrink-0"
                    style="display:none" title="Folder">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Nav buttons --}}
            <div class="flex items-center gap-0.5">
                <button
                    wire:click="goBack"
                    @class([
                        'p-1.5 rounded-lg transition-colors',
                        'text-gray-300 dark:text-gray-600 cursor-not-allowed' => ! $this->canGoBack,
                        'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' => $this->canGoBack,
                    ])
                    @disabled(! $this->canGoBack)
                    title="Kembali"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <button
                    wire:click="goForward"
                    @class([
                        'p-1.5 rounded-lg transition-colors',
                        'text-gray-300 dark:text-gray-600 cursor-not-allowed' => ! $this->canGoForward,
                        'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' => $this->canGoForward,
                    ])
                    @disabled(! $this->canGoForward)
                    title="Maju"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <button
                    wire:click="refresh"
                    class="p-1.5 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                    title="Refresh"
                >
                    <svg class="w-4 h-4" wire:loading.class="animate-spin"
                         wire:target="refresh,openFolder,goBack,goForward"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>

            <div class="w-px h-5 bg-gray-200 dark:bg-gray-700"></div>

            {{-- Breadcrumb --}}
            <div class="flex-1 flex items-center gap-1 text-sm overflow-x-auto no-scrollbar min-w-0">
                <button
                    wire:click="goHome"
                    class="flex-shrink-0 text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1 font-medium"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Home</span>
                </button>

                @foreach ($this->breadcrumbs as $crumb)
                    <svg class="w-3 h-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <button
                        wire:click="openFolder({{ $crumb['id'] }})"
                        @class([
                            'flex-shrink-0 hover:underline truncate max-w-[140px] transition-colors',
                            'text-gray-800 dark:text-gray-100 font-medium' => $currentFolderId === $crumb['id'],
                            'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400' => $currentFolderId !== $crumb['id'],
                        ])
                    >{{ $crumb['name'] }}</button>
                @endforeach
            </div>

            <div class="w-px h-5 bg-gray-200 dark:bg-gray-700"></div>

            {{-- Search --}}
            @if ($currentFolderId)
                <div class="relative">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari..."
                        class="pl-8 pr-7 py-1.5 text-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-32 sm:w-44 transition-all"
                    >
                    @if ($search)
                        <button wire:click="clearSearch"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif
                </div>
            @endif

        </div>

        {{-- ── Action bar ──────────────────────────────────────────── --}}
        @if ($currentFolderId)
        <div class="flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-2 flex items-center gap-2 flex-wrap">
            <button
                wire:click="openNewFolderModal"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm"
                title="Buat Sub-folder"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                <span class="explorer-btn-label">Buat Sub-folder</span>
            </button>

            <button
                wire:click="openUploadModal"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm"
                title="Unggah File"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span class="explorer-btn-label">Unggah File</span>
            </button>

            {{-- Selection info --}}
            @if ($this->selectedCount > 0)
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $this->selectedCount }} dipilih</span>
                    <button wire:click="clearSelection" class="text-xs text-red-500 hover:text-red-700 transition-colors">
                        Batal pilih
                    </button>
                </div>
            @endif

            {{-- View toggle (Grid/List) — selalu di kanan, tampil di semua ukuran layar --}}
            <div class="ml-auto flex items-center gap-0.5 bg-gray-100 dark:bg-gray-700 rounded-lg p-0.5">
                <button
                    wire:click="$set('viewMode','grid')"
                    @class([
                        'p-1.5 rounded-md transition-all',
                        'bg-white dark:bg-gray-600 shadow text-blue-600 dark:text-blue-400' => $viewMode === 'grid',
                        'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' => $viewMode !== 'grid',
                    ])
                    title="Tampilan Grid"
                >
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
                <button
                    wire:click="$set('viewMode','list')"
                    @class([
                        'p-1.5 rounded-md transition-all',
                        'bg-white dark:bg-gray-600 shadow text-blue-600 dark:text-blue-400' => $viewMode === 'list',
                        'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' => $viewMode !== 'list',
                    ])
                    title="Tampilan List"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        {{-- ── Path / Lokasi folder — selalu tampil di semua ukuran layar ── --}}
        @if ($currentFolderId && $this->currentFolder)
        <div class="flex-shrink-0 bg-blue-50 dark:bg-blue-900/20 border-b border-blue-100 dark:border-blue-900/40 px-4 py-2 flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243
                         a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="text-xs font-semibold text-blue-700 dark:text-blue-300 flex-shrink-0">Lokasi:</span>
            <div class="flex items-center gap-1 text-xs text-gray-600 dark:text-gray-400 overflow-x-auto no-scrollbar min-w-0">
                <button wire:click="goHome"
                        class="flex-shrink-0 hover:text-blue-600 dark:hover:text-blue-400 hover:underline transition-colors">
                    Home
                </button>
                @foreach ($this->breadcrumbs as $crumb)
                    <span class="text-gray-300 dark:text-gray-600 flex-shrink-0">/</span>
                    @if ($currentFolderId === $crumb['id'])
                        <span class="flex-shrink-0 font-semibold text-gray-800 dark:text-gray-100 truncate max-w-[200px]"
                              title="{{ $crumb['name'] }}">{{ $crumb['name'] }}</span>
                    @else
                        <button wire:click="openFolder({{ $crumb['id'] }})"
                                class="flex-shrink-0 hover:text-blue-600 dark:hover:text-blue-400 hover:underline transition-colors truncate max-w-[140px]"
                                title="{{ $crumb['name'] }}">{{ $crumb['name'] }}</button>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── Konten utama ─────────────────────────────────────────── --}}
        <div class="flex-1 overflow-y-auto p-4">

            {{-- Loading overlay --}}
            <div
                wire:loading.delay
                wire:target="openFolder,goBack,goForward,refresh,createFolder,renameItem,deleteItem,uploadFiles"
                class="fixed inset-0 z-30 bg-white/40 dark:bg-gray-900/40 backdrop-blur-sm flex items-center justify-center pointer-events-none"
            >
                <div class="flex items-center gap-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-5 py-3 shadow-xl pointer-events-auto">
                    <svg class="w-4 h-4 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Memuat…</span>
                </div>
            </div>

            {{-- ── Belum pilih folder ── --}}
            @if (! $currentFolderId)
                <div class="h-full flex flex-col items-center justify-center gap-4 text-gray-400 dark:text-gray-600 select-none">
                    <svg class="w-20 h-20 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                              d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <div class="text-center">
                        <p class="text-base font-medium text-gray-500 dark:text-gray-400">Pilih folder dari sidebar</p>
                        <p class="text-sm text-gray-400 dark:text-gray-600 mt-1">untuk melihat isi folder</p>
                    </div>
                </div>

            {{-- ════════ GRID VIEW ════════ --}}
            @elseif ($viewMode === 'grid')

                @if ($this->isEmpty)
                    <div class="h-48 flex flex-col items-center justify-center gap-3 text-gray-400 dark:text-gray-600">
                        <svg class="w-14 h-14 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                  d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm">{{ $search ? 'Tidak ditemukan.' : 'Folder ini masih kosong.' }}</p>
                        <p class="text-xs text-gray-400">Klik "Buat Sub-folder" atau "Unggah File" di atas.</p>
                    </div>
                @else
                    <div class="grid gap-3" style="grid-template-columns: repeat(auto-fill, minmax(130px, 1fr))">

                        {{-- Sub-folders --}}
                        @foreach ($this->subFolders as $folder)
                            <div
                                wire:key="grid-folder-{{ $folder->id }}"
                                wire:click="toggleSelect({{ $folder->id }}, 'folder')"
                                wire:dblclick="openFolder({{ $folder->id }})"
                                @class([
                                    'group relative flex flex-col items-center gap-2 p-3 rounded-xl border cursor-pointer transition-all select-none',
                                    'border-blue-400 dark:border-blue-500 ring-2 ring-blue-200 dark:ring-blue-800 bg-blue-50 dark:bg-blue-900/20 shadow-md' => $this->isSelected($folder->id, 'folder'),
                                    'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-sm' => ! $this->isSelected($folder->id, 'folder'),
                                ])
                            >
                                <svg class="w-12 h-12 text-yellow-400 group-hover:text-yellow-500 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                </svg>

                                <p class="text-xs font-medium text-gray-800 dark:text-gray-200 truncate w-full text-center leading-tight"
                                   title="{{ $folder->name }}">{{ $folder->name }}</p>

                                {{-- Context menu --}}
                                <div class="absolute top-1.5 right-1.5 hidden group-hover:flex flex-col gap-0.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-1 z-10"
                                     x-on:click.stop>
                                    <button wire:click.stop="openFolder({{ $folder->id }})"
                                            class="flex items-center gap-1.5 px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 rounded-md transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>Buka
                                    </button>
                                    <a href="{{ route('arsip.show', $folder->uuid) }}" target="_blank" x-on:click.stop
                                       class="flex items-center gap-1.5 px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 rounded-md transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>Lihat
                                    </a>
                                    @if (auth()->id() === $folder->created_by || auth()->user()->isSuperAdmin())
                                    <button wire:click.stop="openShareModal({{ $folder->id }}, 'folder', '{{ addslashes($folder->name) }}')"
                                            class="flex items-center gap-1.5 px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:bg-purple-50 dark:hover:bg-purple-900/30 hover:text-purple-600 rounded-md transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                        </svg>Bagikan
                                    </button>
                                    @endif
                                    <button wire:click.stop="openRenameModal({{ $folder->id }}, 'folder')"
                                            class="flex items-center gap-1.5 px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 hover:text-yellow-600 rounded-md transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>Ubah nama
                                    </button>
                                    <button wire:click.stop="openDeleteModal({{ $folder->id }}, 'folder', '{{ addslashes($folder->name) }}')"
                                            class="flex items-center gap-1.5 px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 rounded-md transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>Hapus
                                    </button>
                                </div>

                                <p class="text-[10px] text-gray-400 dark:text-gray-600 group-hover:text-blue-500 transition-colors">2× untuk buka</p>
                            </div>
                        @endforeach

                        {{-- Files --}}
                        @foreach ($this->currentFiles as $file)
                            @php
                                $ext = $file->extension();
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
                            <div
                                wire:key="grid-file-{{ $file->id }}"
                                wire:click="toggleSelect({{ $file->id }}, 'file')"
                                @class([
                                    'group relative flex flex-col items-center gap-2 p-3 rounded-xl border cursor-pointer transition-all select-none',
                                    'border-blue-400 dark:border-blue-500 ring-2 ring-blue-200 dark:ring-blue-800 bg-blue-50 dark:bg-blue-900/20 shadow-md' => $this->isSelected($file->id, 'file'),
                                    'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-sm' => ! $this->isSelected($file->id, 'file'),
                                ])
                            >
                                <div class="relative">
                                    <svg class="w-12 h-12 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    <span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 text-[8px] font-bold text-white leading-none">
                                        {{ $iconLabel }}
                                    </span>
                                </div>
                                <p class="text-xs font-medium text-gray-800 dark:text-gray-200 truncate w-full text-center"
                                   title="{{ $file->original_name }}">{{ $file->original_name }}</p>
                                <p class="text-[10px] text-gray-400">{{ $file->formattedSize() }}</p>

                                {{-- Context menu --}}
                                <div class="absolute top-1.5 right-1.5 hidden group-hover:flex flex-col gap-0.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-1 z-10"
                                     x-on:click.stop>
                                    @if (in_array($file->extension(), ['pdf','doc','docx','xls','xlsx','ppt','pptx']))
                                    <a href="{{ route('file.view', $file->id) }}" target="_blank" x-on:click.stop
                                       class="flex items-center gap-1.5 px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 rounded-md transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>Lihat
                                    </a>
                                    @endif
                                    <button wire:click.stop="downloadFile({{ $file->id }})"
                                            class="flex items-center gap-1.5 px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 rounded-md transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>Unduh
                                    </button>
                                    @if (auth()->id() === $file->uploaded_by || auth()->user()->isSuperAdmin())
                                    <button wire:click.stop="openShareModal({{ $file->id }}, 'file', '{{ addslashes($file->original_name) }}')"
                                            class="flex items-center gap-1.5 px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:bg-purple-50 dark:hover:bg-purple-900/30 hover:text-purple-600 rounded-md transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                        </svg>Bagikan
                                    </button>
                                    @endif
                                    <button wire:click.stop="openRenameModal({{ $file->id }}, 'file')"
                                            class="flex items-center gap-1.5 px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 hover:text-yellow-600 rounded-md transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>Ubah nama
                                    </button>
                                    <button wire:click.stop="openDeleteModal({{ $file->id }}, 'file', '{{ addslashes($file->original_name) }}')"
                                            class="flex items-center gap-1.5 px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 rounded-md transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>Hapus
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            {{-- ════════ LIST VIEW ════════ --}}
            @else
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                                <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                                <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Tipe</th>
                                <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Ukuran</th>
                                <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Diunggah</th>
                                <th class="text-right px-4 py-2.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                            @foreach ($this->subFolders as $folder)
                                <tr wire:key="list-folder-{{ $folder->id }}"
                                    wire:click="toggleSelect({{ $folder->id }}, 'folder')"
                                    wire:dblclick="openFolder({{ $folder->id }})"
                                    @class([
                                        'group cursor-pointer transition-colors',
                                        'bg-blue-50 dark:bg-blue-900/20' => $this->isSelected($folder->id, 'folder'),
                                        'hover:bg-gray-50 dark:hover:bg-gray-700/50' => ! $this->isSelected($folder->id, 'folder'),
                                    ])
                                >
                                    <td class="px-4 py-2.5">
                                        <div class="flex items-center gap-2.5">
                                            <svg class="w-5 h-5 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                            </svg>
                                            <span class="font-medium text-gray-900 dark:text-gray-100">{{ $folder->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2.5 text-gray-500 dark:text-gray-400 hidden sm:table-cell">Folder</td>
                                    <td class="px-4 py-2.5 text-gray-500 dark:text-gray-400 hidden md:table-cell">—</td>
                                    <td class="px-4 py-2.5 text-gray-500 dark:text-gray-400 hidden lg:table-cell">—</td>
                                    <td class="px-4 py-2.5">
                                        <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity" x-on:click.stop>
                                            <button wire:click.stop="openFolder({{ $folder->id }})"
                                                    class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors" title="Buka">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </button>
                                            <a href="{{ route('arsip.show', $folder->uuid) }}" target="_blank" x-on:click.stop
                                               class="p-1.5 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" title="Lihat">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            @if (auth()->id() === $folder->created_by || auth()->user()->isSuperAdmin())
                                            <button wire:click.stop="openShareModal({{ $folder->id }}, 'folder', '{{ addslashes($folder->name) }}')"
                                                    class="p-1.5 rounded-lg text-gray-400 hover:text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/30 transition-colors" title="Bagikan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                            </button>
                                            @endif
                                            <button wire:click.stop="openRenameModal({{ $folder->id }}, 'folder')"
                                                    class="p-1.5 rounded-lg text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 transition-colors" title="Ubah nama">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <button wire:click.stop="openDeleteModal({{ $folder->id }}, 'folder', '{{ addslashes($folder->name) }}')"
                                                    class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @foreach ($this->currentFiles as $file)
                                @php
                                    $ext = $file->extension();
                                    $lc = match($ext) {
                                        'pdf' => 'text-red-500', 'doc','docx' => 'text-blue-600',
                                        'xls','xlsx' => 'text-green-600', 'ppt','pptx' => 'text-orange-500',
                                        default => 'text-gray-400',
                                    };
                                @endphp
                                <tr wire:key="list-file-{{ $file->id }}"
                                    wire:click="toggleSelect({{ $file->id }}, 'file')"
                                    @class([
                                        'group cursor-pointer transition-colors',
                                        'bg-blue-50 dark:bg-blue-900/20' => $this->isSelected($file->id, 'file'),
                                        'hover:bg-gray-50 dark:hover:bg-gray-700/50' => ! $this->isSelected($file->id, 'file'),
                                    ])
                                >
                                    <td class="px-4 py-2.5">
                                        <div class="flex items-center gap-2.5">
                                            <svg class="w-5 h-5 {{ $lc }} flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="font-medium text-gray-900 dark:text-gray-100">{{ $file->original_name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2.5 text-gray-500 dark:text-gray-400 hidden sm:table-cell">{{ strtoupper($file->extension()) }}</td>
                                    <td class="px-4 py-2.5 text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $file->formattedSize() }}</td>
                                    <td class="px-4 py-2.5 text-gray-500 dark:text-gray-400 hidden lg:table-cell">
                                        {{ $file->created_at->format('d M Y') }}
                                        <span class="block text-xs text-gray-400">oleh {{ $file->uploader?->name ?? '—' }}</span>
                                    </td>
                                    <td class="px-4 py-2.5">
                                        <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity" x-on:click.stop>
                                            @if (in_array($file->extension(), ['pdf','doc','docx','xls','xlsx','ppt','pptx']))
                                            <a href="{{ route('file.view', $file->id) }}" target="_blank" x-on:click.stop
                                               class="p-1.5 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" title="Lihat">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            @endif
                                            <button wire:click.stop="downloadFile({{ $file->id }})"
                                                    class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors" title="Unduh">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            </button>
                                            @if (auth()->id() === $file->uploaded_by || auth()->user()->isSuperAdmin())
                                            <button wire:click.stop="openShareModal({{ $file->id }}, 'file', '{{ addslashes($file->original_name) }}')"
                                                    class="p-1.5 rounded-lg text-gray-400 hover:text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/30 transition-colors" title="Bagikan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                            </button>
                                            @endif
                                            <button wire:click.stop="openRenameModal({{ $file->id }}, 'file')"
                                                    class="p-1.5 rounded-lg text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 transition-colors" title="Ubah nama">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <button wire:click.stop="openDeleteModal({{ $file->id }}, 'file', '{{ addslashes($file->original_name) }}')"
                                                    class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($this->isEmpty)
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center text-sm text-gray-400 dark:text-gray-600">
                                        {{ $search ? 'Tidak ditemukan.' : 'Folder ini masih kosong.' }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            @endif

        </div>{{-- /konten --}}
    </div>{{-- /main --}}
</div>{{-- /shell --}}

{{-- ══════════════════════════════════════════════════════════════════
     MODALS
══════════════════════════════════════════════════════════════════ --}}

{{-- ── Buat Folder ──────────────────────────────────────────────────────── --}}
@if ($showNewFolderModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 dark:bg-black/70 backdrop-blur-sm"
     wire:keydown.window.escape="$set('showNewFolderModal', false)">
    <div x-data x-init="$nextTick(() => $refs.folderNameInput?.focus())"
         class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden"
         x-on:click.outside="$wire.set('showNewFolderModal', false)">

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                Buat Sub-folder Baru
            </h2>
            <button wire:click="$set('showNewFolderModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        @if ($this->currentFolder)
            <div class="px-6 pt-4 pb-0">
                <p class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                    Di dalam: <span class="font-medium text-gray-600 dark:text-gray-300 ml-1">{{ $this->currentFolder->buildPath() }}</span>
                </p>
            </div>
        @endif

        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Folder</label>
                <input
                    type="text"
                    wire:model="newFolderName"
                    wire:keydown.enter="createFolder"
                    x-ref="folderNameInput"
                    placeholder="Contoh: Semester Ganjil 2025"
                    class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                >
                @error('newFolderName')
                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <div class="flex gap-3 pt-1">
                <button wire:click="createFolder" wire:loading.attr="disabled"
                        class="flex-1 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                    <svg wire:loading wire:target="createFolder" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    Buat Folder
                </button>
                <button wire:click="$set('showNewFolderModal', false)"
                        class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── Upload File ───────────────────────────────────────────────────────── --}}
@if ($showUploadModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 dark:bg-black/70 backdrop-blur-sm"
     wire:keydown.window.escape="$set('showUploadModal', false)">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden"
         x-on:click.outside="$wire.set('showUploadModal', false)">

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Unggah File
            </h2>
            <button wire:click="$set('showUploadModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 space-y-4">
            {{-- Drop zone --}}
            <label for="file-upload-input"
                   class="flex flex-col items-center justify-center gap-3 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl px-6 py-8 cursor-pointer hover:border-emerald-400 dark:hover:border-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/10 transition-colors">
                <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Klik atau seret file ke sini</p>
                    <p class="text-xs text-gray-400 mt-1">Maksimal 10 MB per file</p>
                    <p class="text-xs text-gray-400">PDF · Word · Excel · PowerPoint</p>
                </div>
                <input id="file-upload-input" type="file" wire:model="uploads" multiple
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" class="hidden">
            </label>

            <div wire:loading wire:target="uploads" class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <svg class="w-4 h-4 animate-spin text-emerald-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>
                <span>Menyiapkan file…</span>
            </div>

            @if (! empty($uploads))
                <ul class="divide-y divide-gray-100 dark:divide-gray-700 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden text-sm max-h-40 overflow-y-auto">
                    @foreach ($uploads as $upload)
                        <li class="flex items-center justify-between px-3 py-2 bg-white dark:bg-gray-900">
                            <div class="flex items-center gap-2 min-w-0">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="truncate text-gray-700 dark:text-gray-300">{{ $upload->getClientOriginalName() }}</span>
                            </div>
                            <span class="text-gray-400 text-xs flex-shrink-0 ml-2">
                                @php
                                    $bytes = $upload->getSize();
                                    $units = ['B','KB','MB','GB'];
                                    $i = 0;
                                    while ($bytes >= 1024 && $i < 3) { $bytes /= 1024; $i++; }
                                @endphp
                                {{ round($bytes, 1) . ' ' . $units[$i] }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif

            @error('uploads.*')
                <p class="text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror

            <div class="flex gap-3 pt-1">
                <button wire:click="uploadFiles" wire:loading.attr="disabled"
                        @class([
                            'flex-1 flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors',
                            'cursor-not-allowed' => empty($uploads),
                        ])
                        @disabled(empty($uploads))>
                    <svg wire:loading wire:target="uploadFiles" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    Unggah {{ ! empty($uploads) ? '(' . count($uploads) . ')' : '' }}
                </button>
                <button wire:click="$set('showUploadModal', false)"
                        class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── Ubah Nama ─────────────────────────────────────────────────────────── --}}
@if ($showRenameModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 dark:bg-black/70 backdrop-blur-sm"
     wire:keydown.window.escape="$set('showRenameModal', false)">
    <div x-data x-init="$nextTick(() => $refs.renameInput?.focus())"
         class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden"
         x-on:click.outside="$wire.set('showRenameModal', false)">

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                Ubah Nama {{ $renameItemType === 'folder' ? 'Folder' : 'File' }}
            </h2>
            <button wire:click="$set('showRenameModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Baru</label>
                <input type="text" wire:model="renameName" wire:keydown.enter="renameItem" x-ref="renameInput"
                       class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition">
                @error('renameName')
                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-3 pt-1">
                <button wire:click="renameItem" wire:loading.attr="disabled"
                        class="flex-1 flex items-center justify-center gap-2 bg-yellow-500 hover:bg-yellow-600 disabled:opacity-60 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                    <svg wire:loading wire:target="renameItem" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    Simpan
                </button>
                <button wire:click="$set('showRenameModal', false)"
                        class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── Konfirmasi Hapus ──────────────────────────────────────────────────── --}}
@if ($showDeleteModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 dark:bg-black/70 backdrop-blur-sm"
     wire:keydown.window.escape="$set('showDeleteModal', false)">
    <div class="w-full max-w-sm bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden"
         x-on:click.outside="$wire.set('showDeleteModal', false)">
        <div class="p-6 text-center">
            <div class="w-14 h-14 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                Hapus {{ $deleteItemType === 'folder' ? 'Folder' : 'File' }}?
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                <strong class="text-gray-800 dark:text-gray-200 break-all">{{ $deleteItemName }}</strong>
            </p>
            @if ($deleteItemType === 'folder')
                <p class="text-xs text-red-500 dark:text-red-400 mb-4">Semua sub-folder dan file di dalamnya akan ikut terhapus.</p>
            @else
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Tindakan ini tidak dapat dibatalkan.</p>
            @endif
            <div class="flex gap-3">
                <button wire:click="deleteItem" wire:loading.attr="disabled"
                        class="flex-1 flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 disabled:opacity-60 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                    <svg wire:loading wire:target="deleteItem" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    Ya, Hapus
                </button>
                <button wire:click="$set('showDeleteModal', false)"
                        class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════════════════
     MODAL BAGIKAN — Redesigned, user-friendly, responsive
══════════════════════════════════════════════════════════════════ --}}
@if ($showShareModal)
<div
    x-data="{ copied: false }"
    class="fixed inset-0 z-50 flex items-end sm:items-center justify-center sm:p-4 bg-black/60 backdrop-blur-sm"
    wire:keydown.window.escape="$set('showShareModal', false)"
>
    {{-- Backdrop klik tutup --}}
    <div class="absolute inset-0" wire:click="$set('showShareModal', false)"></div>

    {{-- Panel — full-width di mobile (bottom sheet), modal di sm+ --}}
    <div class="relative w-full sm:max-w-lg bg-white dark:bg-gray-900
                rounded-t-3xl sm:rounded-2xl shadow-2xl
                max-h-[92dvh] sm:max-h-[85dvh] flex flex-col
                animate-[slideUp_0.25s_ease-out] sm:animate-none">

        {{-- ── Drag handle (mobile only) ── --}}
        <div class="sm:hidden flex justify-center pt-3 pb-1 flex-shrink-0">
            <div class="w-10 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></div>
        </div>

        {{-- ── Header ── --}}
        <div class="flex items-start justify-between px-5 pt-4 pb-4 sm:pt-5 flex-shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                {{-- Icon item --}}
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                    {{ $shareItemType === 'file' ? 'bg-blue-100 dark:bg-blue-900/40' : 'bg-yellow-100 dark:bg-yellow-900/30' }}">
                    @if ($shareItemType === 'file')
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                        </svg>
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold text-purple-600 dark:text-purple-400 uppercase tracking-wider leading-none mb-0.5">
                        Bagikan {{ $shareItemType === 'file' ? 'File' : 'Folder' }}
                    </p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-[240px] sm:max-w-[320px]"
                       title="{{ $shareItemName }}">{{ $shareItemName }}</p>
                </div>
            </div>
            <button wire:click="$set('showShareModal', false)"
                    class="flex-shrink-0 ml-2 p-1.5 rounded-xl text-gray-400 hover:text-gray-600 dark:hover:text-gray-200
                           hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- ── Scrollable body ── --}}
        <div class="flex-1 overflow-y-auto px-5 pb-5 sm:pb-6 space-y-5">

            {{-- ═══ BAGIAN 1: Link Aktif ═══ --}}
            @if ($shareUrl)
            <div class="rounded-2xl border border-purple-200 dark:border-purple-800/60 bg-purple-50 dark:bg-purple-900/20 p-4 space-y-3">

                {{-- Label status --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                        </span>
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-200">Link aktif</span>
                        {{-- Badge permission --}}
                        @if ($sharePermission === 'download')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Lihat &amp; Unduh
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat saja
                            </span>
                        @endif
                    </div>
                    {{-- Revoke --}}
                    <button wire:click="revokeShareLink"
                            class="text-[11px] font-medium text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300
                                   hover:bg-red-50 dark:hover:bg-red-900/20 px-2 py-1 rounded-lg transition-colors">
                        Cabut akses
                    </button>
                </div>

                {{-- URL box --}}
                <div class="flex gap-2">
                    <div class="flex-1 min-w-0 flex items-center gap-2 px-3 py-2.5 rounded-xl
                                bg-white dark:bg-gray-800 border border-purple-200 dark:border-purple-700">
                        <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        <span class="text-xs text-gray-600 dark:text-gray-300 truncate font-mono select-all">{{ $shareUrl }}</span>
                    </div>

                    {{-- Salin --}}
                    <button
                        x-on:click="
                            navigator.clipboard.writeText('{{ $shareUrl }}');
                            copied = true;
                            setTimeout(() => copied = false, 2500);
                        "
                        class="flex-shrink-0 flex items-center gap-1.5 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200"
                        :class="copied
                            ? 'bg-green-500 text-white shadow-sm shadow-green-200 dark:shadow-green-900'
                            : 'bg-purple-600 hover:bg-purple-700 text-white'"
                    >
                        <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                        <svg x-show="copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="copied ? 'Tersalin!' : 'Salin'"></span>
                    </button>
                </div>

                {{-- Tombol buka --}}
                <a href="{{ $shareUrl }}" target="_blank" rel="noopener"
                   class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium
                          text-purple-700 dark:text-purple-300 bg-white dark:bg-gray-800
                          border border-purple-200 dark:border-purple-700
                          hover:bg-purple-50 dark:hover:bg-purple-900/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Buka di tab baru
                </a>
            </div>
            @else
            {{-- Placeholder belum ada link --}}
            <div class="rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 p-5 text-center space-y-1.5">
                <div class="w-11 h-11 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Belum ada link berbagi</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Atur izin di bawah, lalu buat link untuk dibagikan</p>
            </div>
            @endif

            {{-- ─── Divider ─── --}}
            <div class="flex items-center gap-3">
                <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
                <span class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    {{ $shareUrl ? 'Ganti pengaturan' : 'Pengaturan link' }}
                </span>
                <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
            </div>

            {{-- ═══ BAGIAN 2: Form pengaturan ═══ --}}
            <div class="space-y-4">

                {{-- Pilihan Izin — card selector (wire:click + @class, bukan peer-checked) --}}
                <div>
                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2.5">
                        Izin Akses
                    </p>
                    <div class="grid grid-cols-2 gap-2.5">

                        {{-- Kartu: Lihat saja --}}
                        <button type="button"
                                wire:click="$set('sharePermission', 'view')"
                                @class([
                                    'flex flex-col gap-2 p-3.5 rounded-xl border-2 transition-all duration-150 text-left w-full',
                                    'border-amber-400 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-500 ring-2 ring-amber-200 dark:ring-amber-900/50' => $sharePermission === 'view',
                                    'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-amber-300 dark:hover:border-amber-700' => $sharePermission !== 'view',
                                ])>
                            <div class="flex items-center justify-between">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors
                                    {{ $sharePermission === 'view' ? 'bg-amber-200 dark:bg-amber-800/60' : 'bg-amber-100 dark:bg-amber-900/30' }}">
                                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </div>
                                {{-- Indikator aktif --}}
                                @if ($sharePermission === 'view')
                                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 dark:border-gray-600"></div>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-semibold {{ $sharePermission === 'view' ? 'text-amber-800 dark:text-amber-200' : 'text-gray-800 dark:text-gray-100' }}">
                                    Lihat saja
                                </p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 leading-snug">Tidak bisa mengunduh</p>
                            </div>
                        </button>

                        {{-- Kartu: Lihat & Unduh --}}
                        <button type="button"
                                wire:click="$set('sharePermission', 'download')"
                                @class([
                                    'flex flex-col gap-2 p-3.5 rounded-xl border-2 transition-all duration-150 text-left w-full',
                                    'border-blue-400 bg-blue-50 dark:bg-blue-900/20 dark:border-blue-500 ring-2 ring-blue-200 dark:ring-blue-900/50' => $sharePermission === 'download',
                                    'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-blue-300 dark:hover:border-blue-700' => $sharePermission !== 'download',
                                ])>
                            <div class="flex items-center justify-between">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors
                                    {{ $sharePermission === 'download' ? 'bg-blue-200 dark:bg-blue-800/60' : 'bg-blue-100 dark:bg-blue-900/30' }}">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </div>
                                {{-- Indikator aktif --}}
                                @if ($sharePermission === 'download')
                                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 dark:border-gray-600"></div>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-semibold {{ $sharePermission === 'download' ? 'text-blue-800 dark:text-blue-200' : 'text-gray-800 dark:text-gray-100' }}">
                                    Lihat &amp; Unduh
                                </p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 leading-snug">Boleh mengunduh file</p>
                            </div>
                        </button>

                    </div>
                </div>

                {{-- Kedaluwarsa --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2.5">
                        Kedaluwarsa
                        <span class="normal-case font-normal text-gray-400 dark:text-gray-500">— opsional</span>
                    </label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="datetime-local" wire:model="shareExpiresAt"
                               class="w-full pl-9 pr-3 py-2.5 text-sm bg-white dark:bg-gray-800
                                      border border-gray-200 dark:border-gray-700 rounded-xl
                                      text-gray-900 dark:text-gray-100
                                      focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500
                                      transition-shadow">
                    </div>
                    @error('shareExpiresAt')
                        <p class="mt-1.5 text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="mt-1.5 text-[10px] text-gray-400 dark:text-gray-500">
                        Kosongkan untuk link tanpa batas waktu
                    </p>
                </div>
            </div>

        </div>{{-- /scroll body --}}

        {{-- ── Footer action — sticky ── --}}
        <div class="flex-shrink-0 px-5 py-4 border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900
                    rounded-b-3xl sm:rounded-b-2xl">
            <div class="flex gap-2.5">
                <button wire:click="$set('showShareModal', false)"
                        class="flex-shrink-0 px-4 py-2.5 text-sm font-semibold rounded-xl transition-colors
                               text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-800
                               hover:bg-gray-200 dark:hover:bg-gray-700">
                    Tutup
                </button>
                <button wire:click="createShareLink" wire:loading.attr="disabled"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl
                               text-sm font-semibold text-white transition-colors
                               bg-purple-600 hover:bg-purple-700 active:bg-purple-800
                               disabled:opacity-60 disabled:cursor-not-allowed shadow-sm shadow-purple-200 dark:shadow-purple-900/30">
                    {{-- Loading spinner --}}
                    <svg wire:loading wire:target="createShareLink"
                         class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    {{-- Icon default --}}
                    <svg wire:loading.remove wire:target="createShareLink"
                         class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101
                                 m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    {{ $shareUrl ? 'Buat Link Baru' : 'Buat Link Berbagi' }}
                </button>
            </div>
        </div>

    </div>
</div>

{{-- Animasi slide-up untuk mobile --}}
<style>
@keyframes slideUp {
    from { transform: translateY(100%); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
}
</style>
@endif

{{-- ════════════════════════════════════════════════════════════════
     Responsive sidebar — vanilla CSS + JS (no Alpine, no Livewire conflict)
═════════════════════════════════════════════════════════════════ --}}
<style>
    /* Mobile (<768px) */
    @media (max-width: 767px) {
        #explorer-sidebar {
            position: absolute;
            inset: 0 auto 0 0;
            z-index: 30;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,.25);
            display: none !important;
        }
        #explorer-sidebar.is-open      { display: flex !important; }
        #explorer-backdrop.is-open     { display: block !important; }
        #explorer-toggle-btn,
        #explorer-close-btn            { display: inline-flex !important; }
        .explorer-btn-label            { display: none; }
    }
</style>

<script>
    function explorerToggleSidebar() {
        const s = document.getElementById('explorer-sidebar');
        const b = document.getElementById('explorer-backdrop');
        if (!s || !b) return;
        s.classList.toggle('is-open');
        b.classList.toggle('is-open');
    }
    function explorerCloseSidebar() {
        const s = document.getElementById('explorer-sidebar');
        const b = document.getElementById('explorer-backdrop');
        if (s) s.classList.remove('is-open');
        if (b) b.classList.remove('is-open');
    }
</script>

</div>{{-- /root --}}
