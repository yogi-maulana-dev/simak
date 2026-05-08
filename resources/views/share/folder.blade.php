<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $rootFolder->name }} — SIMAK Berbagi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 flex flex-col" style="height:100dvh">

{{-- ── Top bar ──────────────────────────────────────────────────────────── --}}
<header class="flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-2.5 flex items-center gap-3">
    {{-- Brand --}}
    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest flex-shrink-0">SIMAK</span>
    <div class="w-px h-4 bg-gray-200 dark:bg-gray-700"></div>

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1 text-sm overflow-x-auto no-scrollbar flex-1 min-w-0">
        @foreach ($breadcrumbs as $i => $crumb)
            @if ($i > 0)
                <svg class="w-3 h-3 text-gray-300 dark:text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            @endif

            @if ($crumb['isRoot'])
                <a href="{{ route('share.show', $link->token) }}"
                   @class([
                       'flex items-center gap-1 flex-shrink-0 transition-colors',
                       'text-gray-800 dark:text-gray-100 font-semibold' => $currentFolder->id === $rootFolder->id,
                       'text-blue-600 dark:text-blue-400 hover:underline' => $currentFolder->id !== $rootFolder->id,
                   ])>
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                    </svg>
                    <span>{{ $crumb['name'] }}</span>
                </a>
            @else
                <a href="{{ route('share.show', $link->token) }}?sub={{ $crumb['id'] }}"
                   @class([
                       'flex-shrink-0 truncate max-w-[160px] transition-colors',
                       'text-gray-800 dark:text-gray-100 font-semibold' => $currentFolder->id === $crumb['id'],
                       'text-blue-600 dark:text-blue-400 hover:underline' => $currentFolder->id !== $crumb['id'],
                   ])>{{ $crumb['name'] }}</a>
            @endif
        @endforeach
    </nav>

    {{-- Badge izin & info --}}
    <div class="flex items-center gap-2 flex-shrink-0">
        @if ($link->permission === 'download')
            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30 rounded-full">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Lihat &amp; Unduh
            </span>
        @else
            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-full">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Lihat saja
            </span>
        @endif

        @if ($link->expires_at)
            <span class="text-xs text-amber-600 dark:text-amber-400 hidden sm:inline">
                Kedaluwarsa: {{ $link->expires_at->translatedFormat('d M Y') }}
            </span>
        @endif

        <span class="text-xs text-gray-400 dark:text-gray-600 hidden sm:inline">
            Oleh: <strong class="text-gray-600 dark:text-gray-300">{{ $link->creator->name ?? '—' }}</strong>
        </span>
    </div>
</header>

{{-- ── Shell (sidebar + konten) ────────────────────────────────────────── --}}
<div class="flex flex-1 overflow-hidden">

    {{-- ╔════════════════════════╗
         ║  SIDEBAR — FOLDER TREE ║
         ╚════════════════════════╝ --}}
    <aside class="w-56 flex-shrink-0 flex flex-col bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-3 py-2.5 border-b border-gray-200 dark:border-gray-700">
            <span class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Folder</span>
        </div>

        <nav class="flex-1 overflow-y-auto py-2 px-2">
            {{-- Root folder --}}
            <ul class="space-y-0.5">
                <li>
                    <a href="{{ route('share.show', $link->token) }}"
                       @class([
                           'flex items-center gap-1.5 px-2 py-1.5 rounded-lg text-sm transition-colors',
                           'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-semibold' => $currentFolder->id === $rootFolder->id,
                           'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-800 dark:hover:text-gray-200' => $currentFolder->id !== $rootFolder->id,
                       ])>
                        <svg class="w-4 h-4 flex-shrink-0 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                        </svg>
                        <span class="truncate font-medium">{{ $rootFolder->name }}</span>
                    </a>

                    {{-- Children tree --}}
                    @php $rootChildren = $rootFolder->children()->get(); @endphp
                    @if ($rootChildren->isNotEmpty())
                        <ul class="mt-0.5 space-y-0.5">
                            @foreach ($rootChildren as $child)
                                @include('share.partials.tree-node', [
                                    'folder'    => $child,
                                    'token'     => $link->token,
                                    'currentId' => $currentFolder->id,
                                    'depth'     => 1,
                                ])
                            @endforeach
                        </ul>
                    @endif
                </li>
            </ul>
        </nav>

        <div class="px-3 py-2 border-t border-gray-200 dark:border-gray-700">
            <p class="text-[10px] text-gray-400 dark:text-gray-600">SIMAK · Sistem Informasi Akademik</p>
        </div>
    </aside>

    {{-- ╔══════════════════════╗
         ║  MAIN AREA           ║
         ╚══════════════════════╝ --}}
    <main class="flex-1 overflow-y-auto p-5">

        @if ($subFolders->isEmpty() && $files->isEmpty())
            {{-- Kosong --}}
            <div class="h-full flex flex-col items-center justify-center gap-3 text-gray-400 dark:text-gray-600 select-none">
                <svg class="w-16 h-16 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                          d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm">Folder ini kosong.</p>
            </div>
        @else

            {{-- ── Sub-folder grid ──────────────────────────────────────── --}}
            @if ($subFolders->isNotEmpty())
                <div class="mb-6">
                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Folder</p>
                    <div class="grid gap-3" style="grid-template-columns: repeat(auto-fill, minmax(130px, 1fr))">
                        @foreach ($subFolders as $folder)
                            <a href="{{ route('share.show', $link->token) }}?sub={{ $folder->id }}"
                               class="group flex flex-col items-center gap-2 p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-sm transition-all select-none cursor-pointer">
                                <svg class="w-12 h-12 text-yellow-400 group-hover:text-yellow-500 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                </svg>
                                <p class="text-xs font-medium text-gray-800 dark:text-gray-200 truncate w-full text-center leading-tight"
                                   title="{{ $folder->name }}">{{ $folder->name }}</p>
                                <p class="text-[10px] text-gray-400 group-hover:text-blue-500 transition-colors">Klik untuk buka</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── File list ────────────────────────────────────────────── --}}
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
                                        $canPreview = in_array($ext, ['pdf','doc','docx','xls','xlsx','ppt','pptx']);
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
                                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400 hidden sm:table-cell">{{ strtoupper($file->extension()) }}</td>
                                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $file->formattedSize() }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-end gap-2">
                                                {{-- Tombol Lihat — selalu ada untuk file previewable --}}
                                                @if ($canPreview)
                                                    <a href="{{ route('share.view', [$link->token, $file->id]) }}"
                                                       target="_blank"
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-800 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 rounded-lg transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        Lihat
                                                    </a>
                                                @endif

                                                {{-- Tombol Unduh — hanya jika permission download --}}
                                                @if ($link->permission === 'download')
                                                    <a href="{{ route('share.download', [$link->token, $file->id]) }}"
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                        </svg>
                                                        Unduh
                                                    </a>
                                                @endif
                                            </div>
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

</body>
</html>
