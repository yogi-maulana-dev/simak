<div>
    {{-- ===================== TOAST NOTIFICATION ===================== --}}
    <div x-data="toastHandler()" x-init="initToastListener()" class="fixed top-5 right-5 z-[9999] flex flex-col gap-2 pointer-events-none" style="min-width: 280px; max-width: 380px">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 translate-x-4"
                 :class="{
                     'bg-green-50 border-green-200 text-green-800': toast.type === 'success',
                     'bg-red-50 border-red-200 text-red-800': toast.type === 'error',
                     'bg-yellow-50 border-yellow-200 text-yellow-800': toast.type === 'warning',
                     'bg-blue-50 border-blue-200 text-blue-800': toast.type === 'info'
                 }"
                 class="flex items-center gap-3 px-4 py-3 rounded-xl border shadow-lg text-sm font-medium pointer-events-auto backdrop-blur-sm">
                <span x-text="toast.message" class="flex-1"></span>
                <button @click="removeToast(toast.id)" class="opacity-60 hover:opacity-100 text-lg leading-none">✕</button>
            </div>
        </template>
    </div>

    <div class="relative flex h-[calc(100vh-4rem)] overflow-hidden bg-gray-50 dark:bg-gray-900">
        {{-- Backdrop untuk mobile (hanya muncul saat sidebar terbuka) --}}
        <div id="explorer-backdrop" class="fixed inset-0 bg-black/50 transition-opacity duration-200" style="display:none; z-index: 30;"></div>

        {{-- ===================== SIDEBAR ===================== --}}
        <aside id="explorer-sidebar"
               class="fixed lg:relative lg:flex lg:w-72 flex-shrink-0 flex-col bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-xl z-40 h-full transition-transform duration-300 transform -translate-x-full lg:translate-x-0 overflow-y-auto">
            {{-- Header sidebar --}}
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">File Explorer</span>
                </div>
                <div class="flex items-center gap-1">
                    @if(auth()->user() && auth()->user()->isSuperAdmin())
                        <button wire:click="openQuickFolderModal(null)" class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Buat folder root">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    @endif
                    <button id="explorer-close-btn" class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 lg:hidden" title="Tutup sidebar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Navigasi folder --}}
            <nav class="flex-1 overflow-y-auto py-2">
                <div class="px-2 mb-2">
                    <button wire:click="goHome"
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all {{ !$currentFolderId ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-semibold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Home</span>
                    </button>
                </div>
                <div class="px-2">
                    <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider px-3 mb-2">Folder</div>
                    @if ($this->rootFolders->isEmpty())
                        <p class="text-center text-sm text-gray-400 py-6 italic">Belum ada folder</p>
                    @else
                        <ul class="space-y-0.5">
                            @foreach ($this->rootFolders as $rootFolder)
                                @include('livewire.partials.folder-tree-node', [
                                    'folder' => $rootFolder,
                                    'depth' => 0,
                                    'currentId' => $currentFolderId,
                                    'expandedIds' => $expandedIds
                                ])
                            @endforeach
                        </ul>
                    @endif
                </div>
            </nav>

            {{-- Footer info --}}
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 text-[11px] text-gray-400 dark:text-gray-500 space-y-1">
                <p>📄 Maks. 10 MB per file</p>
                <p>📎 PDF · Word · Excel · PowerPoint</p>
            </div>
        </aside>

        {{-- ===================== MAIN CONTENT ===================== --}}
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">
            {{-- Toolbar --}}
            <div class="flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-2 flex items-center gap-2 flex-wrap">
                <button id="explorer-toggle-btn" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 lg:hidden" title="Buka sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-700/50 rounded-lg p-1">
                    <button wire:click="goBack" @class(['p-1.5 rounded-md transition', $this->canGoBack ? 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600' : 'text-gray-300 dark:text-gray-600 cursor-not-allowed']) @disabled(!$this->canGoBack)>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button wire:click="goForward" @class(['p-1.5 rounded-md transition', $this->canGoForward ? 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600' : 'text-gray-300 dark:text-gray-600 cursor-not-allowed']) @disabled(!$this->canGoForward)>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <button wire:click="refresh" class="p-1.5 rounded-md text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600 transition">
                        <svg class="w-4 h-4" wire:loading.class="animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>
                </div>

                <div class="h-6 w-px bg-gray-200 dark:bg-gray-700"></div>

                {{-- Breadcrumb --}}
                <div class="flex-1 flex items-center gap-1 text-sm overflow-x-auto whitespace-nowrap scrollbar-hide">
                    <button wire:click="goHome" class="flex items-center gap-1 text-blue-600 dark:text-blue-400 hover:underline font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span class="hidden sm:inline">Home</span>
                    </button>
                    @foreach ($this->breadcrumbs as $crumb)
                        <svg class="w-3 h-3 text-gray-400 flex-shrink-0 mx-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <button wire:click="openFolder({{ $crumb['id'] }})" class="truncate max-w-[120px] sm:max-w-[200px] hover:text-blue-600 dark:hover:text-blue-400 {{ $currentFolderId === $crumb['id'] ? 'font-semibold text-gray-800 dark:text-gray-200' : 'text-gray-500 dark:text-gray-400' }}">
                            {{ $crumb['name'] }}
                        </button>
                    @endforeach
                </div>

                @if ($currentFolderId)
                <div class="relative">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari..." class="pl-8 pr-7 py-1.5 text-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-lg w-36 sm:w-48 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                    @if ($search)
                        <button wire:click="clearSearch" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">✕</button>
                    @endif
                </div>
                @endif
            </div>

            {{-- Action Bar (hanya jika folder terbuka) --}}
            @if ($currentFolderId)
            <div class="flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-2.5 flex flex-wrap items-center gap-3">
                <button wire:click="openNewFolderModal" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    <span>Sub-folder</span>
                </button>
                <button wire:click="openUploadModal" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Upload</span>
                </button>

                @if ($this->selectedCount > 0)
                <div class="flex items-center gap-2 pl-2 border-l border-gray-200 dark:border-gray-700">
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $this->selectedCount }} dipilih</span>
                    <button wire:click="clearSelection" class="text-xs text-red-500 hover:text-red-700">Batal</button>
                </div>
                @endif

                <div class="ml-auto flex items-center gap-1 bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                    <button wire:click="$set('viewMode','grid')" @class(['p-1.5 rounded-md transition-all', 'bg-white dark:bg-gray-600 shadow text-blue-600 dark:text-blue-400' => $viewMode === 'grid', 'text-gray-500 hover:text-gray-700 dark:text-gray-400' => $viewMode !== 'grid'])>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </button>
                    <button wire:click="$set('viewMode','list')" @class(['p-1.5 rounded-md transition-all', 'bg-white dark:bg-gray-600 shadow text-blue-600 dark:text-blue-400' => $viewMode === 'list', 'text-gray-500 hover:text-gray-700 dark:text-gray-400' => $viewMode !== 'list'])>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>

            {{-- Lokasi folder saat ini (opsional) --}}
            <div class="flex-shrink-0 bg-blue-50/50 dark:bg-blue-900/10 px-4 py-2 text-xs flex items-center gap-2 border-b border-blue-100 dark:border-blue-900/30">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="font-medium text-blue-700 dark:text-blue-300">Lokasi:</span>
                <div class="flex items-center gap-1 overflow-x-auto scrollbar-hide">
                    <button wire:click="goHome" class="hover:text-blue-600">Home</button>
                    @foreach ($this->breadcrumbs as $crumb)
                        <span class="text-gray-300 dark:text-gray-600">/</span>
                        @if ($currentFolderId === $crumb['id'])
                            <span class="font-semibold text-gray-800 dark:text-gray-200 truncate max-w-[180px]">{{ $crumb['name'] }}</span>
                        @else
                            <button wire:click="openFolder({{ $crumb['id'] }})" class="hover:text-blue-600 truncate max-w-[140px]">{{ $crumb['name'] }}</button>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ===================== KONTEN UTAMA (GRID / LIST) ===================== --}}
            <div class="flex-1 overflow-y-auto p-4 md:p-6">
                {{-- Loading overlay --}}
                <div wire:loading.delay wire:target="openFolder,goBack,goForward,refresh,createFolder,renameItem,deleteItem,uploadFiles" class="fixed inset-0 z-20 bg-white/60 dark:bg-gray-900/60 backdrop-blur-sm flex items-center justify-center pointer-events-none">
                    <div class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-xl px-5 py-3 shadow-xl border border-gray-200 dark:border-gray-700">
                        <svg class="w-5 h-5 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Memuat...</span>
                    </div>
                </div>

                @if (!$currentFolderId)
                    {{-- Belum ada folder dipilih --}}
                    @if($this->rootFolders->isEmpty())
                        <div class="flex flex-col items-center justify-center h-full text-center text-gray-400 dark:text-gray-500 py-20">
                            <svg class="w-28 h-28 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.8" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            <p class="text-lg font-medium">Belum ada folder</p>
                            <p class="text-sm mt-1">Silakan buat folder pertama Anda</p>
                            @if(auth()->user() && auth()->user()->isSuperAdmin())
                                <button wire:click="openQuickFolderModal(null)" class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Buat Folder Baru
                                </button>
                            @else
                                <p class="text-xs text-gray-400 mt-4">Hubungi administrator untuk membuat folder</p>
                            @endif
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-center text-gray-400 dark:text-gray-500 py-20">
                            <svg class="w-28 h-28 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.8" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            <p class="text-lg font-medium">Pilih folder dari sidebar</p>
                            <p class="text-sm mt-1">Klik folder di sebelah kiri untuk melihat isinya</p>
                        </div>
                    @endif
                @elseif ($viewMode === 'grid')
                    {{-- Tampilan Grid --}}
                    @if ($this->isEmpty)
                        <div class="flex flex-col items-center justify-center h-64 text-gray-400 dark:text-gray-500 gap-2">
                            <svg class="w-16 h-16 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                            <p class="text-sm">{{ $search ? 'Tidak ada item yang cocok' : 'Folder kosong' }}</p>
                            @if(!$search)<p class="text-xs">Klik "Sub-folder" atau "Upload" untuk menambahkan</p>@endif
                        </div>
                    @else
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                            {{-- Sub-folder --}}
                            @foreach ($this->subFolders as $folder)
                                <div wire:key="grid-folder-{{ $folder->id }}"
                                     wire:click="toggleSelect({{ $folder->id }}, 'folder')"
                                     wire:dblclick="openFolder({{ $folder->id }})"
                                     @class([
                                         'group relative flex flex-col items-center gap-2 p-4 rounded-xl border cursor-pointer transition-all duration-200 hover:shadow-md',
                                         'bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-700 ring-1 ring-blue-200' => $this->isSelected($folder->id, 'folder'),
                                         'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600'
                                     ])>
                                    <div class="relative">
                                        <svg class="w-16 h-16 text-amber-400 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                        @if($this->isSelected($folder->id, 'folder'))
                                            <div class="absolute -top-1 -right-1 bg-blue-500 rounded-full p-0.5"><svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></div>
                                        @endif
                                    </div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200 text-center w-full truncate px-1" title="{{ $folder->name }}">{{ $folder->name }}</p>
                                    <div class="absolute top-2 right-2 hidden group-hover:flex flex-col bg-white dark:bg-gray-800 border rounded-lg shadow-lg p-1 z-10" x-on:click.stop>
                                        <button wire:click.stop="openFolder({{ $folder->id }})" class="flex items-center gap-2 px-2 py-1 text-xs rounded-md hover:bg-blue-50"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Buka</button>
                                        <a href="{{ route('arsip.show', $folder->uuid) }}" target="_blank" class="flex items-center gap-2 px-2 py-1 text-xs rounded-md hover:bg-indigo-50"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>Lihat publik</a>
                                        @if(auth()->id() === $folder->created_by || auth()->user()->isSuperAdmin())
                                        <button wire:click.stop="openShareModal({{ $folder->id }}, 'folder', '{{ addslashes($folder->name) }}')" class="flex items-center gap-2 px-2 py-1 text-xs rounded-md hover:bg-purple-50"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>Bagikan</button>
                                        @endif
                                        <button wire:click.stop="openRenameModal({{ $folder->id }}, 'folder')" class="flex items-center gap-2 px-2 py-1 text-xs rounded-md hover:bg-yellow-50"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>Ubah nama</button>
                                        <button wire:click.stop="openDeleteModal({{ $folder->id }}, 'folder', '{{ addslashes($folder->name) }}')" class="flex items-center gap-2 px-2 py-1 text-xs rounded-md hover:bg-red-50"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Hapus</button>
                                    </div>
                                </div>
                            @endforeach
                            {{-- File --}}
                            @foreach ($this->currentFiles as $file)
                                @php $ext = $file->extension(); $color = match($ext) { 'pdf' => 'text-red-500', 'doc','docx' => 'text-blue-600', 'xls','xlsx' => 'text-green-600', 'ppt','pptx' => 'text-orange-500', default => 'text-gray-400' }; $label = match($ext) { 'pdf' => 'PDF', 'doc','docx' => 'DOC', 'xls','xlsx' => 'XLS', 'ppt','pptx' => 'PPT', default => strtoupper($ext) }; @endphp
                                <div wire:key="grid-file-{{ $file->id }}"
                                     wire:click="toggleSelect({{ $file->id }}, 'file')"
                                     @class([
                                         'group relative flex flex-col items-center gap-2 p-4 rounded-xl border cursor-pointer transition-all duration-200 hover:shadow-md',
                                         'bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-700 ring-1 ring-blue-200' => $this->isSelected($file->id, 'file'),
                                         'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600'
                                     ])>
                                    <div class="relative">
                                        <svg class="w-14 h-14 {{ $color }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                        <span class="absolute bottom-1 left-1/2 -translate-x-1/2 text-[9px] font-bold text-white drop-shadow-md">{{ $label }}</span>
                                        @if($this->isSelected($file->id, 'file'))
                                            <div class="absolute -top-1 -right-1 bg-blue-500 rounded-full p-0.5"><svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></div>
                                        @endif
                                    </div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200 text-center w-full truncate px-1" title="{{ $file->original_name }}">{{ $file->original_name }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $file->formattedSize() }}</p>
                                    <div class="absolute top-2 right-2 hidden group-hover:flex flex-col bg-white dark:bg-gray-800 border rounded-lg shadow-lg p-1 z-10" x-on:click.stop>
                                        @if(in_array($ext, ['pdf','doc','docx','xls','xlsx','ppt','pptx']))
                                        <a href="{{ route('file.view', $file->id) }}" target="_blank" class="flex items-center gap-2 px-2 py-1 text-xs rounded-md hover:bg-indigo-50"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>Lihat</a>
                                        @endif
                                        <button wire:click.stop="downloadFile({{ $file->id }})" class="flex items-center gap-2 px-2 py-1 text-xs rounded-md hover:bg-blue-50"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>Unduh</button>
                                        @if(auth()->id() === $file->uploaded_by || auth()->user()->isSuperAdmin())
                                        <button wire:click.stop="openShareModal({{ $file->id }}, 'file', '{{ addslashes($file->original_name) }}')" class="flex items-center gap-2 px-2 py-1 text-xs rounded-md hover:bg-purple-50"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>Bagikan</button>
                                        @endif
                                        <button wire:click.stop="openRenameModal({{ $file->id }}, 'file')" class="flex items-center gap-2 px-2 py-1 text-xs rounded-md hover:bg-yellow-50"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>Ubah nama</button>
                                        <button wire:click.stop="openDeleteModal({{ $file->id }}, 'file', '{{ addslashes($file->original_name) }}')" class="flex items-center gap-2 px-2 py-1 text-xs rounded-md hover:bg-red-50"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Hapus</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    {{-- Tampilan List (sederhana, bisa dikembangkan sesuai kebutuhan) --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400">Nama</th>
                                    <th class="text-left px-4 py-3 hidden sm:table-cell">Tipe</th>
                                    <th class="text-left px-4 py-3 hidden md:table-cell">Ukuran</th>
                                    <th class="text-right px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach ($this->subFolders as $folder)
                                    <tr wire:key="list-folder-{{ $folder->id }}" wire:click="toggleSelect({{ $folder->id }}, 'folder')" wire:dblclick="openFolder({{ $folder->id }})" class="group cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                        <td class="px-4 py-2.5"><div class="flex items-center gap-3"><svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg><span>{{ $folder->name }}</span></div></td>
                                        <td class="px-4 py-2.5 hidden sm:table-cell text-gray-500">Folder</td>
                                        <td class="px-4 py-2.5 hidden md:table-cell text-gray-500">—</td>
                                        <td class="px-4 py-2.5 text-right"><div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition" x-on:click.stop>...</div></td>
                                    </tr>
                                @endforeach
                                @foreach ($this->currentFiles as $file)
                                    <tr wire:key="list-file-{{ $file->id }}" wire:click="toggleSelect({{ $file->id }}, 'file')" class="group cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                        <td class="px-4 py-2.5"><div class="flex items-center gap-3"><svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg><span>{{ $file->original_name }}</span></div></td>
                                        <td class="px-4 py-2.5 hidden sm:table-cell">{{ strtoupper($file->extension()) }}</td>
                                        <td class="px-4 py-2.5 hidden md:table-cell">{{ $file->formattedSize() }}</td>
                                        <td class="px-4 py-2.5 text-right"><div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition" x-on:click.stop>...</div></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ===================== MODAL-MODAL ===================== --}}
    {{-- MODAL: Buat Folder (via action bar) --}}
    @if ($showNewFolderModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:keydown.window.escape="$set('showNewFolderModal', false)">
        <div x-data x-init="$nextTick(() => $refs.folderNameInput?.focus())" class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl" x-on:click.outside="$wire.set('showNewFolderModal', false)">
            <div class="px-6 py-4 border-b flex justify-between items-center"><h3 class="text-lg font-semibold">Buat Folder Baru</h3><button wire:click="$set('showNewFolderModal', false)" class="text-gray-400 hover:text-gray-600">✕</button></div>
            <div class="p-6 space-y-4">
                <div><label class="block text-sm font-medium">Nama Folder</label><input type="text" wire:model="newFolderName" wire:keydown.enter="createFolder" x-ref="folderNameInput" class="w-full border rounded-lg px-3 py-2">@error('newFolderName')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror</div>
                <div class="flex gap-3"><button wire:click="createFolder" class="flex-1 bg-blue-600 text-white py-2 rounded-lg">Buat</button><button wire:click="$set('showNewFolderModal', false)" class="flex-1 bg-gray-100 dark:bg-gray-700 py-2 rounded-lg">Batal</button></div>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL: Upload File --}}
    @if ($showUploadModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:keydown.window.escape="$set('showUploadModal', false)">
        <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl" x-on:click.outside="$wire.set('showUploadModal', false)">
            <div class="px-6 py-4 border-b flex justify-between"><h3 class="text-lg font-semibold">Unggah File</h3><button wire:click="$set('showUploadModal', false)">✕</button></div>
            <div class="p-6 space-y-4">
                <label class="flex flex-col items-center gap-3 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 cursor-pointer hover:border-emerald-400">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <div class="text-center"><p class="text-sm font-medium">Klik atau seret file ke sini</p><p class="text-xs text-gray-400">Maks 10MB, format PDF/DOC/XLS/PPT</p></div>
                    <input type="file" wire:model="uploads" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" class="hidden">
                </label>
                @if(!empty($uploads))<div class="max-h-40 overflow-y-auto border rounded-lg divide-y">@foreach($uploads as $upload)<div class="flex justify-between px-3 py-2 text-sm"><span>{{ $upload->getClientOriginalName() }}</span><span>{{ round($upload->getSize()/1024) }} KB</span></div>@endforeach</div>@endif
                @error('uploads.*')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
                <div class="flex gap-3"><button wire:click="uploadFiles" @disabled(empty($uploads)) class="flex-1 bg-emerald-600 disabled:opacity-50 text-white py-2 rounded-lg">Unggah</button><button wire:click="$set('showUploadModal', false)" class="flex-1 bg-gray-100 dark:bg-gray-700 py-2 rounded-lg">Batal</button></div>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL: Rename --}}
    @if ($showRenameModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:keydown.window.escape="$set('showRenameModal', false)">
        <div x-data x-init="$nextTick(() => $refs.renameInput?.focus())" class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl" x-on:click.outside="$wire.set('showRenameModal', false)">
            <div class="px-6 py-4 border-b flex justify-between"><h3 class="text-lg font-semibold">Ubah Nama {{ $renameItemType === 'folder' ? 'Folder' : 'File' }}</h3><button wire:click="$set('showRenameModal', false)">✕</button></div>
            <div class="p-6 space-y-4"><div><label class="block text-sm font-medium">Nama Baru</label><input type="text" wire:model="renameName" wire:keydown.enter="renameItem" x-ref="renameInput" class="w-full border rounded-lg px-3 py-2">@error('renameName')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror</div><div class="flex gap-3"><button wire:click="renameItem" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-lg">Simpan</button><button wire:click="$set('showRenameModal', false)" class="flex-1 bg-gray-100 dark:bg-gray-700 py-2 rounded-lg">Batal</button></div></div>
        </div>
    </div>
    @endif

    {{-- MODAL: Delete --}}
    @if ($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:keydown.window.escape="$set('showDeleteModal', false)">
        <div class="w-full max-w-sm bg-white dark:bg-gray-800 rounded-2xl shadow-2xl text-center" x-on:click.outside="$wire.set('showDeleteModal', false)">
            <div class="p-6"><div class="mx-auto w-12 h-12 bg-red-100 rounded-full flex items-center justify-center"><svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></div><h4 class="mt-4 text-lg font-semibold">Hapus {{ $deleteItemType === 'folder' ? 'Folder' : 'File' }}?</h4><p class="mt-1 text-sm text-gray-500 break-all">“{{ $deleteItemName }}”</p>@if($deleteItemType === 'folder')<p class="mt-2 text-xs text-red-500">Semua isi folder akan ikut terhapus!</p>@endif<div class="mt-6 flex gap-3"><button wire:click="deleteItem" class="flex-1 bg-red-600 text-white py-2 rounded-lg">Ya, Hapus</button><button wire:click="$set('showDeleteModal', false)" class="flex-1 bg-gray-100 dark:bg-gray-700 py-2 rounded-lg">Batal</button></div></div>
        </div>
    </div>
    @endif

    {{-- MODAL: Quick Folder (Superadmin) --}}
    @if ($showQuickFolderModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:keydown.window.escape="$set('showQuickFolderModal', false)">
        <div x-data x-init="$nextTick(() => $refs.quickFolderInput?.focus())" class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl" x-on:click.outside="$wire.set('showQuickFolderModal', false)">
            <div class="px-6 py-4 border-b flex justify-between"><h3 class="text-lg font-semibold">Buat Folder Baru</h3><button wire:click="$set('showQuickFolderModal', false)">✕</button></div>
            <div class="p-6 space-y-4"><div><label class="block text-sm font-medium">Nama Folder</label><input type="text" wire:model="quickFolderName" wire:keydown.enter="createQuickFolder" x-ref="quickFolderInput" class="w-full border rounded-lg px-3 py-2">@error('quickFolderName')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror</div><div class="flex gap-3"><button wire:click="createQuickFolder" class="flex-1 bg-blue-600 text-white py-2 rounded-lg">Buat</button><button wire:click="$set('showQuickFolderModal', false)" class="flex-1 bg-gray-100 dark:bg-gray-700 py-2 rounded-lg">Batal</button></div></div>
        </div>
    </div>
    @endif

    {{-- MODAL: Share (sederhana) --}}
    @if ($showShareModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:keydown.window.escape="$set('showShareModal', false)">
        <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl" x-on:click.outside="$wire.set('showShareModal', false)">
            <div class="px-6 py-4 border-b flex justify-between"><h3 class="text-lg font-semibold">Bagikan {{ $shareItemType === 'folder' ? 'Folder' : 'File' }}</h3><button wire:click="$set('showShareModal', false)">✕</button></div>
            <div class="p-6 space-y-4">
                <p class="text-sm font-medium truncate">{{ $shareItemName }}</p>
                @if ($shareUrl)<div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 flex justify-between items-center"><code class="text-xs break-all">{{ $shareUrl }}</code><button wire:click="revokeShareLink" class="text-red-500 text-xs">Cabut</button></div>@endif
                <div><label class="block text-sm font-medium">Izin</label><select wire:model="sharePermission" class="w-full border rounded-lg px-3 py-2"><option value="view">Lihat saja</option><option value="download">Lihat & Unduh</option></select></div>
                <div><label class="block text-sm font-medium">Kedaluwarsa (opsional)</label><input type="datetime-local" wire:model="shareExpiresAt" class="w-full border rounded-lg px-3 py-2"></div>
                <div class="flex gap-3"><button wire:click="createShareLink" class="flex-1 bg-purple-600 text-white py-2 rounded-lg">Buat Link</button><button wire:click="$set('showShareModal', false)" class="flex-1 bg-gray-100 dark:bg-gray-700 py-2 rounded-lg">Tutup</button></div>
            </div>
        </div>
    </div>
    @endif

    {{-- ===================== STYLE & SCRIPT ===================== --}}
    <style>
        #explorer-sidebar {
            transition: transform 0.3s ease;
        }
        @media (max-width: 1023px) {
            #explorer-sidebar {
                transform: translateX(-100%);
            }
            #explorer-sidebar.is-open {
                transform: translateX(0) !important;
            }
            #explorer-backdrop {
                display: none;
            }
            #explorer-backdrop.is-open {
                display: block !important;
            }
            /* Perbesar area tombol aksi di sidebar */
            #explorer-sidebar .p-1\.5 {
                min-width: 44px;
                min-height: 44px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <script>
        (function() {
            const sidebar = document.getElementById('explorer-sidebar');
            const backdrop = document.getElementById('explorer-backdrop');
            const toggleBtn = document.getElementById('explorer-toggle-btn');
            const closeBtn = document.getElementById('explorer-close-btn');

            function openSidebar() {
                if (sidebar) sidebar.classList.add('is-open');
                if (backdrop) backdrop.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            }
            function closeSidebar() {
                if (sidebar) sidebar.classList.remove('is-open');
                if (backdrop) backdrop.classList.remove('is-open');
                document.body.style.overflow = '';
            }
            function toggleSidebar() {
                if (sidebar && sidebar.classList.contains('is-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            }

            if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);

            // KRUSIAL: Cegah klik di dalam sidebar menutup sidebar
            if (sidebar) {
                sidebar.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Reset saat resize ke desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    if (sidebar) sidebar.classList.remove('is-open');
                    if (backdrop) backdrop.classList.remove('is-open');
                    document.body.style.overflow = '';
                }
            });
        })();

        function toastHandler() {
            return {
                toasts: [],
                initToastListener() {
                    window.addEventListener('notify', (e) => {
                        this.addToast(e.detail.type, e.detail.message);
                    });
                },
                addToast(type, message) {
                    const id = Date.now();
                    this.toasts.push({ id, type, message });
                    setTimeout(() => this.removeToast(id), 4000);
                },
                removeToast(id) {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }
            }
        }
    </script>
</div>