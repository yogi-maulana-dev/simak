<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Tambah Arsip Baru (Admin)</h2>
                        <p class="mt-1 text-sm text-gray-600">Sebagai superadmin, Anda dapat membuat arsip untuk fakultas/prodi manapun</p>
                    </div>

                    @if (session()->has('success'))
                        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            {{-- Judul --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Judul Arsip *</label>
                                <input type="text" wire:model="judul" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('judul') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea wire:model="deskripsi" rows="3"
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                                @error('deskripsi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- Fakultas --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fakultas *</label>
                                <select wire:model="fakultas_id" 
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Fakultas</option>
                                    @foreach($fakultas as $f)
                                        <option value="{{ $f->id }}">{{ $f->nama_fakultas }}</option>
                                    @endforeach
                                </select>
                                @error('fakultas_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- Prodi --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Program Studi</label>
                                <select wire:model="prodi_id" 
                                        {{ !$fakultas_id ? 'disabled' : '' }}
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 {{ !$fakultas_id ? 'bg-gray-100' : '' }}">
                                    <option value="">Pilih Prodi</option>
                                    @if($fakultas_id)
                                        @foreach($prodiOptions as $prodi)
                                            <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('prodi_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- User --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Uploader *</label>
                                <select wire:model="user_id" 
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                @error('user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- File Upload --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">File Arsip *</label>
                                <input type="file" wire:model="file" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                @if($file)
                                    <p class="mt-1 text-sm text-gray-500">File: {{ $file->getClientOriginalName() }} ({{ round($file->getSize() / 1024, 2) }} KB)</p>
                                @endif
                            </div>

                            {{-- Thumbnail --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Thumbnail (Opsional)</label>
                                <input type="file" wire:model="thumbnail" accept="image/*"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('thumbnail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                @if($thumbnail)
                                    <div class="mt-2">
                                        <img src="{{ $thumbnail->temporaryUrl() }}" alt="Preview" class="h-32 w-auto rounded">
                                    </div>
                                @endif
                            </div>

                            {{-- Public Access --}}
                            <div class="md:col-span-2">
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="is_public" id="is_public"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="is_public" class="ml-2 block text-sm text-gray-900">
                                        Arsip dapat diakses publik (tanpa login)
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('admin.arsip.index') }}" 
                               class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Batal
                            </a>
                            <button type="submit" 
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                                <span wire:loading.remove>Simpan Arsip</span>
                                <span wire:loading wire:target="save" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Menyimpan...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
