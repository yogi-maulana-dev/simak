<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $rootFolder->name }} — Arsip SIMAK</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #22873e55; border-radius: 99px; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body
    x-data="{
        preview: { open: false, name: '', url: '', type: '' },
        openPreview(name, url, type) {
            this.preview = { open: true, name, url, type };
        },
        closePreview() {
            this.preview.open = false;
            this.preview.url  = '';
        }
    }"
    class="font-sans antialiased h-full bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex flex-col"
    style="height:100dvh"
>

{{-- ── Top bar ──────────────────────────────────────────────────────────── --}}
<header class="flex-shrink-0 h-12 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 flex items-center gap-3">

    <div class="flex items-center gap-2 flex-shrink-0">
        <div class="w-5 h-5 rounded bg-[#1a6b3e] flex items-center justify-center">
            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
            </svg>
        </div>
        <span class="text-xs font-bold text-[#1a6b3e] dark:text-green-400 uppercase tracking-widest">SIMAK</span>
        <span class="text-gray-300 dark:text-gray-600 text-xs">/</span>
        <span class="text-xs text-gray-500 dark:text-gray-400">Arsip</span>
    </div>

    <div class="w-px h-4 bg-gray-200 dark:bg-gray-700"></div>

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1 text-sm overflow-x-auto no-scrollbar flex-1 min-w-0">
        @foreach ($breadcrumbs as $crumb)
            @if (! $loop->first)
                <svg class="w-3 h-3 text-gray-300 dark:text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            @endif
            @if ($crumb['isRoot'])
                <a href="{{ route('arsip.show', $rootFolder->uuid) }}"
                   @class(['flex-shrink-0 flex items-center gap-1 transition-colors font-medium',
                       'text-gray-900 dark:text-gray-100' => $currentFolder->id === $rootFolder->id,
                       'text-[#1a6b3e] dark:text-green-400 hover:underline' => $currentFolder->id !== $rootFolder->id])>
                    <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                    </svg>
                    {{ $crumb['name'] }}
                </a>
            @else
                <a href="{{ route('arsip.show', $rootFolder->uuid) }}?sub={{ $crumb['id'] }}"
                   @class(['flex-shrink-0 truncate max-w-[160px] transition-colors',
                       'text-gray-900 dark:text-gray-100 font-semibold' => $currentFolder->id === $crumb['id'],
                       'text-[#1a6b3e] dark:text-green-400 hover:underline' => $currentFolder->id !== $crumb['id']])>
                    {{ $crumb['name'] }}
                </a>
            @endif
        @endforeach
    </nav>

    <span class="flex-shrink-0 inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-full">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        Lihat saja
    </span>
</header>

{{-- ── Shell ─────────────────────────────────────────────────────────────── --}}
<div class="flex flex-1 overflow-hidden">

    {{-- SIDEBAR --}}
    <aside class="w-64 flex-shrink-0 flex flex-col bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-3 py-2.5 border-b border-gray-200 dark:border-gray-700">
            <span class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Folder</span>
        </div>

        <nav class="flex-1 overflow-y-auto py-2 px-2">
            <ul class="space-y-0.5">
                <li>
                    <a href="{{ route('arsip.show', $rootFolder->uuid) }}"
                       @class(['flex items-center gap-1.5 px-2 py-1.5 rounded-lg text-sm transition-colors',
                           'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-semibold' => $currentFolder->id === $rootFolder->id,
                           'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-800' => $currentFolder->id !== $rootFolder->id])>
                        <svg class="w-4 h-4 flex-shrink-0 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                        </svg>
                        <span class="truncate font-medium">{{ $rootFolder->name }}</span>
                    </a>

                    @php $rootChildren = $rootFolder->children()->get(); @endphp
                    @if ($rootChildren->isNotEmpty())
                        <ul class="mt-0.5 space-y-0.5">
                            @foreach ($rootChildren as $child)
                                @include('folder.partials.tree-node', [
                                    'folder'    => $child,
                                    'rootUuid'  => $rootFolder->uuid,
                                    'currentId' => $currentFolder->id,
                                    'depth'     => 1,
                                ])
                            @endforeach
                        </ul>
                    @endif
                </li>
            </ul>
        </nav>

        <div class="px-3 py-2.5 border-t border-gray-200 dark:border-gray-700">
            <p class="text-[10px] text-gray-400 dark:text-gray-600">Arsip · SIMAK UML</p>
        </div>
    </aside>

    {{-- MAIN AREA --}}
    <main class="flex-1 overflow-y-auto p-4">

        @if ($subFolders->isEmpty() && $files->isEmpty())
            <div class="h-full flex flex-col items-center justify-center gap-3 text-gray-400 dark:text-gray-600 select-none">
                <svg class="w-16 h-16 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                          d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm">Folder ini kosong.</p>
            </div>
        @else

            {{-- Sub-folder grid --}}
            @if ($subFolders->isNotEmpty())
                <div class="mb-6">
                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Folder</p>
                    <div class="grid gap-3" style="grid-template-columns: repeat(auto-fill, minmax(130px, 1fr))">
                        @foreach ($subFolders as $folder)
                            <a href="{{ route('arsip.show', $rootFolder->uuid) }}?sub={{ $folder->id }}"
                               class="group flex flex-col items-center gap-2 p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-sm transition-all select-none">
                                <svg class="w-12 h-12 text-yellow-400 group-hover:text-yellow-500 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                </svg>
                                <p class="text-xs font-medium text-gray-800 dark:text-gray-200 truncate w-full text-center leading-tight"
                                   title="{{ $folder->name }}">{{ $folder->name }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- File list --}}
            @if ($files->isNotEmpty())
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">
                        File ({{ $files->count() }})
                    </p>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                                    <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                                    <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Tipe</th>
                                    <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Ukuran</th>
                                    <th class="text-right px-4 py-2.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($files as $file)
                                    @php
                                        $ext = $file->extension();
                                        $iconColor = match($ext) {
                                            'pdf'        => 'text-red-500',
                                            'doc','docx' => 'text-blue-600',
                                            'xls','xlsx' => 'text-green-600',
                                            'ppt','pptx' => 'text-orange-500',
                                            default      => 'text-gray-400',
                                        };
                                        $iconLabel = match($ext) {
                                            'pdf'        => 'PDF',
                                            'doc','docx' => 'DOC',
                                            'xls','xlsx' => 'XLS',
                                            'ppt','pptx' => 'PPT',
                                            default      => strtoupper($ext),
                                        };
                                        $canPreview = in_array($ext, ['pdf','doc','docx','xls','xlsx','ppt','pptx']);
                                        $previewUrl = route('arsip.preview', [$rootFolder->uuid, $file->id]);
                                        // Untuk PDF preview di iframe modal, untuk Office buka tab baru
                                        $isOffice   = in_array($ext, ['doc','docx','xls','xlsx','ppt','pptx']);
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2.5">
                                                <div class="relative flex-shrink-0">
                                                    <svg class="w-8 h-8 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                              d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                              clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 text-[7px] font-bold text-white leading-none">
                                                        {{ $iconLabel }}
                                                    </span>
                                                </div>
                                                <span class="font-medium text-gray-900 dark:text-gray-100 truncate max-w-[200px]"
                                                      title="{{ $file->original_name }}">{{ $file->original_name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400 hidden sm:table-cell">{{ strtoupper($ext) }}</td>
                                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $file->formattedSize() }}</td>
                                        <td class="px-4 py-3 text-right">
                                            @if ($canPreview)
                                                @if ($isOffice)
                                                    {{-- Office: buka di tab baru via MS Office Viewer --}}
                                                    <a href="{{ $previewUrl }}" target="_blank"
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        Lihat
                                                    </a>
                                                @else
                                                    {{-- PDF: buka modal iframe --}}
                                                    <button
                                                        @click="openPreview('{{ addslashes($file->original_name) }}', '{{ $previewUrl }}', 'pdf')"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        Lihat PDF
                                                    </button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        @endif
    </main>
</div>

{{-- ══ MODAL PREVIEW PDF ══════════════════════════════════════════════════ --}}
<div
    x-show="preview.open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex flex-col bg-black/80 backdrop-blur-sm"
    style="display:none"
    @keydown.window.escape="closePreview()"
>
    {{-- Modal header --}}
    <div class="flex-shrink-0 flex items-center justify-between px-4 py-3 bg-gray-900 border-b border-gray-700">
        <div class="flex items-center gap-2 min-w-0">
            <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm font-medium text-white truncate" x-text="preview.name"></span>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a :href="preview.url" target="_blank"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-300 hover:text-white bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Buka tab baru
            </a>
            <button @click="closePreview()"
                    class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- iframe PDF --}}
    <div class="flex-1 overflow-hidden">
        <template x-if="preview.open && preview.type === 'pdf'">
            <iframe
                :src="preview.url"
                class="w-full h-full border-0"
                allow="fullscreen"
            ></iframe>
        </template>
    </div>
</div>

</body>
</html>
