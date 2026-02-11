<div class="max-w-4xl mx-auto p-6">
    {{-- Header --}}
    <div class="mb-6 pb-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Arsip</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Perbarui informasi arsip "{{ $arsip->judul }}"
                </p>
            </div>
            <a href="{{ route('arsip.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <form wire:submit.prevent="update" class="p-6 space-y-6">
            {{-- Judul --}}
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">
                    Judul Arsip *
                </label>
                <input type="text" 
                       id="judul" 
                       wire:model.defer="judul" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('judul') border-red-300 @enderror"
                       placeholder="Masukkan judul arsip">
                @error('judul')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi
                </label>
                <textarea id="deskripsi" 
                          wire:model.defer="deskripsi" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('deskripsi') border-red-300 @enderror"
                          placeholder="Deskripsi arsip..."></textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Fakultas --}}
            <div>
                <label for="fakultas_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Fakultas *
                </label>
                <div class="relative">
                    <select id="fakultas_id" 
                            wire:model="fakultas_id" 
                            @if(auth()->user()->hasRole('admin_fakultas') || auth()->user()->hasRole('admin_prodi')) 
                                disabled 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500 cursor-not-allowed"
                            @else
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('fakultas_id') border-red-300 @enderror"
                            @endif>
                        <option value="">Pilih Fakultas</option>
                        @foreach($fakultas as $f)
                            <option value="{{ $f->id }}" 
                                    @if(old('fakultas_id', $arsip->fakultas_id ?? '') == $f->id) selected @endif>
                                {{ $f->nama_fakultas }}
                            </option>
                        @endforeach
                    </select>
                    @if(auth()->user()->hasRole('admin_fakultas') || auth()->user()->hasRole('admin_prodi'))
                        <div class="absolute inset-0 bg-gray-50 opacity-50 cursor-not-allowed"></div>
                        <p class="mt-1 text-xs text-gray-500">
                            Fakultas terkunci karena peran Anda
                        </p>
                    @endif
                </div>
                @error('fakultas_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Program Studi --}}
            <div>
                <label for="prodi_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Program Studi
                </label>
                <div class="relative">
                    <select id="prodi_id" 
                            wire:model="prodi_id" 
                            @if(auth()->user()->hasRole('admin_prodi')) 
                                disabled 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500 cursor-not-allowed"
                            @else
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('prodi_id') border-red-300 @enderror"
                            @endif>
                        <option value="">Pilih Program Studi (opsional)</option>
                        @foreach($prodis as $p)
                            <option value="{{ $p->id }}"
                                    @if(old('prodi_id', $arsip->prodi_id ?? '') == $p->id) selected @endif>
                                {{ $p->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                    @if(auth()->user()->hasRole('admin_prodi'))
                        <div class="absolute inset-0 bg-gray-50 opacity-50 cursor-not-allowed"></div>
                        <p class="mt-1 text-xs text-gray-500">
                            Program Studi terkunci karena peran Anda
                        </p>
                    @endif
                </div>
                @error('prodi_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- File --}}
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">File Arsip</h3>
                
                {{-- File saat ini --}}
                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-700 mb-2">File saat ini:</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ basename($arsip->file) }}
                                </p>
                                                    <p class="text-xs text-gray-500">
                            @php
                                $filePath = 'arsip/' . basename($arsip->file);
                                $fullPath = storage_path('app/public/arsip/' . $filePath);
                                
                                if (file_exists($fullPath)) {
                                    echo number_format(filesize($fullPath) / 1024, 2) . ' KB';
                                } elseif (Storage::disk('public')->exists($filePath)) {
                                    echo number_format(Storage::disk('public')->size($filePath) / 1024, 2) . ' KB';
                                } else {
                                    echo 'File tidak ditemukan';
                                }
                            @endphp
                        </p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/arsip/' . $arsip->file) }}" 
                           target="_blank"
                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>

                {{-- Upload file baru --}}
                <div>
                    <label for="file_baru" class="block text-sm font-medium text-gray-700 mb-2">
                        Ganti File (opsional)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md @error('file_baru') border-red-300 @enderror">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file_baru" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Upload file</span>
                                    <input id="file_baru" 
                                           type="file" 
                                           wire:model="file_baru" 
                                           class="sr-only"
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PDF, DOC, XLS, JPG, PNG (Max: 10MB)
                            </p>
                        </div>
                    </div>
                    
                    @if($file_baru)
                        <div class="mt-3 p-3 bg-green-50 rounded-md">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-green-800">
                                    File baru siap diupload: {{ $file_baru->getClientOriginalName() }}
                                </span>
                            </div>
                        </div>
                    @endif
                    
                    @error('file_baru')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <button type="button" 
                        onclick="window.history.back()"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </button>
                
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled">
                    <svg wire:loading wire:target="update" 
                         class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" 
                         xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg wire:loading.remove wire:target="update" 
                         class="-ml-1 mr-2 h-4 w-4" 
                         fill="none" 
                         stroke="currentColor" 
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Loading State --}}
    <div wire:loading.flex wire:target="update" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl">
            <div class="flex items-center">
                <svg class="animate-spin h-8 w-8 text-indigo-600 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Menyimpan perubahan...</p>
                    <p class="text-sm text-gray-600">Mohon tunggu sebentar</p>
                </div>
            </div>
        </div>
    </div>
</div>