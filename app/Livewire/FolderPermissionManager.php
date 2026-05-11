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

#[Title('Kelola Akses - SIMAK')]
class FolderPermissionManager extends Component
{
    public string  $search         = '';
    public ?int    $activeFolderId = null;

    // Single assign (per folder)
    public string  $selectedUserId = '';
    public string  $permission     = 'read';
    public ?string $expiresAt      = null;

    // Bulk assign tree
    public string $bulkUserId        = '';
    public array  $bulkFolderIds     = [];      // folder yang dicentang
    public string $bulkPermission    = 'read'; // level untuk folder dicentang
    public array  $expandedFolders   = [];      // ID folder yang sedang terbuka
    public string $bulkFolderSearch  = '';

    public bool $isProcessing = false;

    public function mount(): void
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);
    }

    // ─── Daftar folder untuk kolom kiri (flat) ───
    #[Computed]
    public function foldersFlat()
    {
        return Folder::whereNull('deleted_at')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('kode_lamp')
            ->orderBy('name')
            ->get();
    }

    // ─── Tree untuk bulk assign ───
    #[Computed]
    public function bulkFolderTree(): Collection
    {
        $query = Folder::whereNull('deleted_at');

        if (!empty($this->bulkFolderSearch)) {
            $matchingIds = Folder::whereNull('deleted_at')
                ->where('name', 'like', '%' . $this->bulkFolderSearch . '%')
                ->pluck('id');

            $ancestorIds = collect();
            foreach ($matchingIds as $id) {
                $ancestors = $this->getAncestors($id);
                $ancestorIds = $ancestorIds->merge($ancestors);
            }
            $allIds = $matchingIds->merge($ancestorIds)->unique();
            $folders = Folder::whereNull('deleted_at')->whereIn('id', $allIds)->get();

            // Auto expand semua folder yang relevan
            $this->expandedFolders = array_merge($this->expandedFolders, $allIds->toArray());
        } else {
            $folders = Folder::whereNull('deleted_at')->get();
        }

        return $this->buildTree($folders);
    }

    private function getAncestors(int $folderId): array
    {
        $ancestors = [];
        $folder = Folder::find($folderId);
        while ($folder && $folder->parent_id) {
            $ancestors[] = $folder->parent_id;
            $folder = Folder::find($folder->parent_id);
        }
        return $ancestors;
    }

    private function buildTree(Collection $folders, $parentId = null): Collection
    {
        $tree = collect();
        $children = $folders->where('parent_id', $parentId);
        foreach ($children as $child) {
            $child->children = $this->buildTree($folders, $child->id);
            $tree->push($child);
        }
        return $tree;
    }

    #[Computed]
    public function activeFolder(): ?Folder
    {
        return $this->activeFolderId ? Folder::find($this->activeFolderId) : null;
    }

    #[Computed]
    public function folderPermissions()
    {
        if (!$this->activeFolderId) return collect();
        return FolderPermission::with('user')->where('folder_id', $this->activeFolderId)->get();
    }

    #[Computed]
    public function users()
    {
        return User::where('role', '!=', 'super_admin')->orderBy('prodi')->orderBy('name')->get();
    }

    // ─── Single folder actions ───
    public function selectFolder(int $folderId): void
    {
        $this->activeFolderId = $folderId;
        $this->reset(['selectedUserId', 'permission', 'expiresAt', 'isProcessing']);
        unset($this->activeFolder, $this->folderPermissions);
        $this->dispatch('$refresh');
    }

    public function grantPermission(): void
    {
        $this->isProcessing = true;
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'permission'     => 'required|in:read,write,admin',
            'expiresAt'      => 'nullable|date|after:now',
        ]);
        try {
            FolderPermission::updateOrCreate(
                ['folder_id' => $this->activeFolderId, 'user_id' => $this->selectedUserId],
                ['permission' => $this->permission, 'expires_at' => $this->expiresAt ?: null]
            );
            $this->reset(['selectedUserId', 'permission', 'expiresAt']);
            unset($this->folderPermissions);
            $this->dispatch('notify', type: 'success', message: 'Akses berhasil diberikan.');
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Gagal: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    public function revokePermission(int $permissionId): void
    {
        $this->isProcessing = true;
        try {
            FolderPermission::findOrFail($permissionId)->delete();
            unset($this->folderPermissions);
            $this->dispatch('notify', type: 'success', message: 'Akses dicabut.');
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Gagal: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // ─── Bulk assign tree logic ───
    public function updatedBulkUserId($userId)
    {
        if (!$userId) {
            $this->bulkFolderIds = [];
            return;
        }
        // Ambil semua folder yang sudah memiliki permission untuk user ini
        $this->bulkFolderIds = FolderPermission::where('user_id', $userId)->pluck('folder_id')->toArray();
        $this->expandedFolders = [];
        $this->bulkFolderSearch = '';
        $this->bulkPermission = 'read';
    }

    public function updatedBulkFolderSearch()
    {
        $this->expandedFolders = [];
        unset($this->bulkFolderTree);
    }

    // Expand / collapse
    public function toggleExpand($folderId)
    {
        if (in_array($folderId, $this->expandedFolders)) {
            $this->expandedFolders = array_diff($this->expandedFolders, [$folderId]);
        } else {
            $this->expandedFolders[] = $folderId;
        }
    }

    // Ketika checkbox di-click
    public function toggleFolderCheck($folderId, $checked)
    {
        if ($checked) {
            if (!in_array($folderId, $this->bulkFolderIds)) $this->bulkFolderIds[] = $folderId;
            $this->checkChildrenRecursive($folderId);
        } else {
            $this->bulkFolderIds = array_diff($this->bulkFolderIds, [$folderId]);
            $this->uncheckChildrenRecursive($folderId);
        }
        $this->bulkFolderIds = array_values($this->bulkFolderIds);
    }

    private function checkChildrenRecursive($parentId)
    {
        $children = Folder::where('parent_id', $parentId)->get();
        foreach ($children as $child) {
            if (!in_array($child->id, $this->bulkFolderIds)) $this->bulkFolderIds[] = $child->id;
            $this->checkChildrenRecursive($child->id);
        }
    }

    private function uncheckChildrenRecursive($parentId)
    {
        $children = Folder::where('parent_id', $parentId)->get();
        foreach ($children as $child) {
            $this->bulkFolderIds = array_diff($this->bulkFolderIds, [$child->id]);
            $this->uncheckChildrenRecursive($child->id);
        }
    }

    public function checkAllVisible()
    {
        $this->bulkFolderIds = Folder::whereNull('deleted_at')->pluck('id')->toArray();
    }

    public function uncheckAll()
    {
        $this->bulkFolderIds = [];
    }

    /**
     * Simpan akses massal:
     * - Folder tidak dicentang -> hapus semua permission user untuk folder itu.
     * - Folder dicentang -> jika belum punya akses, buat dengan level bulkPermission.
     *                    -> jika sudah punya akses dengan level lebih rendah, upgrade ke bulkPermission.
     *                    -> jika sudah sama atau lebih tinggi, biarkan.
     */
    public function saveBulkPermissions()
    {
        $this->validate([
            'bulkUserId'     => 'required|exists:users,id',
            'bulkPermission' => 'required|in:read,write,admin',
        ]);
        $this->isProcessing = true;
        try {
            $userId = $this->bulkUserId;
            $checkedFolders = $this->bulkFolderIds;
            $newLevel = $this->bulkPermission;
            $levelOrder = ['read' => 1, 'write' => 2, 'admin' => 3];

            // Hapus akses untuk folder yang tidak dicentang
            FolderPermission::where('user_id', $userId)
                ->whereNotIn('folder_id', $checkedFolders)
                ->delete();

            // Proses folder dicentang
            foreach ($checkedFolders as $folderId) {
                $existing = FolderPermission::where('user_id', $userId)
                            ->where('folder_id', $folderId)
                            ->first();
                if ($existing) {
                    if ($levelOrder[$newLevel] > $levelOrder[$existing->permission]) {
                        $existing->update(['permission' => $newLevel]);
                    }
                } else {
                    FolderPermission::create([
                        'user_id'    => $userId,
                        'folder_id'  => $folderId,
                        'permission' => $newLevel,
                        'expires_at' => null,
                    ]);
                }
            }

            $this->dispatch('notify', type: 'success', message: 'Akses massal disimpan.');
            // Refresh daftar centangan
            $this->updatedBulkUserId($userId);
            unset($this->folderPermissions);
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Gagal: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    public function updatedSearch(): void
    {
        unset($this->foldersFlat);
        $this->activeFolderId = null;
        $this->reset(['selectedUserId', 'permission', 'expiresAt']);
    }

    public function render()
    {
        return view('livewire.folder-permission-manager')->layout('layouts.app', ['title' => 'Kelola Akses - SIMAK']);
    }
}