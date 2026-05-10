<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared Folder - {{ $folder->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow">
            <!-- Breadcrumb -->
            <div class="border-b px-6 py-3 flex items-center gap-2 text-sm flex-wrap">
                <a href="{{ route('share.show', $token) }}" class="text-blue-600 hover:underline">Root</a>
                @php
                    $ancestors = [];
                    $temp = $folder;
                    while ($temp && $temp->parent_id && $temp->id != $rootFolder->id) {
                        array_unshift($ancestors, $temp);
                        $temp = $temp->parent;
                    }
                @endphp
                @foreach($ancestors as $anc)
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('share.show', ['token' => $token, 'folder' => $anc->id]) }}" class="text-blue-600 hover:underline">{{ $anc->name }}</a>
                @endforeach
                @if($folder->id != $rootFolder->id)
                    <span class="text-gray-400">/</span>
                    <span class="font-semibold">{{ $folder->name }}</span>
                @endif
            </div>

            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">{{ $folder->name }}</h2>

                @if($folder->children->isNotEmpty())
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-3">📁 Sub-folder</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @foreach($folder->children as $sub)
                                <a href="{{ route('share.show', ['token' => $token, 'folder' => $sub->id]) }}" class="block p-3 border rounded-lg hover:bg-gray-50 transition">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                        <span class="truncate">{{ $sub->name }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($folder->files->isNotEmpty())
                    <div>
                        <h3 class="text-lg font-semibold mb-3">📄 File</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($folder->files as $file)
                                <div class="border rounded-lg p-3 flex items-center justify-between">
                                    <div class="flex items-center gap-2 overflow-hidden">
                                        <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                        <span class="truncate text-sm">{{ $file->original_name }}</span>
                                    </div>
                                    <div class="flex gap-2">
                                        @if(in_array($file->extension(), ['pdf','doc','docx','xls','xlsx','ppt','pptx']))
                                            <a href="{{ route('share.view', ['token' => $token, 'fileId' => $file->id]) }}" target="_blank" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Lihat</a>
                                        @endif
                                        @if($permission === 'download')
                                            <a href="{{ route('share.download', ['token' => $token, 'fileId' => $file->id]) }}" class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">Unduh</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($folder->children->isEmpty() && $folder->files->isEmpty())
                    <p class="text-gray-500 text-center py-10">Folder ini kosong.</p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>