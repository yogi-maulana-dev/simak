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
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">Manajemen Slider</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola banner slider halaman utama</p>
        </div>
        <button wire:click="create"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold
                       bg-[#0d4a2a] hover:bg-[#0a3d22] text-white shadow-md transition-all hover:scale-105">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Slider
        </button>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <div class="relative max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input wire:model.live.debounce.400ms="search" type="text" placeholder="Cari slider..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900
                          text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a] outline-none transition"/>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 w-12">#</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Gambar</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Judul</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Urutan</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($sliders as $slider)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $slider->id }}</td>
                            <td class="px-4 py-3">
                                <div class="w-20 h-12 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800">
                                    @if ($slider->image_path)
                                        <img src="{{ $slider->imageUrl() }}" alt=""
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $slider->title }}</p>
                                @if ($slider->subtitle)
                                    <p class="text-xs text-gray-400 mt-0.5 truncate max-w-[200px]">{{ $slider->subtitle }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300">
                                    {{ $slider->order }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <button wire:click="toggleActive({{ $slider->id }})"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold transition-colors
                                               {{ $slider->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $slider->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                    {{ $slider->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $slider->id }})"
                                            class="p-1.5 rounded-lg text-gray-500 hover:text-[#0d4a2a] hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $slider->id }})"
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
                            <td colspan="6" class="px-4 py-12 text-center text-sm text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Belum ada slider.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($sliders->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">
                {{ $sliders->links() }}
            </div>
        @endif
    </div>

    {{-- ── Modal Form ── --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-data x-init="document.body.style.overflow='hidden'"
             x-destroy="document.body.style.overflow=''">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>

            <div class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-2xl shadow-2xl z-10
                        max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white dark:bg-gray-900 flex items-center justify-between p-5 border-b border-gray-100 dark:border-gray-800 z-10">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $editingId ? 'Edit Slider' : 'Tambah Slider' }}
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
                        <input wire:model="title" type="text" placeholder="Judul slider..."
                               class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800
                                      text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a] outline-none transition"/>
                        @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Subjudul --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Subjudul</label>
                        <input wire:model="subtitle" type="text" placeholder="Deskripsi singkat..."
                               class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800
                                      text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a] outline-none transition"/>
                        @error('subtitle') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Gambar --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Gambar</label>
                        @if ($image)
                            <div class="mb-2 relative w-full h-32 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800">
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                                <button type="button" wire:click="$set('image', null)"
                                        class="absolute top-2 right-2 w-7 h-7 flex items-center justify-center rounded-full bg-red-600 text-white">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @elseif ($existingImage)
                            <div class="mb-2 relative w-full h-32 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800">
                                <img src="{{ Storage::disk('public')->url($existingImage) }}" class="w-full h-full object-cover">
                                <p class="absolute bottom-2 left-2 text-[10px] bg-black/50 text-white px-2 py-0.5 rounded">Gambar saat ini</p>
                            </div>
                        @endif
                        <input wire:model="image" type="file" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                                      file:text-sm file:font-semibold file:bg-[#0d4a2a]/10 file:text-[#0d4a2a] hover:file:bg-[#0d4a2a]/20 transition"/>
                        <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG, WebP. Maks 2MB.</p>
                        @error('image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Link --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">URL Tautan</label>
                            <input wire:model="link_url" type="text" placeholder="https://..."
                                   class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800
                                          text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a] outline-none transition"/>
                            @error('link_url') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Label Tombol</label>
                            <input wire:model="link_label" type="text" placeholder="Pelajari Lebih..."
                                   class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800
                                          text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a] outline-none transition"/>
                        </div>
                    </div>

                    {{-- Urutan & Status --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Urutan</label>
                            <input wire:model="order" type="number" min="0"
                                   class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800
                                          text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#0d4a2a]/30 focus:border-[#0d4a2a] outline-none transition"/>
                        </div>
                        <div class="flex flex-col justify-end pb-1">
                            <label class="flex items-center gap-2.5 cursor-pointer">
                                <div class="relative">
                                    <input wire:model="is_active" type="checkbox" class="sr-only peer">
                                    <div class="w-10 h-5 bg-gray-200 peer-checked:bg-[#0d4a2a] rounded-full transition-colors"></div>
                                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Aktif</span>
                            </label>
                        </div>
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
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Hapus Slider?</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">Tindakan ini tidak dapat dibatalkan. Gambar juga akan dihapus.</p>
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