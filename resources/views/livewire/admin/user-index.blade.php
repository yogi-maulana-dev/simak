<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen User') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" 
               class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Tambah User
            </a>
        </div>
    </x-slot>

        <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ✅ TAMBAHKAN FLASH MESSAGES DI SINI --}}
            @if (session('success'))
                <div class="mb-6">
                    <x-alert type="success">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-medium">Sukses!</span> {{ session('success') }}
                            </div>
                            <button type="button" @click="$event.target.closest('.alert').remove()" class="text-green-700 hover:text-green-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </x-alert>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6">
                    <x-alert type="error">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-medium">Error!</span> {{ session('error') }}
                            </div>
                            <button type="button" @click="$event.target.closest('.alert').remove()" class="text-red-700 hover:text-red-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </x-alert>
                </div>
            @endif

            @if (session('info'))
                <div class="mb-6">
                    <x-alert type="info">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-medium">Info:</span> {{ session('info') }}
                            </div>
                            <button type="button" @click="$event.target.closest('.alert').remove()" class="text-blue-700 hover:text-blue-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </x-alert>
                </div>
            @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Search and Filter -->
                    <div class="mb-6 flex space-x-4">
                        <div class="flex-1">
                            <input type="text" wire:model.live="search" 
                                   placeholder="Cari user (nama atau email)..."
                                   class="w-full px-4 py-2 rounded-md border-gray-300">
                        </div>
                        <div>
                            <select wire:model.live="perPage" 
                                    class="px-4 py-2 rounded-md border-gray-300">
                                <option value="10">10 per halaman</option>
                                <option value="25">25 per halaman</option>
                                <option value="50">50 per halaman</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Flash Messages -->
                    @if (session()->has('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg" 
                             x-data="{ show: true }" 
                             x-show="show" 
                             x-transition 
                             x-init="setTimeout(() => show = false, 5000)">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ session('success') }}
                                </div>
                                <button @click="show = false" class="text-green-700 hover:text-green-900">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg" 
                             x-data="{ show: true }" 
                             x-show="show" 
                             x-transition 
                             x-init="setTimeout(() => show = false, 5000)">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    {{ session('error') }}
                                </div>
                                <button @click="show = false" class="text-red-700 hover:text-red-900">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Users Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        wire:click="sortBy('name')">
                                        Nama
                                        @if($sortField === 'name')
                                            @if($sortDirection === 'asc')
                                                ↑
                                            @else
                                                ↓
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        wire:click="sortBy('email')">
                                        Email
                                        @if($sortField === 'email')
                                            @if($sortDirection === 'asc')
                                                ↑
                                            @else
                                                ↓
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fakultas/Prodi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        wire:click="sortBy('created_at')">
                                        Dibuat
                                        @if($sortField === 'created_at')
                                            @if($sortDirection === 'asc')
                                                ↑
                                            @else
                                                ↓
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $user->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ $user->email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($user->role->name === 'superadmin') bg-red-100 text-red-800
                                                @elseif($user->role->name === 'admin_univ') bg-blue-100 text-blue-800
                                                @elseif($user->role->name === 'admin_fakultas') bg-green-100 text-green-800
                                                @elseif($user->role->name === 'admin_prodi') bg-purple-100 text-purple-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $user->role->name)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($user->fakultas)
                                                {{ $user->fakultas->nama_fakultas }}
                                                @if($user->prodi)
                                                    <br><small class="text-gray-400">{{ $user->prodi->nama_prodi }}</small>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
    @if($user->role->name !== 'superadmin' || auth()->user()->id === $user->id)
        <a href="{{ route('admin.users.edit', $user->id) }}" 
           class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
            Edit
        </a>
    @endif
    
    <!-- Tombol Reset Password -->
    @if($user->role->name !== 'superadmin' || auth()->user()->id === $user->id)
        <span class="text-gray-400 dark:text-gray-600">|</span>
        <a href="{{ route('admin.users.reset', $user->id) }}" 
           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
            Reset Password
        </a>
    @endif

    @if($user->role->name !== 'superadmin' && auth()->user()->id !== $user->id)
        <span class="text-gray-400 dark:text-gray-600">|</span>
        <button wire:click="deleteUser('{{ $user->id }}')" 
                onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')"
                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
            Hapus
        </button>
    @endif
</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada data user.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>