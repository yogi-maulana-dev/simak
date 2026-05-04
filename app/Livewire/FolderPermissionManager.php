<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Folder;
use App\Models\FolderPermission;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manajemen Akses - SIMAK')]
class FolderPermissionManager extends Component
{
    // ── Panel kiri — daftar root folder ──────────────────────────────────
    public string $folderSearch = '';

    // ── Panel kanan — user yang sedang di-manage ──────────────────────────
    public ?int $selectedUserId = null;

    // ── Folder modal ──────────────────────────────────────────────────────
    public bool   $showFolderModal       = false;
    public string $folderMode            = 'create'; // 'create' | 'edit'
    public ?int   $editingFolderId       = null;
    public string $folderName            = '';

    // ── Delete folder modal ───────────────────────────────────────────────
    public bool   $showDeleteFolderModal = false;
    public ?int   $deleteFolderId        = null;
    public string $deleteFolderName      = '';

    // ── Permission state (checkbox tree) ─────────────────────────────────
    /**
     * Map: folder_id => [
     *   'checked'  => bool,
     *   'level'    => 'read'|'write'|'admin',
     *   'perm_id'  => int|null,
     * ]
     *
     * @var array<int, array{checked: bool, level: string, perm_id: int|null}>
     */
    public array $permMap = [];

    // ── User search ───────────────────────────────────────────────────────
    public string $userSearch = '';

    // ─────────────────────────────────────────────────────────────────────
    // COMPUTED
    // ─────────────────────────────────────────────────────────────────────

    #[Computed]
    public function stats(): array
    {
        return [
            'total_folders' => Folder::whereNull('parent_id')->whereNull('deleted_at')->count(),
            'total_files'   => \App\Models\File::whereNull('deleted_at')->count(),
            'total_users'   => User::count(),
            'total_grants'  => FolderPermission::count(),
        ];
    }

    #[Computed]
    public function rootFolders(): Collection
    {
        return Folder::whereNull('parent_id')
            ->whereNull('deleted_at')
            ->when($this->folderSearch, fn ($q) =>
                $q->where('name', 'like', '%' . $this->folderSearch . '%')
            )
            ->withCount(['children', 'files'])
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function selectedUser(): ?User
    {
        return $this->selectedUserId ? User::find($this->selectedUserId) : null;
    }

    #[Computed]
    public function allUsers(): Collection
    {
        return User::orderBy('name')
            ->when($this->userSearch, fn ($q) =>
                $q->where('name', 'like', '%' . $this->userSearch . '%')
                  ->orWhere('email', 'like', '%' . $this->userSearch . '%')
            )
            ->get();
    }

    /**
     * Semua folder (flat list dengan info parent) untuk tree checkbox.
     * Di-groupBy parent_id supaya blade bisa build tree rekursif.
     */
    #[Computed]
    public function allFolderTree(): Collection
    {
        return Folder::whereNull('deleted_at')
            ->withCount('children')
            ->orderBy('name')
            ->get()
            ->groupBy('parent_id');
    }

    // ─────────────────────────────────────────────────────────────────────
    // SELECT USER
    // ─────────────────────────────────────────────────────────────────────

    public function selectUser(int $userId): void
    {
        $this->selectedUserId = $userId;
        $this->loadPermMap($userId);
        unset($this->selectedUser);
    }

    /**
     * Muat semua permission user ke dalam $permMap.
     */
    private function loadPermMap(int $userId): void
    {
        $perms = FolderPermission::where('user_id', $userId)->get()->keyBy('folder_id');

        $map = [];

        // Isi dari permission yang sudah ada
        foreach ($perms as $folderId => $p) {
            $map[$folderId] = [
                'checked' => true,
                'level'   => $p->permission,
                'perm_id' => $p->id,
            ];
        }

        // Folder yang belum ada permission = unchecked default
        $allFolderIds = Folder::whereNull('deleted_at')->pluck('id');
        foreach ($allFolderIds as $fid) {
            if (! isset($map[$fid])) {
                $map[$fid] = [
                    'checked' => false,
                    'level'   => 'read',
                    'perm_id' => null,
                ];
            }
        }

        $this->permMap = $map;
    }

    // ─────────────────────────────────────────────────────────────────────
    // CHECKBOX TOGGLE — simpan/hapus permission ke DB langsung
    // ─────────────────────────────────────────────────────────────────────

    public function toggleFolderAccess(int $folderId): void
    {
        if (! $this->selectedUserId) return;

        $current    = $this->permMap[$folderId] ?? ['checked' => false, 'level' => 'read', 'perm_id' => null];
        $nowChecked = ! $current['checked'];

        if ($nowChecked) {
            // ── Beri akses ──────────────────────────────────────────────
            $perm = FolderPermission::updateOrCreate(
                [
                    'user_id'   => $this->selectedUserId,
                    'folder_id' => $folderId,
                ],
                [
                    'permission' => $current['level'],
                    'expires_at' => null,
                ]
            );

            $this->permMap[$folderId] = [
                'checked' => true,
                'level'   => $perm->permission,
                'perm_id' => $perm->id,
            ];

            $this->dispatch('notify', type: 'success', message: 'Akses folder diberikan.');
        } else {
            // ── Cabut akses ──────────────────────────────────────────────
            FolderPermission::where('user_id', $this->selectedUserId)
                ->where('folder_id', $folderId)
                ->delete();

            $this->permMap[$folderId] = [
                'checked' => false,
                'level'   => $current['level'], // pertahankan level terakhir (UX nyaman)
                'perm_id' => null,
            ];

            $this->dispatch('notify', type: 'success', message: 'Akses folder dicabut.');
        }

        unset($this->stats);
    }

    // ─────────────────────────────────────────────────────────────────────
    // DROPDOWN LEVEL — update permission level ke DB
    // ─────────────────────────────────────────────────────────────────────

    public function changeLevel(int $folderId, string $level): void
    {
        if (! $this->selectedUserId) return;

        // Validasi nilai level
        if (! in_array($level, ['read', 'write', 'admin'])) return;

        $this->permMap[$folderId]['level'] = $level;

        // Hanya update DB jika folder memang sedang checked
        if ($this->permMap[$folderId]['checked'] ?? false) {
            FolderPermission::where('user_id', $this->selectedUserId)
                ->where('folder_id', $folderId)
                ->update(['permission' => $level]);

            $this->dispatch('notify', type: 'success', message: 'Level akses diperbarui.');
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // FOLDER CRUD (root folders)
    // ─────────────────────────────────────────────────────────────────────

    public function openCreateFolder(): void
    {
        $this->folderMode      = 'create';
        $this->editingFolderId = null;
        $this->folderName      = '';
        $this->resetValidation('folderName');
        $this->showFolderModal = true;
    }

    public function openEditFolder(int $id): void
    {
        $this->folderMode      = 'edit';
        $this->editingFolderId = $id;
        $this->folderName      = Folder::findOrFail($id)->name;
        $this->resetValidation('folderName');
        $this->showFolderModal = true;
    }

    public function saveFolder(): void
    {
        $this->validate(['folderName' => 'required|string|max:255']);

        if ($this->folderMode === 'create') {
            Folder::create([
                'name'       => $this->folderName,
                'parent_id'  => null,
                'path'       => '/' . $this->folderName,
                'created_by' => auth()->id(),
                'is_system'  => false,
            ]);
            $this->dispatch('notify', type: 'success', message: 'Folder berhasil dibuat.');
        } else {
            Folder::findOrFail($this->editingFolderId)->update(['name' => $this->folderName]);
            $this->dispatch('notify', type: 'success', message: 'Nama folder diperbarui.');
        }

        $this->showFolderModal = false;
        unset($this->rootFolders, $this->allFolderTree, $this->stats);

        // Reload permMap agar folder baru muncul di tree
        if ($this->selectedUserId) {
            $this->loadPermMap($this->selectedUserId);
        }
    }

    public function openDeleteFolder(int $id, string $name): void
    {
        $this->deleteFolderId        = $id;
        $this->deleteFolderName      = $name;
        $this->showDeleteFolderModal = true;
    }

    public function deleteFolder(): void
    {
        $folder = Folder::findOrFail($this->deleteFolderId);
        $this->softDeleteFolderRecursive($folder);

        $this->showDeleteFolderModal = false;
        unset($this->rootFolders, $this->allFolderTree, $this->stats);

        if ($this->selectedUserId) {
            $this->loadPermMap($this->selectedUserId);
        }

        $this->dispatch('notify', type: 'success', message: 'Folder berhasil dihapus.');
    }

    private function softDeleteFolderRecursive(Folder $folder): void
    {
        foreach ($folder->children()->withTrashed()->get() as $child) {
            $this->softDeleteFolderRecursive($child);
        }
        foreach ($folder->files()->withTrashed()->get() as $file) {
            \Illuminate\Support\Facades\Storage::disk($file->disk)->delete($file->storagePath());
            $file->delete();
        }
        // Hapus semua permission terkait folder ini dulu
        FolderPermission::where('folder_id', $folder->id)->delete();
        $folder->delete();
    }

    // ─────────────────────────────────────────────────────────────────────
    // RENDER
    // ─────────────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.folder-permission-manager')
            ->layout('layouts.app', ['title' => 'Manajemen Akses - SIMAK']);
    }
}
