{{-- resources/views/livewire/admin/reset-password.blade.php --}}
<div>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Reset Password User</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Reset password untuk: <span class="font-semibold">{{ $user->name }}</span> ({{ $user->email }})
                        </p>
                    </div>

                    <!-- Informasi User -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Role</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->role->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Terakhir Login</p>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Belum pernah' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Reset Password -->
                    <form wire:submit.prevent="resetPassword">
                        <!-- Pilihan Tipe Reset -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Pilih Metode Reset Password
                            </label>
                            
                            <!-- Default Password Option -->
                            <div class="flex items-start mb-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <div class="flex items-center h-5 mt-1">
                                    <input 
                                        type="radio" 
                                        id="resetTypeDefault" 
                                        wire:model="resetType" 
                                        value="default"
                                        class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                    >
                                </div>
                                <div class="ml-3 flex-1">
                                    <label for="resetTypeDefault" class="font-medium text-gray-700 cursor-pointer flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Reset ke Password Default
                                    </label>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Password: <code class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-mono">{{ $defaultPassword }}</code>
                                    </p>
                                    <p class="text-xs text-yellow-600 mt-1 flex items-start">
                                        <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                        User harus mengganti password saat login pertama kali.
                                    </p>
                                </div>
                            </div>

                            <!-- Custom Password Option -->
                            <div class="flex items-start p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <div class="flex items-center h-5 mt-1">
                                    <input 
                                        type="radio" 
                                        id="resetTypeCustom" 
                                        wire:model="resetType" 
                                        value="custom"
                                        class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                    >
                                </div>
                                <div class="ml-3 flex-1">
                                    <label for="resetTypeCustom" class="font-medium text-gray-700 cursor-pointer flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                        Buat Password Custom
                                    </label>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Tentukan password baru sesuai keinginan Anda
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Custom Password Fields (Muncul saat memilih custom) -->
                        @if($this->showCustomFields)
                            <div class="space-y-6 mb-6 p-6 bg-gray-50 border border-gray-200 rounded-lg">
                                <!-- New Password -->
                                <div>
                                    <label for="customPassword" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                    <div class="mt-1 relative">
                                        <input
                                            wire:model="customPassword"
                                            id="customPassword"
                                            name="customPassword"
                                            type="{{ $showPassword ? 'text' : 'password' }}"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pr-10"
                                            required
                                            placeholder="Masukkan password baru (min. 8 karakter)"
                                            autocomplete="new-password"
                                        />
                                        <button type="button"
                                                wire:click="togglePasswordVisibility('customPassword')"
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                            @if($showPassword)
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            @endif
                                        </button>
                                    </div>
                                    @error('customPassword')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="customPassword_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                    <div class="mt-1 relative">
                                        <input
                                            wire:model="customPassword_confirmation"
                                            id="customPassword_confirmation"
                                            name="customPassword_confirmation"
                                            type="{{ $showPasswordConfirmation ? 'text' : 'password' }}"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pr-10"
                                            required
                                            placeholder="Konfirmasi password baru"
                                            autocomplete="new-password"
                                        />
                                        <button type="button"
                                                wire:click="togglePasswordVisibility('customPassword_confirmation')"
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                            @if($showPasswordConfirmation)
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            @endif
                                        </button>
                                    </div>
                                    @error('customPassword_confirmation')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Password Generator -->
                                <div class="flex items-center justify-between">
                                    <button 
                                        type="button"
                                        wire:click="generateRandomPassword"
                                        class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                        </svg>
                                        Generate Password Acak
                                    </button>
                                    
                                    @if($customPassword)
                                        <span class="text-sm text-gray-500">
                                            Panjang: {{ strlen($customPassword) }} karakter
                                        </span>
                                    @endif
                                </div>

                                <!-- Preview Password jika ada -->
                                @if($customPassword)
                                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Password yang dihasilkan:</p>
                                        <div class="flex items-center justify-between">
                                            <code class="text-lg font-mono text-gray-900 bg-gray-100 px-3 py-2 rounded flex-1 overflow-x-auto">
                                                {{ $customPassword }}
                                            </code>
                                            <button
                                                type="button"
                                                onclick="copyToClipboard('{{ $customPassword }}')"
                                                class="ml-3 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none"
                                            >
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                Salin
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Validation Alert -->
                        @if($this->showCustomFields)
                            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    <span class="text-sm text-yellow-700">Password harus minimal 8 karakter dan kedua input harus sama.</span>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <button type="submit" 
                                wire:loading.attr="disabled"
                                @if($resetType === 'custom' && (!$customPassword || !$customPassword_confirmation)) disabled @endif
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 @if($resetType === 'custom' && (!$customPassword || !$customPassword_confirmation)) opacity-50 cursor-not-allowed @endif">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Password berhasil disalin ke clipboard!');
            }, function(err) {
                alert('Gagal menyalin password: ' + err);
            });
        }
    </script>
</div> 
