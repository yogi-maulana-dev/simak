{{--
    resources/views/livewire/folder-permission-manager.blade.php
    Admin: Manajemen Folder & Akses — Checkbox Tree per User
--}}

<div>

{{-- ── Toast ──────────────────────────────────────────────────────────── --}}
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
                'bg-red-50 border-red-300 text-red-800': toast.type==='error',
                'bg-yellow-50 border-yellow-300 text-yellow-800': toast.type==='warning',
                'bg-blue-50 border-blue-300 text-blue-800': toast.type==='info',
            }"
            class="flex items-center gap-2.5 px-4 py-3 rounded-xl border shadow-lg text-sm font-medium pointer-events-auto"
        >
            <span x-text="toast.message" class="flex-1"></span>
            <button @click="remove(toast.id)" class="opacity-50 hover:opacity-100">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </template>
</div>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-6">

    {{-- ── Page header ──────────────────────────────────────────────────── --}}
    <div class="mb-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                Manajemen Folder & Akses
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                Pilih pengguna → centang folder yang boleh diakses
            </p>
        </div>
        <a href="{{ route('explorer') }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-600 dark:text-gray-300
                  bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50
                  dark:hover:bg-gray-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Explorer
        </a>
    </div>

    {{-- ── Stat cards ────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach ([
            ['label'=>'Root Folder','value'=>$this->stats['total_folders'],'color'=>'blue',   'path'=>'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z'],
            ['label'=>'Total File', 'value'=>$this->stats['total_files'],  'color'=>'emerald','path'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['label'=>'Pengguna',   'value'=>$this->stats['total_users'],  'color'=>'violet', 'path'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['label'=>'Total Akses','value'=>$this->stats['total_grants'], 'color'=>'amber',  'path'=>'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z'],
        ] as $card)
            @php
                $p = ['blue'=>['bg'=>'bg-blue-50 dark:bg-blue-900/20','ic'=>'text-blue-600','vl'=>'text-blue-700 dark:text-blue-300'],
                      'emerald'=>['bg'=>'bg-emerald-50 dark:bg-emerald-900/20','ic'=>'text-emerald-600','vl'=>'text-emerald-700 dark:text-emerald-300'],
                      'violet'=>['bg'=>'bg-violet-50 dark:bg-violet-900/20','ic'=>'text-violet-600','vl'=>'text-violet-700 dark:text-violet-300'],
                      'amber'=>['bg'=>'bg-amber-50 dark:bg-amber-900/20','ic'=>'text-amber-600','vl'=>'text-amber-700 dark:text-amber-300']][$card['color']];
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-3 shadow-sm">
                <div class="p-2.5 rounded-xl {{ $p['bg'] }}">
                    <svg class="w-5 h-5 {{ $p['ic'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['path'] }}"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold {{ $p['vl'] }}">{{ number_format($card['value']) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $card['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ── Two-panel layout ─────────────────────────────────────────────── --}}
    <div class="grid lg:grid-cols-[300px,1fr] gap-5">

        {{-- ╔════════════════════════╗
             ║  PANEL KIRI — Users    ║
             ╚════════════════════════╝ --}}
        <div class="flex flex-col gap-4">

            {{-- Root folder management --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                        </svg>
                        Folder Utama
                    </h2>
                    <button wire:click="openCreateFolder"
                            class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah
                    </button>
                </div>

                {{-- Search folder --}}
                <div class="px-3 py-2 border-b border-gray-100 dark:border-gray-700">
                    <div class="relative">
                        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                        </svg>
                        <input type="text" wire:model.live.debounce.300ms="folderSearch"
                               placeholder="Cari folder..."
                               class="w-full pl-8 pr-3 py-1.5 text-xs border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <ul class="divide-y divide-gray-100 dark:divide-gray-700 max-h-52 overflow-y-auto">
                    @forelse ($this->rootFolders as $rf)
                        <li class="group flex items-center gap-2.5 px-4 py-2.5">
                            <svg class="w-5 h-5 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $rf->name }}</p>
                                <p class="text-xs text-gray-400">{{ $rf->children_count }} sub · {{ $rf->files_count }} file</p>
                            </div>
                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="openEditFolder({{ $rf->id }})"
                                        class="p-1 rounded text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button wire:click="openDeleteFolder({{ $rf->id }}, '{{ addslashes($rf->name) }}')"
                                        class="p-1 rounded text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </li>
                    @empty
                        <li class="px-4 py-6 text-center text-xs text-gray-400 dark:text-gray-600">
                            {{ $folderSearch ? 'Tidak ditemukan.' : 'Belum ada folder.' }}
                        </li>
                    @endforelse
                </ul>
            </div>

            {{-- User list --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden flex-1">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-1.5 mb-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Pilih Pengguna
                    </h2>
                    <div class="relative">
                        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                        </svg>
                        <input type="text" wire:model.live.debounce.300ms="userSearch"
                               placeholder="Cari pengguna..."
                               class="w-full pl-8 pr-3 py-1.5 text-xs border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <ul class="divide-y divide-gray-100 dark:divide-gray-700 max-h-[calc(100vh-30rem)] overflow-y-auto">
                    @forelse ($this->allUsers as $u)
                        <li
                            wire:key="ul-{{ $u->id }}"
                            wire:click="selectUser({{ $u->id }})"
                            @class([
                                'flex items-center gap-3 px-4 py-2.5 cursor-pointer transition-colors',
                                'bg-blue-50 dark:bg-blue-900/20 border-l-2 border-blue-500' => $selectedUserId === $u->id,
                                'hover:bg-gray-50 dark:hover:bg-gray-700/50 border-l-2 border-transparent' => $selectedUserId !== $u->id,
                            ])
                        >
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $u->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $u->email }}</p>
                            </div>
                            @php
                                $grantCount = App\Models\FolderPermission::where('user_id', $u->id)->count();
                            @endphp
                            @if ($grantCount)
                                <span class="flex-shrink-0 text-xs px-1.5 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full font-semibold">
                                    {{ $grantCount }}
                                </span>
                            @endif
                        </li>
                    @empty
                        <li class="px-4 py-6 text-center text-xs text-gray-400">Tidak ada pengguna.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- ╔═══════════════════════════════════════════════════╗
             ║  PANEL KANAN — Checkbox Tree Folder per User      ║
             ╚═══════════════════════════════════════════════════╝ --}}
        {{-- PANEL KANAN --}}
<div>
    @if (! $selectedUserId)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm h-80 flex flex-col items-center justify-center gap-3 text-gray-400 dark:text-gray-600">
            <svg class="w-16 h-16 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                      d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
            <p class="text-sm font-medium">Pilih pengguna di sebelah kiri</p>
            <p class="text-xs">untuk mengatur akses folder-nya</p>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

            {{-- Header user --}}
            <div class="flex items-center gap-4 px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                    {{ strtoupper(substr($this->selectedUser?->name ?? '?', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white truncate">
                        {{ $this->selectedUser?->name }}
                    </h2>
                    <p class="text-xs text-gray-400 truncate">{{ $this->selectedUser?->email }}</p>
                </div>
                <div class="flex-shrink-0 text-right">
                    <p class="text-xs text-gray-400">Folder diizinkan</p>
                    <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                        {{ collect($permMap)->where('checked', true)->count() }}
                    </p>
                </div>
            </div>

            {{-- Legend --}}
            <div class="flex items-center gap-4 px-5 py-2.5 bg-gray-50 dark:bg-gray-900/40 border-b border-gray-100 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400">
                <span class="flex items-center gap-1.5">
                    <input type="checkbox" checked disabled class="w-3 h-3 rounded border-blue-500 bg-blue-500">
                    Diizinkan
                </span>
                <span class="flex items-center gap-1.5">
                    <input type="checkbox" disabled class="w-3 h-3 rounded border-gray-300">
                    Tidak diizinkan
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="px-1.5 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-full text-[10px] font-medium">inherit</span>
                    Otomatis dari parent
                </span>
            </div>

            {{-- Checkbox tree --}}
            <div class="p-3 max-h-[calc(100vh-22rem)] overflow-y-auto">
                @php
                    // Get root folders (parent_id = null)
                    $rootFolders = $this->allFolderTree->get(null, collect());
                @endphp

                @if($rootFolders->isEmpty())
                    <div class="py-12 text-center text-sm text-gray-400 dark:text-gray-600">
                        Belum ada folder yang dibuat.
                    </div>
                @else
                    <ul class="space-y-0.5">
                        @foreach($rootFolders as $rootFolder)
                            @include('livewire.partials.permission-checkbox-node', [
                                'folder'    => $rootFolder,
                                'permMap'   => $permMap,
                                'depth'     => 0,
                            ])
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endif
</div>

    </div>
</div>

{{-- ── Modal: Tambah / Edit Folder ─────────────────────────────────────── --}}
@if ($showFolderModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 dark:bg-black/70 backdrop-blur-sm"
     wire:keydown.window.escape="$set('showFolderModal', false)">
    <div x-data x-init="$nextTick(() => $refs.fi?.focus())"
         class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden"
         x-on:click.outside="$wire.set('showFolderModal', false)">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                {{ $folderMode === 'create' ? 'Tambah Folder Utama' : 'Edit Nama Folder' }}
            </h2>
            <button wire:click="$set('showFolderModal', false)" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Folder</label>
                <input type="text" wire:model="folderName" wire:keydown.enter="saveFolder" x-ref="fi"
                       placeholder="Contoh: Akreditasi 2025"
                       class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                @error('folderName')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-3 pt-1">
                <button wire:click="saveFolder" wire:loading.attr="disabled"
                        class="flex-1 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                    <svg wire:loading wire:target="saveFolder" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    {{ $folderMode === 'create' ? 'Buat Folder' : 'Simpan' }}
                </button>
                <button wire:click="$set('showFolderModal', false)"
                        class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── Modal: Hapus Folder ──────────────────────────────────────────────── --}}
@if ($showDeleteFolderModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 dark:bg-black/70 backdrop-blur-sm"
     wire:keydown.window.escape="$set('showDeleteFolderModal', false)">
    <div class="w-full max-w-sm bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden"
         x-on:click.outside="$wire.set('showDeleteFolderModal', false)">
        <div class="p-6 text-center">
            <div class="w-14 h-14 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Hapus Folder?</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                <strong class="text-gray-800 dark:text-gray-200 break-all">{{ $deleteFolderName }}</strong>
            </p>
            <p class="text-xs text-red-500 mb-5">Semua sub-folder, file, dan data akses akan ikut terhapus.</p>
            <div class="flex gap-3">
                <button wire:click="deleteFolder" wire:loading.attr="disabled"
                        class="flex-1 flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 disabled:opacity-60 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                    <svg wire:loading wire:target="deleteFolder" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    Ya, Hapus
                </button>
                <button wire:click="$set('showDeleteFolderModal', false)"
                        class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endif

</div>{{-- /root --}}