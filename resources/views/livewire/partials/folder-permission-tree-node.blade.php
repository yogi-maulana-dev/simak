{{--
    folder-permission-tree-node.blade.php
    Digunakan di FolderPermissionManager untuk menampilkan tree folder dengan checkbox permission.
    Parameter:
      $folder          : instance Folder
      $depth           : integer (kedalaman)
      $selectedFolderId: id folder yang sedang dipilih (untuk highlight)
      $permissions     : collection dari user's folder permissions (key = folder_id, value = permission object)
      $expandedIds     : array folder id yang di-expand
--}}

@php
    $isExpanded = in_array($folder->id, $expandedIds);
    $hasChildren = ($folder->children_count ?? $folder->children->count()) > 0;
    $indent = $depth * 1.25; // rem
    $currentPerm = $permissions->get($folder->id);
    $permValue = $currentPerm ? $currentPerm->permission : '';
@endphp

<li wire:key="perm-tree-{{ $folder->id }}" class="relative">
    <div
        class="group flex items-center gap-1 py-1 rounded-lg cursor-pointer transition-all duration-100 hover:bg-gray-100 dark:hover:bg-gray-700/60"
        style="padding-left: {{ 0.5 + $indent }}rem; padding-right: 0.5rem;"
    >
        {{-- Tombol expand/collapse --}}
        <button
            wire:click="toggleExpand({{ $folder->id }})"
            @disabled(!$hasChildren)
            class="flex-shrink-0 w-5 h-5 flex items-center justify-center rounded transition-all
                   {{ $hasChildren ? 'hover:bg-gray-200 dark:hover:bg-gray-600' : 'opacity-0 cursor-default' }}
                   focus:outline-none"
        >
            @if($hasChildren)
                <svg class="w-3 h-3 transition-transform duration-150 {{ $isExpanded ? 'rotate-90' : '' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            @endif
        </button>

        {{-- Ikon folder --}}
        <svg class="w-4 h-4 flex-shrink-0 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
            @if($isExpanded && $hasChildren)
                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v1H2V6zM2 9h16v7a2 2 0 01-2 2H4a2 2 0 01-2-2V9z"/>
            @else
                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
            @endif
        </svg>

        {{-- Nama folder --}}
        <span class="flex-1 text-sm truncate text-gray-800 dark:text-gray-200">
            {{ $folder->name }}
        </span>

        {{-- Dropdown permission --}}
        <div class="flex items-center gap-1 opacity-70 group-hover:opacity-100 transition-opacity">
            <select
                wire:change="updatePermission({{ $folder->id }}, $event.target.value)"
                class="text-xs border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 rounded-md px-2 py-0.5 focus:ring-1 focus:ring-blue-500"
            >
                <option value="">Tidak ada akses</option>
                <option value="read" @selected($permValue === 'read')>Baca</option>
                <option value="write" @selected($permValue === 'write')>Tulis</option>
                <option value="admin" @selected($permValue === 'admin')>Admin</option>
            </select>

            @if($currentPerm && $currentPerm->inherited_from)
                <span class="text-[10px] text-gray-400" title="Diwarisi dari folder induk">🔗</span>
            @endif
        </div>
    </div>

    {{-- Children – muat hanya jika expanded --}}
    @if($isExpanded && $hasChildren)
        <ul class="mt-0.5 space-y-0.5">
            @foreach ($folder->children as $child)
                @include('livewire.partials.folder-permission-tree-node', [
                    'folder'          => $child,
                    'depth'           => $depth + 1,
                    'selectedFolderId'=> $selectedFolderId ?? null,
                    'permissions'     => $permissions,
                    'expandedIds'     => $expandedIds,
                ])
            @endforeach
        </ul>
    @endif
</li>