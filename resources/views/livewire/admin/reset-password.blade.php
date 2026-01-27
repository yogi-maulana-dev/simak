{{-- resources/views/livewire/user-reset-password.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Reset Password User
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ $user->name }} • {{ $user->email }}
                </p>
            </div>
          <x-secondary-link :href="route('admin.users.index')">
                ← Kembali ke Daftar User
            </x-secondary-link>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6">
                    <x-alert type="success">
                        {{ session('success') }}
                    </x-alert>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6">
                    <x-alert type="error">
                        {{ session('error') }}
                    </x-alert>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <!-- Tabs Navigation -->
                    <div class="border-b border-gray-200 mb-8">
                        <nav class="-mb-px flex space-x-8">
                            <button type="button"
                                    wire:click="$set('activeTab', 'direct')"
                                    @class([
                                        'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                                        'border-indigo-500 text-indigo-600' => $activeTab === 'direct',
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== 'direct'
                                    ])>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                    Reset Langsung
                                </div>
                            </button>

                            <button type="button"
                                    wire:click="$set('activeTab', 'email')"
                                    @class([
                                        'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                                        'border-indigo-500 text-indigo-600' => $activeTab === 'email',
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== 'email'
                                    ])>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Kirim Link Reset
                                </div>
                            </button>
                        </nav>
                    </div>

                    <!-- Direct Reset Tab -->
                    @if($activeTab === 'direct')
                        <div>
                            <!-- Default Password Option -->
                            <div class="mb-8">
                                <div class="bg-green-50 border-l-4 border-green-400 p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-green-800">
                                                    Reset ke Password Default
                                                </p>
                                                <p class="text-xs text-green-600 mt-1">
                                                    Password: <code class="bg-green-100 px-2 py-1 rounded">admin@1234</code>
                                                </p>
                                            </div>
                                        </div>
                                        <x-danger-button 
                                            wire:click="confirmDefaultReset"
                                            wire:loading.attr="disabled">
                                            Reset ke Default
                                        </x-danger-button>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Password Form -->
                            <div class="border-t border-gray-200 pt-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    Atau Buat Password Custom
                                </h3>

                                <form wire:submit.prevent="resetWithCustomPassword">
                                    <div class="space-y-6">
                                        <!-- New Password -->
                                        <div>
                                            <x-input-label for="password" value="Password Baru" />
                                            <div class="mt-1 relative">
                                                <x-text-input
                                                    wire:model="password"
                                                    id="password"
                                                    type="{{ $showPassword ? 'text' : 'password' }}"
                                                    class="block w-full pr-10"
                                                    required
                                                    placeholder="Masukkan password baru"
                                                />
                                                <button type="button"
                                                        wire:click="$toggle('showPassword')"
                                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                    @if($showPassword)
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                        </svg>
                                                    @else
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            </div>
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <!-- Confirm Password -->
                                        <div>
                                            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                                            <x-text-input
                                                wire:model="password_confirmation"
                                                id="password_confirmation"
                                                type="password"
                                                class="block w-full mt-1"
                                                required
                                                placeholder="Konfirmasi password baru"
                                            />
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>

                                        <!-- Password Generator -->
                                        <div class="flex items-center justify-between">
                                            <x-secondary-button 
                                                type="button"
                                                wire:click="generateRandomPassword">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                </svg>
                                                Generate Password Acak
                                            </x-secondary-button>
                                        </div>

                                        <!-- Form Actions -->
                                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                                            <x-secondary-link :href="route('admin.users.index')">
                                                Batal
                                            </x-secondary-link>
                                            <x-primary-button type="submit">
                                                Reset Password
                                            </x-primary-button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Email Reset Tab -->
                    @if($activeTab === 'email')
                        <div>
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                                <div class="flex">
                                    <svg class="h-5 w-5 text-blue-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm text-blue-800">
                                            Link reset akan dikirim ke email: <strong>{{ $user->email }}</strong>
                                        </p>
                                        <p class="text-xs text-blue-600 mt-1">
                                            Link akan kadaluarsa dalam 24 jam.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end space-x-3">
                                <x-secondary-link :href="route('admin.users.index')">
                                    Batal
                                </x-secondary-link>
                                <x-primary-button 
                                    wire:click="sendResetLink"
                                    wire:loading.attr="disabled">
                                    Kirim Reset Link
                                </x-primary-button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- User Information Card -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Informasi User
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nama Lengkap</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Email</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Role</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->role->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Fakultas</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->fakultas->nama_fakultas ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Program Studi</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->prodi->nama_prodi ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Terakhir Login</p>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Belum pernah' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal for Default Password Reset -->
    <x-confirmation-modal wire:model="confirmingDefaultReset">
        <x-slot name="title">
            Reset Password ke Default?
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <p>Anda yakin ingin reset password untuk <strong>{{ $user->name }}</strong> ke password default?</p>
                
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-yellow-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <div>
                            <p class="text-sm text-yellow-800">
                                <strong>Perhatian:</strong> Password akan direset ke <code class="font-mono">admin@1234</code>.
                                User harus mengganti password saat login pertama kali.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingDefaultReset')">
                Batal
            </x-secondary-button>

            <x-danger-button 
                class="ml-3"
                wire:click="resetWithDefaultPassword"
                wire:loading.attr="disabled">
                Ya, Reset ke Default
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Success Modal -->
    <x-dialog-modal wire:model="showSuccessModal" maxWidth="lg">
        <x-slot name="title">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Password Berhasil Direset
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6">
                <p>Password untuk <strong>{{ $user->name }}</strong> telah berhasil direset.</p>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-700 mb-4">Password Baru:</p>
                    <div class="flex items-center justify-between">
                        <code class="text-2xl font-mono text-gray-900 bg-gray-100 px-4 py-3 rounded-lg flex-1 mr-4 text-center">
                            {{ $newPassword }}
                        </code>
                        <button
                            type="button"
                            x-data="{
                                copied: false,
                                copy(text) {
                                    navigator.clipboard.writeText(text);
                                    this.copied = true;
                                    setTimeout(() => this.copied = false, 2000);
                                }
                            }"
                            x-on:click="copy('{{ $newPassword }}')"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        >
                            <template x-if="!copied">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </template>
                            <template x-if="copied">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </template>
                            <span x-text="copied ? 'Tersalin!' : 'Salin Password'"></span>
                        </button>
                    </div>
                    <p class="mt-4 text-sm text-gray-500">
                        @if($newPassword === 'admin@1234')
                            ⚠️ <strong>Password default</strong> - User harus mengganti password saat login pertama kali.
                        @else
                            🔒 Password ini hanya ditampilkan sekali. Pastikan untuk menyimpannya dengan aman.
                        @endif
                    </p>
                </div>

                @if($newPassword === 'admin@1234')
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-yellow-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <p class="text-sm text-yellow-800">
                                <strong>Peringatan Keamanan:</strong> Pastikan user segera mengganti password ini setelah login pertama kali.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeSuccessModal">
                Tutup
            </x-secondary-button>

            <x-primary-button 
                class="ml-3"
                wire:click="sendPasswordViaEmail"
                wire:loading.attr="disabled">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Kirim via Email
            </x-primary-button>
        </x-slot>
    </x-dialog-modal>

</x-app-layout>