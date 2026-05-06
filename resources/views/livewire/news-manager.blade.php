<div class="min-h-screen bg-gray-50 dark:bg-gray-950 p-4 sm:p-6 lg:p-8">

    {{-- Toast --}}
    @if (session('toast'))
        <div x-data="{ show: true }"
             x-init="setTimeout(() => show = false, 3500)"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0"
             class="fixed top-5 right-5 z-[100] flex items-center gap-3 px-4 py-3 rounded-xl shadow-xl
                    {{ session('toast')['type'] === 'success' ? 'bg-emerald-600' : 'bg-red-600' }} text-white text-sm font-medium">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @if (session('toast')['type'] === 'success')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                @endif
            </svg>
            {{ session('toast')['msg'] }}
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">Manajemen Berita</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola berita dan pengumuman</p>
        </div>
        <button wire:click="create"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold
                       bg-[#0d4a2a] hover:bg-[#0a3d22] text-white shadow-md transition-all hover:scale-105">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Berita
        </button>
    </div>

    {{-- Filter & Search --}}
    <div class="mb-4 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1 max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input wire:model.live.debounce.400ms="search" type="text" placeholder="Cari berita..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900
                          text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a] outline-none transition"/>
        </div>
        <select wire:model.live="filterCategory"
                class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900
                       text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a] outline-none transition">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterStatus"
                class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900
                       text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a] outline-none transition">
            <option value="">Semua Status</option>
            <option value="published">Dipublikasikan</option>
            <option value="draft">Draft</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Berita</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Kategori</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Unggulan</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Views</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Tanggal</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($news as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-14 h-10 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800 flex-shrink-0">
                                        @if ($item->thumbnail_path)
                                            <img src="{{ $item->thumbnailUrl() }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 dark:text-white truncate max-w-[220px]">{{ $item->title }}</p>
                                        <p class="text-[11px] text-gray-400 truncate max-w-[220px]">/{{ $item->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($item->category)
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#0d4a2a]/10 dark:bg-emerald-900/30 text-[#0d4a2a] dark:text-emerald-400 capitalize">
                                        {{ $item->category }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <button wire:click="togglePublished({{ $item->id }})"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold transition-colors
                                               {{ $item->is_published ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $item->is_published ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                    {{ $item->is_published ? 'Publik' : 'Draft' }}
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <button wire:click="toggleFeatured({{ $item->id }})"
                                        class="p-1.5 rounded-lg transition-colors {{ $item->is_featured ? 'text-yellow-500' : 'text-gray-300 hover:text-yellow-400' }}">
                                    <svg class="w-4 h-4" fill="{{ $item->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                </button>
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">
                                {{ number_format($item->views) }}
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs whitespace-nowrap">
                                {{ $item->published_at?->format('d/m/Y') ?? $item->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($item->is_published)
                                        <a href="{{ route('news.show', $item->slug) }}" target="_blank"
                                           class="p-1.5 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    @endif
                                    <button wire:click="edit({{ $item->id }})"
                                            class="p-1.5 rounded-lg text-gray-500 hover:text-[#0d4a2a] hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $item->id }})"
                                            class="p-1.5 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-sm text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                                Belum ada berita.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($news->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">
                {{ $news->links() }}
            </div>
        @endif
    </div>

    {{-- ── Modal Form ── --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-data x-init="document.body.style.overflow='hidden'"
             x-destroy="document.body.style.overflow=''">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>

            <div class="relative w-full max-w-2xl bg-white dark:bg-gray-900 rounded-2xl shadow-2xl z-10
                        max-h-[92vh] overflow-y-auto">
                <div class="sticky top-0 bg-white dark:bg-gray-900 flex items-center justify-between p-5 border-b border-gray-100 dark:border-gray-800 z-10">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $editingId ? 'Edit Berita' : 'Tambah Berita' }}
                    </h2>
                    <button wire:click="closeModal" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit="save" class="p-5 space-y-4">
                    {{-- Judul --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input wire:model="title" type="text" placeholder="Judul berita..."
                               class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800
                                      text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a] outline-none transition"/>
                        @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Kategori</label>
                        <select wire:model="category"
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800
                                       text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a] outline-none transition">
                            <option value="">— Pilih Kategori —</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Thumbnail --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Thumbnail</label>
                        @if ($thumbnail)
                            <div class="mb-2 relative w-full h-36 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800">
                                <img src="{{ $thumbnail->temporaryUrl() }}" class="w-full h-full object-cover">
                                <button type="button" wire:click="$set('thumbnail', null)"
                                        class="absolute top-2 right-2 w-7 h-7 flex items-center justify-center rounded-full bg-red-600 text-white">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @elseif ($existingThumbnail)
                            <div class="mb-2 relative w-full h-36 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800">
                                <img src="{{ Storage::disk('public')->url($existingThumbnail) }}" class="w-full h-full object-cover">
                                <p class="absolute bottom-2 left-2 text-[10px] bg-black/50 text-white px-2 py-0.5 rounded">Thumbnail saat ini</p>
                            </div>
                        @endif
                        <input wire:model="thumbnail" type="file" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                                      file:text-sm file:font-semibold file:bg-[#0d4a2a]/10 file:text-[#0d4a2a] hover:file:bg-[#0d4a2a]/20 transition"/>
                        <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG, WebP. Maks 3MB. Rasio 16:9 disarankan.</p>
                        @error('thumbnail') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Isi Berita --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            Isi Berita <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="body" rows="8" placeholder="Tuliskan isi berita di sini..."
                                  class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800
                                         text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a] outline-none transition resize-y min-h-[160px]"></textarea>
                        @error('body') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Ringkasan --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            Ringkasan <span class="text-xs font-normal text-gray-400">(opsional – otomatis dari isi jika kosong)</span>
                        </label>
                        <textarea wire:model="excerpt" rows="2" placeholder="Ringkasan singkat untuk preview..."
                                  class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800
                                         text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a] outline-none transition resize-none"></textarea>
                        @error('excerpt') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Toggles --}}
                    <div class="flex flex-wrap gap-6 pt-1">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <div class="relative">
                                <input wire:model="is_published" type="checkbox" class="sr-only peer">
                                <div class="w-10 h-5 bg-gray-200 peer-checked:bg-[#0d4a2a] rounded-full transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Publikasikan</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <div class="relative">
                                <input wire:model="is_featured" type="checkbox" class="sr-only peer">
                                <div class="w-10 h-5 bg-gray-200 peer-checked:bg-yellow-400 rounded-full transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">⭐ Berita Unggulan</span>
                        </label>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3 pt-2">
                        <button type="button" wire:click="closeModal"
                                class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold
                                       text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 rounded-xl bg-[#0d4a2a] hover:bg-[#0a3d22] text-white text-sm font-semibold
                                       shadow-md transition-all hover:scale-[1.02]">
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Perbarui' : 'Simpan' }}</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ── Confirm Delete ── --}}
    @if ($showConfirm)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="$set('showConfirm', false)"></div>
            <div class="relative w-full max-w-sm bg-white dark:bg-gray-900 rounded-2xl shadow-2xl p-6 z-10 text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Hapus Berita?</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">Tindakan ini tidak dapat dibatalkan. Thumbnail juga akan dihapus.</p>
                <div class="flex gap-3">
                    <button wire:click="$set('showConfirm', false)"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button wire:click="delete"
                            class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition-colors">
                        <span wire:loading.remove wire:target="delete">Ya, Hapus</span>
                        <span wire:loading wire:target="delete">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>