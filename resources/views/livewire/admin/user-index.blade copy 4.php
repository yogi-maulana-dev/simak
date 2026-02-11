<div>
 <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen User') }}
            </h2>
<x-primary-button
    onclick="window.location='{{ route('admin.users.create') }}'"
    class="!bg-green-600 hover:!bg-green-700 !text-white">
    Tambah User
</x-primary-button>


        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-6" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
                    <div class="p-4 bg-green-100 text-green-700 rounded-lg">
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
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
                    <div class="p-4 bg-red-100 text-red-700 rounded-lg">
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
                </div>
            @endif

            @if (session('info'))
                <div class="mb-6" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
                    <div class="p-4 bg-blue-100 text-blue-700 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ session('info') }}
                            </div>
                            <button @click="show = false" class="text-blue-700 hover:text-blue-900">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Search and Filter -->
                    <div class="mb-6 flex space-x-4">
                        <div class="flex-1">
                            <input type="text" wire:model.live="search" 
                                   placeholder="Cari user (nama atau email)..."
                                   class="w-full px-4 py-2 rounded-md border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                        </div>
                        <div>
                            <select wire:model.live="perPage" 
                                    class="px-4 py-2 rounded-md border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                                <option value="10">10 per halaman</option>
                                <option value="25">25 per halaman</option>
                                <option value="50">50 per halaman</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Users Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition duration-200"
                                        wire:click="sortBy('name')">
                                        <div class="flex items-center">
                                            Nama
                                            @if($sortField === 'name')
                                                @if($sortDirection === 'asc')
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                @endif
                                            @endif
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition duration-200"
                                        wire:click="sortBy('email')">
                                        <div class="flex items-center">
                                            Email
                                            @if($sortField === 'email')
                                                @if($sortDirection === 'asc')
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                @endif
                                            @endif
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fakultas/Prodi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition duration-200"
                                        wire:click="sortBy('created_at')">
                                        <div class="flex items-center">
                                            Dibuat
                                            @if($sortField === 'created_at')
                                                @if($sortDirection === 'asc')
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                @endif
                                            @endif
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-gray-50 transition duration-200">
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
                                                   class="text-indigo-600 hover:text-indigo-900 hover:underline transition duration-200">
                                                    Edit
                                                </a>
                                            @endif
                                            
                                            @if($user->role->name !== 'superadmin' || auth()->user()->id === $user->id)
                                                <span class="text-gray-400">|</span>
                                                <a href="{{ route('admin.users.reset-password', $user) }}" 
                                                   class="text-yellow-600 hover:text-yellow-900 hover:underline transition duration-200 inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                                    </svg>
                                                    Reset
                                                </a>
                                            @endif

                                            @if($user->role->name !== 'superadmin' && auth()->user()->id !== $user->id)
                                                <span class="text-gray-400">|</span>
                                                <button wire:click="deleteUser('{{ $user->id }}')" 
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')"
                                                        class="text-red-600 hover:text-red-900 hover:underline transition duration-200">
                                                    Hapus
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center py-8">
                                                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 3.75a2.5 2.5 0 01-2.5 2.5h-15a2.5 2.5 0 01-2.5-2.5v-9a2.5 2.5 0 012.5-2.5h15a2.5 2.5 0 012.5 2.5v9z"/>
                                                </svg>
                                                <p class="text-lg">Tidak ada data user.</p>
                                                @if(auth()->user()->can('create-users'))
                                                    <a href="{{ route('admin.users.create') }}" 
                                                       class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-200 flex items-center space-x-2">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                        </svg>
                                                        <span>Tambah User</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>