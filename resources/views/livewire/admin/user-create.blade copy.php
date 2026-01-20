<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah User Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Flash Messages -->
                    @if (session()->has('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if (session()->has('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <!-- Debug Info - TAMPILKAN INI UNTUK TESTING -->
                    <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 rounded-lg">
                        <p><strong>Debug Info:</strong></p>
                        <p>Current Role ID: <strong>{{ $role_id ?: 'KOSONG' }}</strong></p>
                        <p>Selected Role: <strong>{{ $selectedRole ? $selectedRole->name : 'null' }}</strong></p>
                        <p>Fakultas ID: <strong>{{ $fakultas_id ?: 'null' }}</strong></p>
                        <p>Jumlah Fakultas: {{ count($fakultas) }}</p>
                        <p>Jumlah Prodi: {{ count($prodis) }}</p>
                    </div>
                    
                    <!-- Form -->
                    <form wire:submit.prevent="save">
                        <!-- Nama -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nama Lengkap')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" wire:model.live="name" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        
                        <!-- Email -->
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" wire:model.live="email" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" wire:model.live="password" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" wire:model.live="password_confirmation" required />
                        </div>
                        
                        <!-- Role Selection - PAKAI wire:model.live.debounce.300ms -->
                        <div class="mb-4">
                            <x-input-label for="role_id" :value="__('Role')" />
                            <select id="role_id" wire:model.live="role_id" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                        </div>
                        
                        <!-- Conditional Fields - TAMPIL HANYA JIKA ADA ROLE -->
                        @if($role_id && $selectedRole && in_array($selectedRole->name, ['admin_fakultas', 'admin_prodi']))
                            <!-- Fakultas Dropdown (untuk admin_fakultas DAN admin_prodi) -->
                            <div class="mb-4">
                                <x-input-label for="fakultas_id" :value="__('Pilih Fakultas')" />
                                <select id="fakultas_id" wire:model.live="fakultas_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required>
                                    <option value="">-- Pilih Fakultas --</option>
                                    @foreach($fakultas as $f)
                                        <option value="{{ $f->id }}">{{ $f->nama_fakultas }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('fakultas_id')" class="mt-2" />
                            </div>
                            
                            <!-- Prodi Dropdown (hanya untuk admin_prodi DAN jika fakultas sudah dipilih) -->
                            @if($selectedRole->name === 'admin_prodi' && $fakultas_id)
                                <div class="mb-4">
                                    <x-input-label for="prodi_id" :value="__('Pilih Program Studi')" />
                                    <select id="prodi_id" wire:model.live="prodi_id"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        required>
                                        <option value="">-- Pilih Program Studi --</option>
                                        @if(count($prodis) > 0)
                                            @foreach($prodis as $prodi)
                                                <option value="{{ $prodi->id }}">
                                                    {{ $prodi->nama_prodi }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>
                                                -- Tidak ada program studi di fakultas ini --
                                            </option>
                                        @endif
                                    </select>
                                    <x-input-error :messages="$errors->get('prodi_id')" class="mt-2" />
                                    
                                    @if(count($prodis) === 0)
                                        <p class="mt-1 text-sm text-red-600">
                                            Tidak ada program studi di fakultas ini.
                                        </p>
                                    @endif
                                </div>
                            @endif
                            
                            @if($selectedRole->name === 'admin_prodi' && !$fakultas_id)
                                <div class="mb-4 p-3 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg">
                                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                        <strong>Peringatan:</strong> Silakan pilih fakultas terlebih dahulu untuk menampilkan daftar program studi.
                                    </p>
                                </div>
                            @endif
                        @endif
                        
                        <!-- Info Box -->
                        @if($selectedRole)
                            <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    @if(in_array($selectedRole->name, ['admin_univ', 'asesor_fakultas', 'asesor_prodi']))
                                        <strong>Info:</strong> Untuk role <strong>{{ ucfirst(str_replace('_', ' ', $selectedRole->name)) }}</strong>, 
                                        tidak perlu memilih fakultas atau prodi.
                                    @elseif($selectedRole->name === 'admin_fakultas')
                                        <strong>Info:</strong> Pilih fakultas yang akan dikelola oleh Admin Fakultas ini.
                                    @elseif($selectedRole->name === 'admin_prodi')
                                        <strong>Info:</strong> Pilih fakultas terlebih dahulu, kemudian pilih program studi yang akan dikelola oleh Admin Prodi ini.
                                    @endif
                                </p>
                            </div>
                        @endif
                        
                        <!-- Button Actions -->
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.users.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-4">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button>
                                {{ __('Simpan User') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>