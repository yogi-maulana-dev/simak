<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

        {{-- Header --}}
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Slider</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Slider akan tampil di halaman utama publik. Maksimal disarankan 5 slide.
                </p>
            </div>
            <button wire:click="openCreate"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white
                           bg-gradient-to-r from-[#0a3d22] to-[#1a6b3e] hover:from-[#082e1a] hover:to-[#155831]
                           shadow-md shadow-[#0d4a2a]/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Slider
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

        {{-- Slider grid cards --}}
        @if ($this->sliders->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada slider. Klik <strong>Tambah Slider</strong> di atas.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($this->sliders as $i => $s)
                    <div wire:key="slider-{{ $s->id }}"
                         class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden transition-all
                                {{ $s->is_active ? '' : 'opacity-60' }}">

                        {{-- Image preview --}}
                        <div class="relative aspect-[16/7] bg-gray-100 dark:bg-gray-900">
                            <img src="{{ $s->imageUrl() }}" alt="{{ $s->title }}"
                                 class="w-full h-full object-cover">

                            {{-- Status badge --}}
                            <div class="absolute top-2.5 left-2.5">
                                @if ($s->is_active)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500 text-white shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gray-500 text-white shadow-sm">
                                        Nonaktif
                                    </span>
                                @endif
                            </div>

                            {{-- Order badge --}}
                            <div class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-black/60 backdrop-blur-sm text-white text-xs font-bold flex items-center justify-center">
                                #{{ $i + 1 }}
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="p-4">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white truncate" title="{{ $s->title }}">
                                {{ $s->title }}
                            </h3>
                            @if ($s->subtitle)
                                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400 line-clamp-2">{{ $s->subtitle }}</p>
                            @endif
                            @if ($s->link_url)
                                <p class="mt-1.5 text-[11px] text-blue-600 dark:text-blue-400 truncate flex items-center gap-1">
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"/>
                                    </svg>
                                    {{ $s->link_url }}
                                </p>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <div class="flex items-center gap-1">
                                <button wire:click="moveUp({{ $s->id }})" @disabled($loop->first)
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
                                        title="Naikkan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                </button>
                                <button wire:click="moveDown({{ $s->id }})" @disabled($loop->last)
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
                                        title="Turunkan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <button wire:click="toggleActive({{ $s->id }})"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-{{ $s->is_active ? 'orange' : 'emerald' }}-600 hover:bg-{{ $s->is_active ? 'orange' : 'emerald' }}-50 dark:hover:bg-{{ $s->is_active ? 'orange' : 'emerald' }}-900/30 transition-colors"
                                        title="{{ $s->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    @if ($s->is_active)
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    @else
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </button>
                            </div>
                            <div class="flex items-center gap-1">
                                <button wire:click="openEdit({{ $s->id }})"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 transition-colors"
                                        title="Edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button wire:click="confirmDelete({{ $s->id }})"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors"
                                        title="Hapus">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════ MODAL FORM ═══════════════════════════ --}}
    @if ($showFormModal)
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center sm:p-4 bg-black/60 backdrop-blur-sm"
         wire:keydown.window.escape="$set('showFormModal', false)">
        <div class="absolute inset-0" wire:click="$set('showFormModal', false)"></div>

        <div class="relative w-full sm:max-w-xl bg-white dark:bg-gray-900 rounded-t-3xl sm:rounded-2xl shadow-2xl max-h-[92dvh] flex flex-col">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
                <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#0d4a2a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $editId ? 'Edit Slider' : 'Tambah Slider' }}
                </h2>
                <button wire:click="$set('showFormModal', false)" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Body --}}
            <form wire:submit="save" class="flex-1 overflow-y-auto px-6 py-5 space-y-4">

                {{-- Image upload --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                        Gambar Slider <span class="text-red-500">*</span>
                    </label>

                    @if ($image)
                        <div class="relative rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 mb-2">
                            <img src="{{ $image->temporaryUrl() }}" class="w-full aspect-[16/7] object-cover">
                            <button type="button" wire:click="$set('image', null)"
                                    class="absolute top-2 right-2 p-1.5 rounded-full bg-red-500 text-white hover:bg-red-600 shadow-md">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @elseif ($existingImagePath)
                        <div class="relative rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 mb-2">
                            <img src="{{ \Storage::disk('public')->url($existingImagePath) }}"
                                 class="w-full aspect-[16/7] object-cover">
                            <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full text-[10px] font-bold bg-black/60 text-white backdrop-blur-sm">
                                Gambar saat ini
                            </span>
                        </div>
                    @endif

                    <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-700 hover:border-[#0d4a2a] dark:hover:border-emerald-500 hover:bg-[#0d4a2a]/5 dark:hover:bg-emerald-900/10 cursor-pointer transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span class="text-sm text-gray-600 dark:text-gray-300">
                            {{ $image ? 'Ganti gambar' : ($existingImagePath ? 'Ganti gambar' : 'Klik untuk pilih gambar') }}
                        </span>
                        <input type="file" wire:model="image" accept="image/jpeg,image/png,image/webp" class="hidden">
                    </label>
                    <p class="mt-1 text-[10px] text-gray-400">
                        JPG/PNG/WebP, maksimal 5 MB. Disarankan rasio 16:7 (mis. 1600×700px).
                    </p>
                    <div wire:loading wire:target="image" class="mt-1 text-xs text-blue-500">Mengunggah...</div>
                    @error('image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Title --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="title" required maxlength="200"
                           placeholder="Mis. Selamat Datang di SIMAK UML"
                           class="w-full px-3 py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a]">
                    @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Subtitle --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                        Sub-judul / Deskripsi
                    </label>
                    <textarea wire:model="subtitle" rows="2" maxlength="255"
                              placeholder="Deskripsi singkat di bawah judul (opsional)"
                              class="w-full px-3 py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a] resize-none"></textarea>
                </div>

                {{-- Link --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                            Link URL <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="url" wire:model="linkUrl" placeholder="https://..."
                               class="w-full px-3 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a]">
                        @error('linkUrl') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                            Label Tombol
                        </label>
                        <input type="text" wire:model="linkLabel" maxlength="100" placeholder="Pelajari Lebih Lanjut"
                               class="w-full px-3 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a]">
                    </div>
                </div>

                {{-- Order + Active --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                            Urutan
                        </label>
                        <input type="number" wire:model="sortOrder" min="0" max="9999"
                               class="w-full px-3 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a]">
                        <p class="mt-1 text-[10px] text-gray-400">Angka kecil tampil duluan</p>
                    </div>
                    <div class="flex items-end">
                        <label class="flex items-center gap-2 cursor-pointer w-full">
                            <input type="checkbox" wire:model="isActive"
                                   class="rounded border-gray-300 text-[#0d4a2a] focus:ring-[#0d4a2a]/30">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Aktifkan slider ini</span>
                        </label>
                    </div>
                </div>
            </form>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex gap-2.5 flex-shrink-0">
                <button wire:click="$set('showFormModal', false)"
                        class="flex-shrink-0 px-4 py-2.5 text-sm font-semibold rounded-xl
                               text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700">
                    Batal
                </button>
                <button wire:click="save" wire:loading.attr="disabled"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white
                               bg-gradient-to-r from-[#0a3d22] to-[#1a6b3e] hover:from-[#082e1a] hover:to-[#155831]
                               shadow-md shadow-[#0d4a2a]/20 disabled:opacity-60 transition-all">
                    <svg wire:loading wire:target="save" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    {{ $editId ? 'Simpan Perubahan' : 'Tambah Slider' }}
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════ MODAL DELETE ═══════════════════════════ --}}
    @if ($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Hapus Slider?</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                Slider <strong class="text-gray-700 dark:text-gray-200">"{{ $deleteName }}"</strong>
                akan dihapus permanen beserta gambarnya.
            </p>
            <div class="flex gap-3">
                <button wire:click="$set('showDeleteModal', false)"
                        class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg">
                    Batal
                </button>
                <button wire:click="deleteSlider"
                        class="flex-1 px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg">
                    Hapus
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
