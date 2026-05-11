@props(['folder', 'level' => 0])

@php
    $hasChildren = $folder->children && $folder->children->count() > 0;
    $isExpanded = in_array($folder->id, $this->expandedFolders);
    $isChecked = in_array($folder->id, $this->bulkFolderIds);

    // Ambil permission existing untuk user terpilih (untuk badge)
    $existingPerm = null;
    if ($this->bulkUserId) {
        $existingPerm = \App\Models\FolderPermission::where('user_id', $this->bulkUserId)
                        ->where('folder_id', $folder->id)
                        ->first();
    }
    $permLevel = $existingPerm ? $existingPerm->permission : null;
@endphp

<div class="folder-tree-item" style="margin-left: {{ $level * 1.5 }}rem;">
    <div class="flex items-center gap-2 py-1">
        @if($hasChildren)
            <button type="button" wire:click="toggleExpand({{ $folder->id }})" class="focus:outline-none">
                <svg class="w-4 h-4 text-gray-500 transition-transform {{ $isExpanded ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        @else
            <span class="w-4"></span>
        @endif

        <input type="checkbox"
               wire:change="toggleFolderCheck({{ $folder->id }}, $event.target.checked)"
               @if($isChecked) checked @endif
               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">

        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $folder->name }}</span>

        @if($permLevel)
            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold
                @if($permLevel === 'admin') bg-green-100 text-green-700
                @elseif($permLevel === 'write') bg-blue-100 text-blue-700
                @else bg-gray-100 text-gray-600 @endif">
                {{ strtoupper($permLevel) }}
            </span>
        @endif

        <span class="text-xs text-gray-400 truncate">{{ $folder->buildPath() }}</span>
    </div>

    @if($hasChildren && $isExpanded)
        <div class="ml-4">
            @foreach($folder->children as $child)
                @include('livewire.partials.folder-tree-item', ['folder' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>