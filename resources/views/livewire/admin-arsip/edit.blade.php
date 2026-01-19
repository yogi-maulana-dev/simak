<div>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold mb-6">Edit Arsip</h2>
                    
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form wire:submit.prevent="update">
                        <!-- Judul -->
                        <div class="mb-4">
                            <label for="judul" class="block text-sm font-medium text-gray-700">Judul *</label>
                            <input type="text" 
                                   id="judul"
                                   wire:model="judul"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('judul') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea id="deskripsi"
                                      wire:model="deskripsi"
                                      rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                            @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- User -->
                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700">Pemilik Arsip *</label>
                            <select id="user_id"
                                    wire:model="user_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Pilih User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} 
                                        @if($user->fakultas)
                                            - {{ $user->fakultas->nama_fakultas }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            
                            @if($selectedUser)
                                <div class="mt-2 text-sm text-gray-600">
                                    <p><strong>Fakultas User:</strong> {{ $selectedUser->fakultas->nama_fakultas ?? '-' }}</p>
                                    <p><strong>Role User:</strong> {{ $selectedUser->role->name ?? '-' }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Fakultas (Read-only jika user sudah dipilih) -->
                        <div class="mb-4">
                            <label for="fakultas_id" class="block text-sm font-medium text-gray-700">
                                Fakultas *
                                @if($selectedUser)
                                    <span class="text-xs text-gray-500">(Otomatis dari user)</span>
                                @endif
                            </label>
                            @if($selectedUser && $selectedUser->fakultas_id)
                                <input type="text" 
                                       value="{{ $selectedUser->fakultas->nama_fakultas ?? '-' }}"
                                       disabled
                                       class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm">
                                <input type="hidden" wire:model="fakultas_id">
                            @else
                                <select id="fakultas_id"
                                        wire:model="fakultas_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Pilih Fakultas</option>
                                    @foreach($fakultas as $fak)
                                        <option value="{{ $fak->id }}">{{ $fak->nama_fakultas }}</option>
                                    @endforeach
                                </select>
                            @endif
                            @error('fakultas_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Prodi -->
                        <div class="mb-4">
                            <label for="prodi_id" class="block text-sm font-medium text-gray-700">Program Studi</label>
                            <select id="prodi_id"
                                    wire:model="prodi_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    @if($selectedUser && !count($prodiOptions)) disabled @endif>
                                <option value="">Pilih Program Studi</option>
                                @foreach($prodiOptions as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                            @if($selectedUser && !count($prodiOptions))
                                <p class="mt-1 text-sm text-gray-500">Tidak ada program studi untuk fakultas ini</p>
                            @endif
                            @error('prodi_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- File -->
                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700">
                                File (Kosongkan jika tidak ingin mengganti)
                            </label>
                            <input type="file" 
                                   id="file"
                                   wire:model="file"
                                   class="mt-1 block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-full file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100">
                            @error('file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            
                            @if($old_file)
                                <div class="mt-2 text-sm text-gray-500">
                                    File saat ini: 
                                    <a href="{{ Storage::url($old_file) }}" 
                                       target="_blank" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        Lihat File
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Public Access -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="is_public"
                                       wire:model="is_public"
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_public" class="ml-2 block text-sm text-gray-900">
                                    Akses Publik (dapat dilihat oleh semua user)
                                </label>
                            </div>
                            @error('is_public') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.arsip.index') }}"
                               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Update Arsip
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>