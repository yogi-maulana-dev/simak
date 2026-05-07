<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

        {{-- Header --}}
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Berita</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Berita yang dipublikasikan akan tampil di halaman utama (publik, tanpa login).
                </p>
            </div>
            <button wire:click="openCreate"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white
                           bg-gradient-to-r from-[#0a3d22] to-[#1a6b3e] hover:from-[#082e1a] hover:to-[#155831]
                           shadow-md shadow-[#0d4a2a]/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tulis Berita
            </button>
        </div>

        {{-- Flash --}}
        @if (session('flash'))
            <div class="px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-sm text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('flash') }}
            </div>
        @endif

        {{-- Filter --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search" type="search"
                       placeholder="Cari judul atau isi berita..."
                       class="w-full pl-9 pr-3 py-2.5 text-sm bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-gray-100">
            </div>
            <select wire:model.live="filterCategory"
                    class="w-full sm:w-48 px-3 py-2.5 text-sm bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-200">
                <option value="">Semua kategori</option>
                @foreach ($categories as $val => $label)
                    <option value="{{ $val }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- News list --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Berita</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Kategori</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Penulis</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($this->newsList as $n)
                            <tr wire:key="news-{{ $n->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                <td class="px-4 py-3">
                                    <div class="flex items-start gap-3">
                                        @if ($n->thumbnail_path)
                                            <img src="{{ $n->thumbnailUrl() }}" class="w-16 h-12 rounded-lg object-cover flex-shrink-0">
                                        @else
                                            <div class="w-16 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-1">{{ $n->title }}</p>
                                            <p class="mt-0.5 text-xs text-gray-400 line-clamp-2 max-w-md">{{ $n->excerpt }}</p>
                                            <p class="mt-1 text-[10px] text-gray-400">
                                                {{ $n->published_at?->translatedFormat('d M Y, H:i') ?? '— belum dijadwalkan' }}
                                                · 👁 {{ $n->views }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 hidden md:table-cell">
                                    @if ($n->category)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 capitalize">
                                            {{ $categories[$n->category] ?? $n->category }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 hidden lg:table-cell text-xs text-gray-500 dark:text-gray-400">
                                    {{ $n->author?->name ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <button wire:click="togglePublish({{ $n->id }})"
                                            class="cursor-pointer">
                                        @if ($n->is_published)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Tayang
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                                Draft
                                            </span>
                                        @endif
                                    </button>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('news.show', $n->slug) }}" target="_blank"
                                           class="p-1.5 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30"
                                           title="Lihat di halaman publik">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <button wire:click="openEdit({{ $n->id }})"
                                                class="p-1.5 rounded-lg text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $n->id }})"
                                                class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-sm text-gray-400 dark:text-gray-600">
                                    {{ $search || $filterCategory ? 'Tidak ada berita yang cocok.' : 'Belum ada berita. Klik "Tulis Berita" di atas.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($this->newsList->hasPages())
                <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                    {{ $this->newsList->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════ MODAL FORM ═══════════════════════════ --}}
    @if ($showFormModal)
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center sm:p-4 bg-black/60 backdrop-blur-sm">
        <div class="absolute inset-0" wire:click="$set('showFormModal', false)"></div>

        <div class="relative w-full sm:max-w-2xl bg-white dark:bg-gray-900 rounded-t-3xl sm:rounded-2xl shadow-2xl max-h-[92dvh] flex flex-col">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
                <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#0d4a2a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    {{ $editId ? 'Edit Berita' : 'Tulis Berita Baru' }}
                </h2>
                <button wire:click="$set('showFormModal', false)" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="save" class="flex-1 overflow-y-auto px-6 py-5 space-y-4">

                {{-- Thumbnail --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                        Thumbnail <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    @if ($thumbnail)
                        <div class="relative rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 mb-2">
                            <img src="{{ $thumbnail->temporaryUrl() }}" class="w-full aspect-[16/9] object-cover">
                            <button type="button" wire:click="$set('thumbnail', null)"
                                    class="absolute top-2 right-2 p-1.5 rounded-full bg-red-500 text-white">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    @elseif ($existingThumbnailPath)
                        <div class="relative rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 mb-2">
                            <img src="{{ \Storage::disk('public')->url($existingThumbnailPath) }}" class="w-full aspect-[16/9] object-cover">
                        </div>
                    @endif
                    <label class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-700 hover:border-[#0d4a2a] cursor-pointer transition-colors text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span class="text-gray-600 dark:text-gray-300">Pilih gambar (max 5 MB)</span>
                        <input type="file" wire:model="thumbnail" accept="image/jpeg,image/png,image/webp" class="hidden">
                    </label>
                    <div wire:loading wire:target="thumbnail" class="mt-1 text-xs text-blue-500">Mengunggah...</div>
                    @error('thumbnail') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Title --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                        Judul Berita <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="title" required maxlength="250"
                           class="w-full px-3 py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a]">
                    @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Category + Publish date --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Kategori</label>
                        <select wire:model="category"
                                class="w-full px-3 py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-200">
                            <option value="">— pilih kategori —</option>
                            @foreach ($categories as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Tanggal Tayang</label>
                        <input type="datetime-local" wire:model="publishedAt"
                               class="w-full px-3 py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-gray-100">
                    </div>
                </div>

                {{-- Excerpt --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                        Ringkasan <span class="text-gray-400 font-normal">(otomatis dari isi jika kosong)</span>
                    </label>
                    <textarea wire:model="excerpt" rows="2" maxlength="500"
                              placeholder="Ringkasan singkat untuk preview di daftar berita..."
                              class="w-full px-3 py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a] resize-none"></textarea>
                </div>

                {{-- Content --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                        Isi Berita <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="content" rows="10" required
                              placeholder="Tulis isi berita di sini. Bisa pakai HTML sederhana seperti <p>, <strong>, <a>, <br>..."
                              class="w-full px-3 py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a] font-mono"></textarea>
                    @error('content') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Status --}}
                <div class="flex items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800/50">
                    <input id="isPublished" type="checkbox" wire:model="isPublished"
                           class="rounded border-gray-300 text-[#0d4a2a] focus:ring-[#0d4a2a]/30">
                    <label for="isPublished" class="text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                        Tayangkan berita ini sekarang
                    </label>
                </div>
            </form>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex gap-2.5 flex-shrink-0">
                <button wire:click="$set('showFormModal', false)"
                        class="flex-shrink-0 px-4 py-2.5 text-sm font-semibold rounded-xl text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">
                    Batal
                </button>
                <button wire:click="save" wire:loading.attr="disabled"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#0a3d22] to-[#1a6b3e] hover:from-[#082e1a] hover:to-[#155831] shadow-md disabled:opacity-60">
                    <svg wire:loading wire:target="save" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    {{ $editId ? 'Simpan Perubahan' : 'Publikasikan' }}
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- DELETE CONFIRM --}}
    @if ($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Hapus Berita?</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 break-words">
                <strong>"{{ $deleteName }}"</strong> akan dihapus permanen.
            </p>
            <div class="flex gap-3">
                <button wire:click="$set('showDeleteModal', false)"
                        class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 rounded-lg">Batal</button>
                <button wire:click="deleteNews"
                        class="flex-1 px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg">Hapus</button>
            </div>
        </div>
    </div>
    @endif

</div>
