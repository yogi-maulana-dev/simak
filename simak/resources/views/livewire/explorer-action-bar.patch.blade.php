{{-- ── Action bar ──────────────────────────────────────────── --}}
{{-- GANTI bagian action bar yang lama dengan ini --}}
@if ($currentFolderId)
<div class="flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-2 flex items-center gap-2 flex-wrap">

    @if ($this->canWriteCurrentFolder)
        <button
            wire:click="openNewFolderModal"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            </svg>
            Buat Sub-folder
        </button>

        <button
            wire:click="openUploadModal"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Unggah File
        </button>
    @else
        {{-- User hanya punya akses baca di folder ini --}}
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-lg">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Akses hanya baca
        </span>
    @endif

    {{-- Breadcrumb path hint --}}
    @if ($this->currentFolder)
        <span class="text-xs text-gray-400 dark:text-gray-600 hidden sm:inline">
            📁 {{ $this->currentFolder->buildPath() }}
        </span>
    @endif

    {{-- Selection info --}}
    @if ($this->selectedCount > 0)
        <div class="ml-auto flex items-center gap-2">
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $this->selectedCount }} dipilih</span>
            <button wire:click="clearSelection" class="text-xs text-red-500 hover:text-red-700 transition-colors">
                Batal pilih
            </button>
        </div>
    @endif
</div>
@endif