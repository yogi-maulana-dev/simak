<div>
    {{-- Toast notification (sederhana) --}}
    {{-- Toast notification (sama seperti sebelumnya) --}}
    <div x-data="toastHandler()" x-init="initToastListener()" class="fixed top-5 right-5 z-[9999] flex flex-col gap-2 pointer-events-none" style="min-width: 280px; max-width: 380px">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 translate-x-4"
                :class="{
                    'bg-green-50 border-green-200 text-green-800': toast.type === 'success',
                    'bg-red-50 border-red-200 text-red-800': toast.type === 'error'
                }"
                class="flex items-center gap-3 px-4 py-3 rounded-xl border shadow-lg text-sm font-medium pointer-events-auto">
                <span x-text="toast.message"></span>
                <button @click="removeToast(toast.id)" class="opacity-60 hover:opacity-100">✕</button>
            </div>
        </template>
    </div>

   <div class="relative flex h-[calc(100vh-4rem)] overflow-hidden bg-gray-50 dark:bg-gray-900">
        {{-- Backdrop untuk mobile (hanya muncul saat sidebar terbuka) --}}
        <div id="explorer-backdrop" class="fixed inset-0 bg-black/50 transition-opacity duration-200" style="display:none; z-index: 30;" onclick="closeSidebar()"></div>

        {{-- SIDEBAR --}}
       {{-- ===================== SIDEBAR ===================== --}}
        <aside id="explorer-sidebar" class="fixed lg:relative lg:flex lg:w-72 flex-shrink-0 flex-col bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-xl z-40 h-full transition-transform duration-300 transform -translate-x-full lg:translate-x-0 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <span class="font-semibold">Folder</span>
                <div class="flex gap-1">
                    @if(auth()->user() && auth()->user()->isSuperAdmin())
                        <button wire:click="openQuickFolderModal(null)" class="p-1.5 rounded-md hover:bg-gray-100" title="Buat folder root">➕</button>
                    @endif
                    <button id="explorer-close-btn" class="p-1.5 rounded-md hover:bg-gray-100 lg:hidden">✕</button>
                </div>
            </div>
            <nav class="flex-1 overflow-y-auto py-2 px-2">
                <button wire:click="goHome" class="w-full text-left px-3 py-2 rounded-md mb-2 {{ !$currentFolderId ? 'bg-blue-100 font-semibold' : 'hover:bg-gray-100' }}">🏠 Home</button>
                <div class="text-xs text-gray-400 px-3 mb-1">Folder</div>
                @if($this->rootFolders->isEmpty())
                    <p class="text-center text-gray-400 py-4 text-sm">Belum ada folder</p>
                @else
                    <ul>
                        @foreach($this->rootFolders as $rootFolder)
                            @include('livewire.partials.folder-tree-node', ['folder'=>$rootFolder, 'depth'=>0, 'currentId'=>$currentFolderId, 'expandedIds'=>$expandedIds])
                        @endforeach
                    </ul>
                @endif
            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">
            {{-- Toolbar --}}
            <div class="bg-white border-b px-4 py-2 flex items-center gap-2 flex-wrap">
                <button id="explorer-toggle-btn" class="p-2 rounded-md hover:bg-gray-100 lg:hidden">☰</button>
                <div class="flex gap-1 bg-gray-100 p-1 rounded">
                    <button wire:click="goBack" class="p-1.5 rounded {{ $this->canGoBack ? '' : 'opacity-50' }}">◀</button>
                    <button wire:click="goForward" class="p-1.5 rounded {{ $this->canGoForward ? '' : 'opacity-50' }}">▶</button>
                    <button wire:click="refresh" class="p-1.5 rounded">⟳</button>
                </div>
                <div class="flex-1 flex gap-1 text-sm overflow-x-auto">
                    <button wire:click="goHome" class="text-blue-600">Home</button>
                    @foreach($this->breadcrumbs as $crumb)
                        <span>/</span>
                        <button wire:click="openFolder({{ $crumb['id'] }})" class="{{ $currentFolderId === $crumb['id'] ? 'font-semibold' : 'hover:text-blue-600' }}">{{ $crumb['name'] }}</button>
                    @endforeach
                </div>
                @if($currentFolderId)
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari..." class="pl-8 pr-2 py-1 border rounded w-36 sm:w-48">
                </div>
                @endif
            </div>

            {{-- ACTION BAR (yang penting) --}}
            @if ($currentFolderId)
            <div class="bg-white border-b px-4 py-2 flex flex-wrap items-center gap-3">
                <button wire:click="openNewFolderModal" class="flex items-center gap-2 px-3 py-1.5 bg-blue-600 text-white rounded-md">➕ Sub-folder</button>
                <button wire:click="openUploadModal" class="flex items-center gap-2 px-3 py-1.5 bg-emerald-600 text-white rounded-md">📤 Upload</button>
                @if($this->selectedCount > 0)
                    <span class="text-sm">{{ $this->selectedCount }} dipilih</span>
                    <button wire:click="clearSelection" class="text-red-500 text-sm">Batal</button>
                @endif
                <div class="ml-auto flex gap-1">
                    <button wire:click="$set('viewMode','grid')" class="p-1.5 rounded {{ $viewMode === 'grid' ? 'bg-gray-200' : '' }}">⊞</button>
                    <button wire:click="$set('viewMode','list')" class="p-1.5 rounded {{ $viewMode === 'list' ? 'bg-gray-200' : '' }}">≡</button>
                </div>
            </div>
            @endif

            {{-- CONTENT (grid/list) disederhanakan --}}
            <div class="flex-1 overflow-y-auto p-4">
                @if(!$currentFolderId)
                    @if($this->rootFolders->isEmpty() && auth()->user()->isSuperAdmin())
                        <div class="flex flex-col items-center justify-center h-full text-center py-20">
                            <p class="text-gray-500 mb-4">Belum ada folder</p>
                            <button wire:click="openQuickFolderModal(null)" class="bg-blue-600 text-white px-4 py-2 rounded-md">Buat Folder Pertama</button>
                        </div>
                    @else
                        <div class="text-center text-gray-400 py-20">Pilih folder dari sidebar</div>
                    @endif
                @elseif($viewMode === 'grid')
                    {{-- Tampilan grid sederhana --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($this->subFolders as $folder)
                            <div wire:click="openFolder({{ $folder->id }})" class="border rounded-lg p-3 text-center cursor-pointer hover:shadow">
                                <div class="text-4xl">📁</div>
                                <div class="truncate">{{ $folder->name }}</div>
                                @if(auth()->user()->isSuperAdmin())
                                    <div class="flex justify-center gap-2 mt-1" @click.stop>
                                        <button wire:click="openRenameModal({{ $folder->id }}, 'folder')" class="text-xs text-yellow-600">✏️</button>
                                        <button wire:click="openDeleteModal({{ $folder->id }}, 'folder', '{{ $folder->name }}')" class="text-xs text-red-600">🗑️</button>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        @foreach($this->currentFiles as $file)
                            <div class="border rounded-lg p-3 text-center">
                                <div class="text-4xl">📄</div>
                                <div class="truncate">{{ $file->original_name }}</div>
                                <div class="text-xs text-gray-500">{{ $file->formattedSize() }}</div>
                                @if(auth()->user()->isSuperAdmin())
                                    <div class="flex justify-center gap-2 mt-1" @click.stop>
                                        <button wire:click="openRenameModal({{ $file->id }}, 'file')" class="text-xs text-yellow-600">✏️</button>
                                        <button wire:click="openDeleteModal({{ $file->id }}, 'file', '{{ $file->original_name }}')" class="text-xs text-red-600">🗑️</button>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- list view sederhana --}}
                    <table class="w-full">
                        @foreach($this->subFolders as $folder)
                            <tr wire:click="openFolder({{ $folder->id }})" class="cursor-pointer hover:bg-gray-100">
                                <td class="py-1">📁 {{ $folder->name }}</td>
                                <td class="text-right">
                                    @if(auth()->user()->isSuperAdmin())
                                        <button wire:click.stop="openRenameModal({{ $folder->id }}, 'folder')" class="text-yellow-600 mr-2">✏️</button>
                                        <button wire:click.stop="openDeleteModal({{ $folder->id }}, 'folder', '{{ $folder->name }}')" class="text-red-600">🗑️</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @foreach($this->currentFiles as $file)
                            <tr>
                                <td class="py-1">📄 {{ $file->original_name }} <span class="text-xs text-gray-500">({{ $file->formattedSize() }})</span></td>
                                <td class="text-right">
                                    <button wire:click="downloadFile({{ $file->id }})" class="text-blue-600 mr-2">⬇️</button>
                                    @if(auth()->user()->isSuperAdmin())
                                        <button wire:click.stop="openRenameModal({{ $file->id }}, 'file')" class="text-yellow-600 mr-2">✏️</button>
                                        <button wire:click.stop="openDeleteModal({{ $file->id }}, 'file', '{{ $file->original_name }}')" class="text-red-600">🗑️</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL-MODAL (ringkas) --}}
    @if ($showNewFolderModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Buat Folder Baru</h3>
            <input type="text" wire:model="newFolderName" placeholder="Nama folder" class="w-full border rounded px-3 py-2 mb-4">
            @error('newFolderName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            <div class="flex justify-end gap-2">
                <button wire:click="$set('showNewFolderModal', false)" class="px-4 py-2 border rounded">Batal</button>
                <button wire:click="createFolder" class="px-4 py-2 bg-blue-600 text-white rounded">Buat</button>
            </div>
        </div>
    </div>
    @endif

    @if ($showUploadModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Unggah File</h3>
            <input type="file" wire:model="uploads" multiple class="mb-4">
            @error('uploads.*') <span class="text-red-500">{{ $message }}</span> @enderror
            <div class="flex justify-end gap-2">
                <button wire:click="$set('showUploadModal', false)" class="px-4 py-2 border rounded">Batal</button>
                <button wire:click="uploadFiles" class="px-4 py-2 bg-emerald-600 text-white rounded">Unggah</button>
            </div>
        </div>
    </div>
    @endif

    @if ($showRenameModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Ubah Nama</h3>
            <input type="text" wire:model="renameName" class="w-full border rounded px-3 py-2 mb-4">
            @error('renameName') <span class="text-red-500">{{ $message }}</span> @enderror
            <div class="flex justify-end gap-2">
                <button wire:click="$set('showRenameModal', false)" class="px-4 py-2 border rounded">Batal</button>
                <button wire:click="renameItem" class="px-4 py-2 bg-yellow-600 text-white rounded">Simpan</button>
            </div>
        </div>
    </div>
    @endif

    @if ($showDeleteModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Hapus {{ $deleteItemType === 'folder' ? 'Folder' : 'File' }}?</h3>
            <p class="mb-4">"{{ $deleteItemName }}" akan dihapus permanen.</p>
            <div class="flex justify-end gap-2">
                <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 border rounded">Batal</button>
                <button wire:click="deleteItem" class="px-4 py-2 bg-red-600 text-white rounded">Hapus</button>
            </div>
        </div>
    </div>
    @endif

    @if ($showQuickFolderModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Buat Folder Baru</h3>
            <input type="text" wire:model="quickFolderName" placeholder="Nama folder" class="w-full border rounded px-3 py-2 mb-4">
            @error('quickFolderName') <span class="text-red-500">{{ $message }}</span> @enderror
            <div class="flex justify-end gap-2">
                <button wire:click="$set('showQuickFolderModal', false)" class="px-4 py-2 border rounded">Batal</button>
                <button wire:click="createQuickFolder" class="px-4 py-2 bg-blue-600 text-white rounded">Buat</button>
            </div>
        </div>
    </div>
    @endif
 <style>
        /* Sidebar mobile: tidak otomatis tertutup saat klik di dalam sidebar */
        #explorer-sidebar {
            transition: transform 0.3s ease;
        }
        @media (max-width: 1023px) {
            #explorer-sidebar {
                transform: translateX(-100%);
            }
            #explorer-sidebar.is-open {
                transform: translateX(0) !important;
            }
            #explorer-backdrop.is-open {
                display: block !important;
            }
            /* Pastikan backdrop tidak menutup saat klik di dalam sidebar */
            #explorer-backdrop {
                cursor: pointer;
            }
            /* Ukuran tombol aksi di sidebar lebih besar untuk mobile */
            #explorer-sidebar .p-1\.5 {
                min-width: 40px;
                min-height: 40px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            /* Hindari scroll body saat sidebar terbuka */
            body.sidebar-open {
                overflow: hidden;
            }
        }
        /* Scrollbar hide */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <script>
        // Fungsi untuk membuka/tutup sidebar (hanya via tombol burger & close)
        function toggleSidebar() {
            const sidebar = document.getElementById('explorer-sidebar');
            const backdrop = document.getElementById('explorer-backdrop');
            if (!sidebar) return;
            const isOpen = sidebar.classList.contains('is-open');
            if (isOpen) {
                sidebar.classList.remove('is-open');
                backdrop?.classList.remove('is-open');
                document.body.classList.remove('sidebar-open');
            } else {
                sidebar.classList.add('is-open');
                backdrop?.classList.add('is-open');
                document.body.classList.add('sidebar-open');
            }
        }
        function closeSidebar() {
            const sidebar = document.getElementById('explorer-sidebar');
            const backdrop = document.getElementById('explorer-backdrop');
            if (sidebar) sidebar.classList.remove('is-open');
            if (backdrop) backdrop.classList.remove('is-open');
            document.body.classList.remove('sidebar-open');
        }
        // Event listener untuk tombol burger dan close
        document.getElementById('explorer-toggle-btn')?.addEventListener('click', toggleSidebar);
        document.getElementById('explorer-close-btn')?.addEventListener('click', closeSidebar);
        // Saat layar di-resize lebih dari 1023px, pastikan sidebar visible dan tidak terkunci
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                const sidebar = document.getElementById('explorer-sidebar');
                if (sidebar) sidebar.classList.remove('is-open');
                document.body.classList.remove('sidebar-open');
            }
        });
        // Toast handler (sederhana)
        function toastHandler() {
            return {
                toasts: [],
                initToastListener() {
                    window.addEventListener('notify', (e) => {
                        this.addToast(e.detail.type, e.detail.message);
                    });
                },
                addToast(type, message) {
                    const id = Date.now();
                    this.toasts.push({ id, type, message });
                    setTimeout(() => this.removeToast(id), 4000);
                },
                removeToast(id) {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }
            }
        }
    </script>
</div>