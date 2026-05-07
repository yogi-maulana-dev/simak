{{--
    share/partials/tree-node.blade.php
    $folder        — Folder saat ini
    $token         — token shared link
    $currentId     — ID folder yang sedang aktif
    $depth         — kedalaman indent (0, 1, 2, ...)
--}}
@php $indent = $depth * 12; @endphp

<li>
    <a
        href="{{ route('share.show', $token) }}?sub={{ $folder->id }}"
        style="padding-left: {{ 8 + $indent }}px"
        @class([
            'flex items-center gap-1.5 py-1.5 pr-2 rounded-lg text-sm transition-colors w-full',
            'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-semibold' => $currentId === $folder->id,
            'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-800 dark:hover:text-gray-200' => $currentId !== $folder->id,
        ])
    >
        <svg class="w-4 h-4 flex-shrink-0 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
        </svg>
        <span class="truncate">{{ $folder->name }}</span>
    </a>

    @php $children = $folder->children()->get(); @endphp
    @if ($children->isNotEmpty())
        <ul class="space-y-0.5 mt-0.5">
            @foreach ($children as $child)
                @include('share.partials.tree-node', [
                    'folder'    => $child,
                    'token'     => $token,
                    'currentId' => $currentId,
                    'depth'     => $depth + 1,
                ])
            @endforeach
        </ul>
    @endif
</li>
