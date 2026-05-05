{{--
    folder-tree-node.blade.php
    Dipanggil rekursif untuk render sidebar tree.

    Params:
      $folder        — instance Folder (harus memiliki atribut children_count)
      $depth         — kedalaman indentasi
      $currentId     — ID folder yang sedang aktif
      $expandedIds   — array ID folder yang sedang di-expand
--}}

@php
    $isActive    = ($currentId == $folder->id);
    $isExpanded  = in_array($folder->id, $expandedIds);
    $hasChildren = ($folder->children_count ?? 0) > 0;
    $indent      = $depth * 1.25; // dalam rem
    $user        = auth()->user();
    $canShare    = $user && (
        $folder->created_by === $user->id || $user->isSuperAdmin()
    );
@endphp

<li wire:key="tree-folder-{{ $folder->id }}" class="relative">
    <div
        wire:key="tree-row-{{ $folder->id }}"
        @class([
            'group flex items-center gap-1 py-1 rounded-lg cursor-pointer select-none transition-all duration-100',
            'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium' => $isActive,
            'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/60' => ! $isActive,
        ])
        style="padding-left: {{ 0.5 + $indent }}rem; padding-right: 0.5rem;"
    >
        {{-- Tombol expand/collapse (hanya jika punya anak) --}}
        <button
            wire:click.stop="toggleExpand({{ $folder->id }})"
            @disabled(!$hasChildren)
            class="flex-shrink-0 w-5 h-5 flex items-center justify-center rounded transition-all duration-150
                   {{ $hasChildren ? 'hover:bg-gray-200 dark:hover:bg-gray-600' : 'opacity-0 cursor-default' }}
                   focus:outline-none"
        >
            @if($hasChildren)
                <svg
                    class="w-3 h-3 transition-transform duration-150 {{ $isExpanded ? 'rotate-90' : '' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            @endif
        </button>

        {{-- Konten folder (ikon + nama) --}}
        <button
            wire:click="openFolder({{ $folder->id }})"
            class="flex-1 flex items-center gap-1.5 min-w-0 text-left"
            title="{{ $folder->name }}"
        >
            <svg class="w-4 h-4 flex-shrink-0 {{ $isActive ? 'text-blue-500' : 'text-yellow-500' }}"
                 fill="currentColor" viewBox="0 0 20 20"
            >
                @if($isExpanded && $hasChildren)
                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v1H2V6zM2 9h16v7a2 2 0 01-2 2H4a2 2 0 01-2-2V9z"/>
                @else
                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                @endif
            </svg>
            <span class="truncate text-sm">{{ $folder->name }}</span>
        </button>

        {{-- Tombol Bagikan — hanya muncul jika user boleh share & saat hover --}}
        @if($canShare)
            <button
                wire:click.stop="openShareModal({{ $folder->id }}, 'folder', '{{ addslashes($folder->name) }}')"
                x-on:click.stop
                title="Bagikan folder ini"
                class="flex-shrink-0 opacity-0 group-hover:opacity-100 p-1 rounded-md transition-all
                       text-gray-400 hover:text-indigo-600 hover:bg-indigo-50
                       dark:hover:text-indigo-400 dark:hover:bg-indigo-900/30
                       focus:outline-none focus:opacity-100"
            >
                {{-- Ikon Share (seperti Google Drive) --}}
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8.684 13.342C8.886 12.938 9 12.482 9 12s-.114-.938-.316-1.342
                             m0 2.684a3 3 0 110-2.684
                             m0 2.684l6.632 3.316
                             m-6.632-6l6.632-3.316
                             m0 0a3 3 0 105.367-2.684
                             3 3 0 00-5.367 2.684z"/>
                </svg>
            </button>
        @endif
    </div>

    {{-- Children – dimuat hanya jika expanded --}}
    @if($isExpanded && $hasChildren)
        <div class="relative mt-0.5">
            {{-- Indikator loading --}}
            <div wire:loading.delay wire:target="toggleExpand({{ $folder->id }})"
                 class="absolute inset-0 bg-white/50 dark:bg-gray-800/50 flex items-center justify-center rounded-md z-10">
                <svg class="w-4 h-4 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>
            </div>

            <ul class="space-y-0.5">
                @php
                    $children = $this->sidebarChildren($folder->id);
                @endphp
                @foreach ($children as $child)
                    @include('livewire.partials.folder-tree-node', [
                        'folder'      => $child,
                        'depth'       => $depth + 1,
                        'currentId'   => $currentId,
                        'expandedIds' => $expandedIds,
                    ])
                @endforeach
            </ul>
        </div>
    @endif
</li>