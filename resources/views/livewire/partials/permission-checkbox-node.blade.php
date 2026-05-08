{{--
    livewire/partials/permission-checkbox-node.blade.php

    Params:
      $folder   — Eloquent Folder (butuh atribut children_count)
      $permMap  — array[ folder_id => ['checked'=>bool, 'level'=>string, 'inherited'=>bool] ]
      $depth    — integer indentasi (mulai 0)

    Komponen ini me-render satu baris folder beserta anak-anaknya
    secara rekursif menggunakan Alpine.js untuk expand/collapse lokal
    (tidak butuh round-trip ke server hanya untuk buka/tutup node).
--}}

@php
    $checked    = $permMap[$folder->id]['checked']   ?? false;
    $level      = $permMap[$folder->id]['level']     ?? 'read';
    $inherited  = $permMap[$folder->id]['inherited'] ?? false;
    $hasChildren = ($folder->children_count ?? 0) > 0;

    // Ambil anak dari allFolderTree yang sudah di-groupBy parent_id di komponen
    // Blade tidak bisa akses $this->allFolderTree langsung di partial,
    // jadi kita query ulang secara minimal (hanya id & name & children_count)
    // supaya tidak memerlukan perubahan pada komponen PHP.
    $children = $hasChildren
        ? \App\Models\Folder::where('parent_id', $folder->id)
            ->whereNull('deleted_at')
            ->withCount('children')
            ->orderBy('name')
            ->get()
        : collect();

    // Tentukan apakah node ini (atau keturunannya) memiliki akses agar
    // badge count bisa ditampilkan di sebelah nama folder parent.
    $grantedDescendants = collect($permMap)
        ->filter(fn($p) => $p['checked'])
        ->count();
@endphp

<li
    wire:key="perm-{{ $folder->id }}"
    x-data="{ open: {{ $checked || $inherited ? 'true' : 'false' }} }"
    class="relative"
>
    {{-- ── Baris utama folder ───────────────────────────────────────── --}}
    <div
        class="group flex items-center gap-1 py-1 pr-2 rounded-lg transition-colors duration-100 hover:bg-gray-50 dark:hover:bg-gray-700/40"
        style="padding-left: {{ 0.5 + $depth * 1.5 }}rem;"
    >

        {{-- Tombol expand/collapse (Alpine lokal, tanpa round-trip server) --}}
        <button
            type="button"
            x-on:click.stop="open = !open"
            class="flex-shrink-0 w-5 h-5 flex items-center justify-center rounded transition-colors
                   {{ $hasChildren
                        ? 'hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer'
                        : 'opacity-0 cursor-default pointer-events-none' }}"
            @if(!$hasChildren) tabindex="-1" @endif
        >
            @if($hasChildren)
                <svg
                    class="w-3 h-3 transition-transform duration-150 text-gray-400 dark:text-gray-500"
                    :class="open ? 'rotate-90' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            @else
                {{-- leaf node — titik kecil sebagai penanda --}}
                <span class="w-1.5 h-1.5 rounded-full bg-gray-300 dark:bg-gray-600 opacity-60"></span>
            @endif
        </button>

        {{-- Checkbox + ikon + nama folder --}}
        <label class="flex items-center gap-2 flex-1 min-w-0 cursor-pointer select-none">

            {{-- Checkbox Livewire --}}
            <input
                type="checkbox"
                wire:click="toggleFolderAccess({{ $folder->id }})"
                @checked($checked)
                class="flex-shrink-0 w-4 h-4 rounded border-gray-300 dark:border-gray-600
                       text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer
                       transition-colors"
            >

            {{-- Ikon folder (terbuka jika checked & ada anak) --}}
            <svg
                class="w-4 h-4 flex-shrink-0 transition-colors
                       {{ $checked ? 'text-blue-500' : 'text-yellow-400' }}"
                fill="currentColor" viewBox="0 0 20 20"
            >
                @if($checked && $hasChildren)
                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v1H2V6zM2 9h16v7a2 2 0 01-2 2H4a2 2 0 01-2-2V9z"/>
                @else
                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                @endif
            </svg>

            {{-- Nama folder --}}
            <span
                class="truncate text-sm transition-colors
                       {{ $checked
                            ? 'text-gray-900 dark:text-gray-100 font-medium'
                            : 'text-gray-600 dark:text-gray-400' }}"
                title="{{ $folder->name }}"
            >
                {{ $folder->name }}
            </span>
        </label>

        {{-- Badge "inherit" --}}
        @if($inherited)
            <span class="flex-shrink-0 px-1.5 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-full text-[10px] font-medium leading-none">
                inherit
            </span>
        @endif

        {{-- Dropdown level akses (hanya tampil jika checked) --}}
        @if($checked)
            <select
                wire:change="changeLevel({{ $folder->id }}, $event.target.value)"
                class="flex-shrink-0 text-xs border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800
                       text-gray-700 dark:text-gray-300 rounded-lg px-1.5 py-1 focus:outline-none
                       focus:ring-2 focus:ring-blue-500 cursor-pointer transition-colors"
                x-on:click.stop
            >
                <option value="read"  {{ $level === 'read'  ? 'selected' : '' }}>Baca</option>
                <option value="write" {{ $level === 'write' ? 'selected' : '' }}>Tulis</option>
                <option value="admin" {{ $level === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        @endif

    </div>

    {{-- ── Garis connector vertikal (indikator kedalaman) ─────────── --}}
    @if($depth > 0 && $hasChildren)
        <span
            class="absolute left-0 top-0 bottom-0 w-px bg-gray-200 dark:bg-gray-700"
            style="left: {{ 0.5 + ($depth - 1) * 1.5 + 0.6 }}rem;"
            aria-hidden="true"
        ></span>
    @endif

    {{-- ── Children (rekursif, dikontrol Alpine x-show) ────────────── --}}
    @if($hasChildren && $children->isNotEmpty())
        <ul
            x-show="open"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-end="opacity-0"
            class="mt-0.5 space-y-0.5 relative"
        >
            {{-- Garis connector vertikal untuk level ini --}}
            <span
                class="absolute top-0 bottom-0 w-px bg-gray-200 dark:bg-gray-700 pointer-events-none"
                style="left: {{ 0.5 + $depth * 1.5 + 0.6 }}rem;"
                aria-hidden="true"
            ></span>

            @foreach ($children as $child)
                @include('livewire.partials.permission-checkbox-node', [
                    'folder'  => $child,
                    'permMap' => $permMap,
                    'depth'   => $depth + 1,
                ])
            @endforeach
        </ul>
    @endif

</li>
