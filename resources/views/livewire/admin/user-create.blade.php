<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah User Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Flash Messages -->
                    @if (session()->has('success'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    @if (session()->has('error'))
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Form -->
                    <form wire:submit.prevent="save">
                        
                        <!-- Informasi Akun -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                Informasi Akun
                            </h3>
                            
                            <div class="space-y-4">
                                <!-- Nama -->
                                <div>
                                    <x-input-label for="name" :value="__('Nama Lengkap')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" wire:model.live="name" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                
                                <!-- Email -->
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" wire:model.live="email" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                                
                                <!-- Password -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="password" :value="__('Password')" />
                                        <x-text-input id="password" class="block mt-1 w-full" type="password" wire:model.live="password" required />
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>
                                    
                                    <div>
                                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" wire:model.live="password_confirmation" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Role & Akses -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                Role & Akses
                            </h3>
                            
                            <div class="space-y-4">
                                <!-- Role Selection -->
                                <div>
                                    <x-input-label for="role_id" :value="__('Role')" />
                                    <select id="role_id" wire:model.live="role_id" 
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
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
                                
                                <!-- Info Box berdasarkan Role -->
                                <!-- Info Box berdasarkan Role -->
@if($selectedRole)
    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex">
            <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div class="text-sm text-blue-800">
                @if(in_array($selectedRole->name, ['admin_univ']))
                    <p><strong>{{ ucfirst(str_replace('_', ' ', $selectedRole->name)) }}</strong> memiliki akses ke semua fakultas dan program studi.</p>
                @elseif(in_array($selectedRole->name, ['asesor_fakultas', 'asesor_prodi']))
                    <p><strong>{{ ucfirst(str_replace('_', ' ', $selectedRole->name)) }}</strong> 
                    @if($selectedRole->name === 'asesor_fakultas')
                        memiliki akses untuk menilai di semua program studi dalam fakultas tertentu.
                    @else
                        memiliki akses untuk menilai di program studi tertentu.
                    @endif
                    </p>
                @elseif($selectedRole->name === 'admin_fakultas')
                    <p>Silakan pilih <strong>fakultas</strong> yang akan dikelola oleh admin ini.</p>
                @elseif($selectedRole->name === 'admin_prodi')
                    <p>Silakan pilih <strong>fakultas</strong> terlebih dahulu, kemudian pilih <strong>program studi</strong> yang akan dikelola.</p>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- Conditional Fields -->
@if($role_id && $selectedRole && in_array($selectedRole->name, ['admin_fakultas', 'admin_prodi', 'asesor_fakultas', 'asesor_prodi']))
    
    <!-- Fakultas Dropdown -->
    <div>
        <x-input-label for="fakultas_id" :value="__('Fakultas')" />
        <select id="fakultas_id" wire:model.live="fakultas_id"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            @if(in_array($selectedRole->name, ['admin_fakultas', 'admin_prodi', 'asesor_fakultas', 'asesor_prodi'])) required @endif>
            <option value="">-- Pilih Fakultas --</option>
            @foreach($fakultas as $f)
                <option value="{{ $f->id }}">{{ $f->nama_fakultas }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('fakultas_id')" class="mt-2" />
    </div>
    
    <!-- Prodi Dropdown -->
    @if(in_array($selectedRole->name, ['admin_prodi', 'asesor_prodi']))
        @if($fakultas_id)
            <div>
                <x-input-label for="prodi_id" :value="__('Program Studi')" />
                <select id="prodi_id" wire:model.live="prodi_id"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    required>
                    <option value="">-- Pilih Program Studi --</option>
                    @if(count($prodis) > 0)
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                        @endforeach
                    @else
                        <option value="" disabled>Tidak ada program studi tersedia</option>
                    @endif
                </select>
                <x-input-error :messages="$errors->get('prodi_id')" class="mt-2" />
                
                @if(count($prodis) === 0)
                    <p class="mt-2 text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded px-3 py-2">
                        Tidak ada program studi di fakultas ini.
                    </p>
                @endif
            </div>
        @else
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-amber-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-amber-800">
                        Silakan pilih <strong>fakultas</strong> terlebih dahulu untuk menampilkan daftar program studi.
                    </p>
                </div>
            </div>
        @endif
    @endif
    
@endif

                            </div>
                        </div>
                        
                        <!-- Button Actions -->
                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.users.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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