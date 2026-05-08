{{-- resources/views/livewire/folder-permission-manager.blade.php --}}
<div class="max-w-6xl mx-auto px-4 py-6 space-y-6">

    {{-- Toast --}}
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
        style="min-width:280px"
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0"
                :class="{
                    'bg-green-50 border-green-300 text-green-800': toast.type==='success',
                    'bg-red-50 border-red-300 text-red-800': toast.type==='error',
                    'bg-yellow-50 border-yellow-300 text-yellow-800': toast.type==='warning',
                }"
                class="flex items-center gap-2.5 px-4 py-3 rounded-xl border shadow-lg text-sm font-medium pointer-events-auto"
            >
                <span x-text="toast.message" class="flex-1"></span>
                <button @click="remove(toast.id)" class="opacity-60 hover:opacity-100">✕</button>
            </div>
        </template>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Kelola Hak Akses Folder</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Hanya Super Admin yang dapat mengatur akses lintas prodi.
            </p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
            </svg>
            Super Admin Only
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Kolom kiri: Daftar Folder --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Pilih Folder</h2>
                <div class="mt-2 relative">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari folder..."
                        class="w-full pl-8 pr-3 py-1.5 text-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
            </div>

            <ul class="divide-y divide-gray-100 dark:divide-gray-700 max-h-96 overflow-y-auto">
                @forelse ($this->folders as $folder)
                    <li>
                        <button
                            wire:click="selectFolder({{ $folder->id }})"
                            @class([
                                'w-full flex items-start gap-3 px-4 py-3 text-left transition-colors',
                                'bg-blue-50 dark:bg-blue-900/20 border-l-2 border-blue-500' => $activeFolderId === $folder->id,
                                'hover:bg-gray-50 dark:hover:bg-gray-700/50' => $activeFolderId !== $folder->id,
                            ])
                        >
                            <svg class="w-5 h-5 text-yellow-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                            </svg>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $folder->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $folder->buildPath() }}</p>
                                @if ($folder->kode_lamp)
                                    <span class="inline-block mt-1 px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-[10px] font-mono">
                                        {{ $folder->kode_lamp }}
                                    </span>
                                @endif
                            </div>
                        </button>
                    </li>
                @empty
                    <li class="px-4 py-8 text-center text-sm text-gray-400">
                        Tidak ada folder ditemukan.
                    </li>
                @endforelse
            </ul>
        </div>

        {{-- Kolom kanan: Kelola Permission --}}
        <div class="space-y-4">

            @if (! $activeFolderId)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8 flex flex-col items-center justify-center text-center gap-3">
                    <svg class="w-12 h-12 text-gray-200 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-gray-400">Pilih folder dari kiri untuk mengatur akses.</p>
                </div>
            @else

                {{-- Info folder aktif --}}
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl px-4 py-3 flex items-center gap-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-blue-800 dark:text-blue-300">{{ $this->activeFolder?->name }}</p>
                        <p class="text-xs text-blue-500 dark:text-blue-400">{{ $this->activeFolder?->buildPath() }}</p>
                    </div>
                </div>

                {{-- Form beri akses --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 space-y-3">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Beri Akses User</h3>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">User</label>
                        <select wire:model="selectedUserId"
                                class="w-full text-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih user --</option>
                            @foreach ($this->users as $u)
                                <option value="{{ $u->id }}">
                                    {{ $u->name }}
                                    @if ($u->prodi) ({{ $u->prodi }}) @endif
                                    · {{ $u->roleBadge() }}
                                </option>
                            @endforeach
                        </select>
                        @error('selectedUserId')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Level Akses</label>
                            <select wire:model="permission"
                                    class="w-full text-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="read">Read — Hanya lihat</option>
                                <option value="write">Write — Lihat & upload</option>
                                <option value="admin">Admin — Semua aksi</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Berlaku Sampai</label>
                            <input type="datetime-local" wire:model="expiresAt"
                                   class="w-full text-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="mt-0.5 text-[10px] text-gray-400">Kosong = permanen</p>
                        </div>
                    </div>

                    <button
                        wire:click="grantPermission"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2"
                    >
                        <svg wire:loading wire:target="grantPermission" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                        Simpan Akses
                    </button>
                </div>

                {{-- Daftar permission yang sudah ada --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Akses Aktif
                            <span class="ml-1.5 px-1.5 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs">
                                {{ $this->folderPermissions->count() }}
                            </span>
                        </h3>
                    </div>

                    @if ($this->folderPermissions->isEmpty())
                        <p class="px-4 py-6 text-center text-sm text-gray-400">Belum ada akses yang diberikan.</p>
                    @else
                        <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($this->folderPermissions as $perm)
                                <li class="flex items-center gap-3 px-4 py-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400">
                                            {{ strtoupper(substr($perm->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                            {{ $perm->user->name }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span @class([
                                                'px-1.5 py-0.5 rounded text-[10px] font-semibold',
                                                'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' => $perm->permission === 'admin',
                                                'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' => $perm->permission === 'write',
                                                'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' => $perm->permission === 'read',
                                            ])>
                                                {{ strtoupper($perm->permission) }}
                                            </span>
                                            @if ($perm->expires_at)
                                                <span @class([
                                                    'text-[10px]',
                                                    'text-red-500' => $perm->isExpired(),
                                                    'text-gray-400' => ! $perm->isExpired(),
                                                ])>
                                                    {{ $perm->isExpired() ? 'Kedaluwarsa' : 'Sampai' }}
                                                    {{ $perm->expires_at->format('d M Y') }}
                                                </span>
                                            @else
                                                <span class="text-[10px] text-gray-400">Permanen</span>
                                            @endif
                                        </div>
                                    </div>
                                    <button
                                        wire:click="revokePermission({{ $perm->id }})"
                                        wire:confirm="Cabut akses {{ $perm->user->name }}?"
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                                        title="Cabut akses"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

            @endif
        </div>
    </div>
</div>