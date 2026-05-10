@php
    $hasChildren = $folder->children->isNotEmpty();
    $isExpanded = in_array($folder->id, $expandedIds);
@endphp

<li wire:key="tree-folder-{{ $folder->id }}">
    <div class="flex items-center justify-between gap-1 group">
        <div class="flex items-center flex-1 min-w-0">
            @if($hasChildren)
                <button wire:click.stop="toggleExpand({{ $folder->id }})" class="p-1.5 flex-shrink-0 text-gray-400 hover:text-gray-600" title="Tampilkan sub-folder">
                    <svg class="w-3 h-3 transition-transform {{ $isExpanded ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            @else
                <span class="w-7 flex-shrink-0"></span>
            @endif
            <button wire:click="openFolder({{ $folder->id }})"
                    @click.stop
                    class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm transition flex-1 min-w-0 {{ $currentId === $folder->id ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                <svg class="w-4 h-4 text-yellow-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                <span class="truncate">{{ $folder->name }}</span>
            </button>
        </div>

        {{-- Tombol aksi untuk superadmin – tidak akan menutup sidebar --}}
        @if(auth()->user() && auth()->user()->isSuperAdmin())
        <div class="flex items-center gap-0.5 flex-shrink-0" wire:key="actions-{{ $folder->id }}" @click.stop>
            <button wire:click="openRenameModal({{ $folder->id }}, 'folder')"
                    @click.stop
                    type="button"
                    class="p-1.5 rounded-md text-gray-500 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 transition" title="Ubah nama">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </button>
            <button wire:click="openQuickFolderModal({{ $folder->id }})"
                    @click.stop
                    type="button"
                    class="p-1.5 rounded-md text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition" title="Buat sub-folder">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </button>
            <button wire:click="openDeleteModal({{ $folder->id }}, 'folder', '{{ addslashes($folder->name) }}')"
                    @click.stop
                    type="button"
                    class="p-1.5 rounded-md text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition" title="Hapus folder">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
        @endif
    </div>

    @if($hasChildren && $isExpanded)
        <ul class="ml-6 mt-0.5 space-y-0.5">
            @foreach($folder->children as $child)
                @include('livewire.partials.folder-tree-node', [
                    'folder' => $child,
                    'depth' => $depth + 1,
                    'currentId' => $currentId,
                    'expandedIds' => $expandedIds
                ])
            @endforeach
        </ul>
    @endif
</li>