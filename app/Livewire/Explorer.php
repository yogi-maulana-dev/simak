<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Explorer - SIMAK')]
class Explorer extends Component
{
    use WithFileUploads;

    // ── State navigasi ────────────────────────────────────────────────────

    public ?int $currentFolderId = null;

    /** @var int[] */
    public array $folderHistory = [];
    public int   $historyIndex  = -1;

    // ── State UI ──────────────────────────────────────────────────────────

    public string $viewMode           = 'grid'; // 'grid' | 'list'
    public bool   $showNewFolderModal = false;
    public bool   $showUploadModal    = false;
    public bool   $showRenameModal    = false;
    public bool   $showDeleteModal    = false;
    public string $search             = '';

    // ── Sidebar: expanded folder ids ──────────────────────────────────────

    /** @var int[] folder ids yang sedang di-expand di sidebar */
    public array $expandedFolderIds = [];

    // ── Form: Buat Folder ─────────────────────────────────────────────────

    #[Validate('required|string|max:255', message: [
        'required' => 'Nama folder wajib diisi.',
        'max'      => 'Nama folder maksimal 255 karakter.',
    ])]
    public string $newFolderName = '';

    // ── Form: Rename ──────────────────────────────────────────────────────

    #[Validate('required|string|max:255', message: ['required' => 'Nama wajib diisi.'])]
    public string $renameName     = '';
    public ?int   $renameItemId   = null;
    public string $renameItemType = ''; // 'folder' | 'file'

    // ── Form: Delete ──────────────────────────────────────────────────────

    public ?int   $deleteItemId   = null;
    public string $deleteItemType = '';
    public string $deleteItemName = '';

    // ── Upload ────────────────────────────────────────────────────────────

    #[Validate([
        'uploads.*' => ['file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx'],
    ], message: [
        'uploads.*.max'   => 'Ukuran file maksimal 10 MB.',
        'uploads.*.mimes' => 'Format file tidak didukung. Gunakan: PDF, Word, Excel, atau PowerPoint.',
    ])]
    public array $uploads = [];

    // ── Selection ─────────────────────────────────────────────────────────

    /** @var array<string, bool>  key = "{type}_{id}" */
    public array $selected = [];

    // ─────────────────────────────────────────────────────────────────────
    // COMPUTED PROPERTIES
    // ─────────────────────────────────────────────────────────────────────

    #[Computed]
    public function currentFolder(): ?Folder
    {
        return $this->currentFolderId
            ? Folder::find($this->currentFolderId)
            : null;
    }

    #[Computed]
    public function rootFolders(): Collection
    {
        return $this->accessibleFolders(null);
    }

    #[Computed]
    public function subFolders(): Collection
    {
        if (! $this->currentFolderId) {
            return collect();
        }

        $folders = $this->accessibleFolders($this->currentFolderId);

        if ($this->search) {
            $folders = $folders->filter(
                fn (Folder $f) => str_contains(strtolower($f->name), strtolower($this->search))
            );
        }

        return $folders;
    }

    #[Computed]
    public function currentFiles(): Collection
    {
        if (! $this->currentFolderId) {
            return collect();
        }

        $query = File::where('folder_id', $this->currentFolderId)
            ->whereNull('deleted_at')
            ->with('uploader');

        if ($this->search) {
            $query->where('original_name', 'like', '%' . $this->search . '%');
        }

        return $query->orderBy('original_name')->get();
    }

    #[Computed]
    public function breadcrumbs(): array
    {
        if (! $this->currentFolderId) {
            return [];
        }

        $crumbs = [];
        $folder = Folder::find($this->currentFolderId);

        while ($folder) {
            array_unshift($crumbs, [
                'id'   => $folder->id,
                'name' => $folder->name,
            ]);
            $folder = $folder->parent_id
                ? Folder::find($folder->parent_id)
                : null;
        }

        return $crumbs;
    }

    #[Computed]
    public function canGoBack(): bool
    {
        return $this->historyIndex > 0;
    }

    #[Computed]
    public function canGoForward(): bool
    {
        return $this->historyIndex < count($this->folderHistory) - 1;
    }

    #[Computed]
    public function isEmpty(): bool
    {
        return $this->subFolders->isEmpty() && $this->currentFiles->isEmpty();
    }

    #[Computed]
    public function selectedCount(): int
    {
        return count(array_filter($this->selected));
    }

    /**
     * Apakah user aktif punya hak WRITE/ADMIN di folder aktif
     * (dipakai di Blade untuk show/hide tombol aksi).
     */
    #[Computed]
    public function canWriteCurrentFolder(): bool
    {
        if (! $this->currentFolderId) {
            return false;
        }
        $folder = Folder::find($this->currentFolderId);
        return $folder && $this->canWrite(auth()->user(), $folder);
    }

    public function sidebarChildren(int $folderId): Collection
    {
        return $this->accessibleFolders($folderId);
    }

    // ─────────────────────────────────────────────────────────────────────
    // NAVIGASI
    // ─────────────────────────────────────────────────────────────────────

    public function openFolder(int $folderId): void
    {
        if ($this->historyIndex < count($this->folderHistory) - 1) {
            $this->folderHistory = array_slice($this->folderHistory, 0, $this->historyIndex + 1);
        }

        $this->folderHistory[] = $folderId;
        $this->historyIndex    = count($this->folderHistory) - 1;

        $this->applyFolder($folderId);
        $this->autoExpandAncestors($folderId);
    }

    public function goBack(): void
    {
        if ($this->canGoBack) {
            $this->historyIndex--;
            $this->applyFolder($this->folderHistory[$this->historyIndex]);
        }
    }

    public function goForward(): void
    {
        if ($this->canGoForward) {
            $this->historyIndex++;
            $this->applyFolder($this->folderHistory[$this->historyIndex]);
        }
    }

    public function goHome(): void
    {
        $this->currentFolderId = null;
        $this->search          = '';
        $this->selected        = [];
        $this->clearComputed();
    }

    public function refresh(): void
    {
        $this->clearComputed();
    }

    public function toggleExpand(int $folderId): void
    {
        if (in_array($folderId, $this->expandedFolderIds)) {
            $this->expandedFolderIds = array_values(
                array_filter($this->expandedFolderIds, fn ($id) => $id !== $folderId)
            );
        } else {
            $this->expandedFolderIds[] = $folderId;
        }
    }

    public function isExpanded(int $folderId): bool
    {
        return in_array($folderId, $this->expandedFolderIds);
    }

    private function applyFolder(int $folderId): void
    {
        $this->currentFolderId = $folderId;
        $this->search          = '';
        $this->selected        = [];
        $this->clearComputed();
    }

    private function autoExpandAncestors(int $folderId): void
    {
        $folder = Folder::find($folderId);
        if (! $folder) {
            return;
        }

        $parentId = $folder->parent_id;
        while ($parentId) {
            if (! in_array($parentId, $this->expandedFolderIds)) {
                $this->expandedFolderIds[] = $parentId;
            }
            $parent   = Folder::find($parentId);
            $parentId = $parent?->parent_id;
        }

        if (! in_array($folderId, $this->expandedFolderIds)) {
            $this->expandedFolderIds[] = $folderId;
        }
    }

    private function clearComputed(): void
    {
        unset(
            $this->currentFolder,
            $this->rootFolders,
            $this->subFolders,
            $this->currentFiles,
            $this->breadcrumbs,
            $this->isEmpty,
            $this->canGoBack,
            $this->canGoForward,
            $this->selectedCount,
            $this->canWriteCurrentFolder,
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // BUAT FOLDER
    // ─────────────────────────────────────────────────────────────────────

    public function openNewFolderModal(): void
    {
        if (! $this->currentFolderId) {
            $this->dispatch('notify', type: 'warning', message: 'Pilih folder terlebih dahulu.');
            return;
        }

        $this->resetValidation('newFolderName');
        $this->newFolderName      = '';
        $this->showNewFolderModal = true;
    }

    public function createFolder(): void
    {
        $this->validateOnly('newFolderName');

        $user = auth()->user();

        abort_unless($this->currentFolderId, 422, 'Folder induk tidak dipilih.');

        $parent = Folder::findOrFail($this->currentFolderId);

        // ── Cek hak tulis, naik ke ancestor jika perlu ────────────────
        abort_unless($this->canWrite($user, $parent), 403, 'Akses ditolak.');

        // Cek duplikasi nama di level ini
        $exists = Folder::where('parent_id', $this->currentFolderId)
            ->where('name', $this->newFolderName)
            ->whereNull('deleted_at')
            ->exists();

        if ($exists) {
            $this->addError('newFolderName', 'Folder dengan nama ini sudah ada.');
            return;
        }

        Folder::create([
            'name'       => $this->newFolderName,
            'parent_id'  => $this->currentFolderId,
            'path'       => $parent->buildPath() . '/' . $this->newFolderName,
            'created_by' => $user->id,
            'is_system'  => false,
        ]);

        if (! in_array($this->currentFolderId, $this->expandedFolderIds)) {
            $this->expandedFolderIds[] = $this->currentFolderId;
        }

        $this->newFolderName      = '';
        $this->showNewFolderModal = false;

        $this->clearComputed();

        $this->dispatch('notify', type: 'success', message: 'Folder berhasil dibuat.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // RENAME
    // ─────────────────────────────────────────────────────────────────────

    public function openRenameModal(int $id, string $type): void
    {
        $this->renameItemId   = $id;
        $this->renameItemType = $type;
        $this->renameName     = $type === 'folder'
            ? Folder::findOrFail($id)->name
            : File::findOrFail($id)->original_name;

        $this->resetValidation('renameName');
        $this->showRenameModal = true;
    }

    public function renameItem(): void
    {
        $this->validateOnly('renameName');

        if ($this->renameItemType === 'folder') {
            Folder::findOrFail($this->renameItemId)
                ->update(['name' => $this->renameName]);
        } else {
            File::findOrFail($this->renameItemId)
                ->update(['original_name' => $this->renameName]);
        }

        $this->showRenameModal = false;
        $this->clearComputed();
        $this->dispatch('notify', type: 'success', message: 'Nama berhasil diubah.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // DELETE
    // ─────────────────────────────────────────────────────────────────────

    public function openDeleteModal(int $id, string $type, string $name): void
    {
        $this->deleteItemId    = $id;
        $this->deleteItemType  = $type;
        $this->deleteItemName  = $name;
        $this->showDeleteModal = true;
    }

    public function deleteItem(): void
    {
        if ($this->deleteItemType === 'folder') {
            $folder = Folder::findOrFail($this->deleteItemId);
            $this->softDeleteFolderRecursive($folder);

            $this->expandedFolderIds = array_values(
                array_filter($this->expandedFolderIds, fn ($id) => $id !== $this->deleteItemId)
            );
        } else {
            $file = File::findOrFail($this->deleteItemId);
            Storage::disk($file->disk)->delete($file->storagePath());
            $file->delete();
        }

        unset($this->selected[$this->deleteItemType . '_' . $this->deleteItemId]);

        $this->showDeleteModal = false;
        $this->clearComputed();
        $this->dispatch('notify', type: 'success', message: 'Berhasil dihapus.');
    }

    private function softDeleteFolderRecursive(Folder $folder): void
    {
        foreach ($folder->children()->withTrashed()->get() as $child) {
            $this->softDeleteFolderRecursive($child);
        }

        foreach ($folder->files()->withTrashed()->get() as $file) {
            Storage::disk($file->disk)->delete($file->storagePath());
            $file->delete();
        }

        $folder->delete();
    }

    // ─────────────────────────────────────────────────────────────────────
    // UPLOAD
    // ─────────────────────────────────────────────────────────────────────

    public function openUploadModal(): void
    {
        if (! $this->currentFolderId) {
            $this->dispatch('notify', type: 'warning', message: 'Pilih folder terlebih dahulu.');
            return;
        }

        $this->uploads         = [];
        $this->showUploadModal = true;
    }

    public function updatedUploads(): void
    {
        $this->validateOnly('uploads.*');
    }

    public function uploadFiles(): void
    {
        $this->validate([
            'uploads.*' => ['file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx'],
        ]);

        $user   = auth()->user();
        $folder = Folder::findOrFail($this->currentFolderId);

        abort_unless($this->canWrite($user, $folder), 403, 'Akses ditolak.');

        $count = 0;

        foreach ($this->uploads as $upload) {
            $ext    = $upload->getClientOriginalExtension();
            $stored = time() . '_' . uniqid() . '.' . $ext;
            $upload->storeAs('documents', $stored, 'public');

            File::create([
                'folder_id'     => $this->currentFolderId,
                'original_name' => $upload->getClientOriginalName(),
                'stored_name'   => $stored,
                'mime_type'     => $upload->getMimeType(),
                'size'          => $upload->getSize(),
                'disk'          => 'public',
                'uploaded_by'   => $user->id,
            ]);

            $count++;
        }

        $this->uploads         = [];
        $this->showUploadModal = false;

        $this->clearComputed();

        $this->dispatch('notify', type: 'success', message: "{$count} file berhasil diunggah.");
    }

    // ─────────────────────────────────────────────────────────────────────
    // DOWNLOAD
    // ─────────────────────────────────────────────────────────────────────

    public function downloadFile(int $fileId): mixed
    {
        $file = File::findOrFail($fileId);

        return Storage::disk($file->disk)
            ->download($file->storagePath(), $file->original_name);
    }

    // ─────────────────────────────────────────────────────────────────────
    // SELECTION
    // ─────────────────────────────────────────────────────────────────────

    public function toggleSelect(int $id, string $type): void
    {
        $key = $type . '_' . $id;
        $this->selected[$key] = ! ($this->selected[$key] ?? false);

        if (! $this->selected[$key]) {
            unset($this->selected[$key]);
        }

        unset($this->selectedCount);
    }

    public function isSelected(int $id, string $type): bool
    {
        return $this->selected[$type . '_' . $id] ?? false;
    }

    public function clearSelection(): void
    {
        $this->selected = [];
        unset($this->selectedCount);
    }

    // ─────────────────────────────────────────────────────────────────────
    // SEARCH
    // ─────────────────────────────────────────────────────────────────────

    public function updatedSearch(): void
    {
        unset($this->subFolders, $this->currentFiles, $this->isEmpty);
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->updatedSearch();
    }

    // ─────────────────────────────────────────────────────────────────────
    // INTERNAL HELPERS
    // ─────────────────────────────────────────────────────────────────────

    private function accessibleFolders(?int $parentId): Collection
    {
        $user  = auth()->user();
        $query = Folder::where('parent_id', $parentId)
            ->whereNull('deleted_at')
            ->withCount('children');

        if (! $user->isAdmin()) {
            // Ambil folder_id yang user punya akses aktif (belum expired)
            $allowedIds = $user->folderPermissions()
                ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
                ->pluck('folder_id');

            $query->whereIn('id', $allowedIds);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Cek apakah user punya hak WRITE atau ADMIN pada folder ini.
     *
     * Logika: permission dicek dari folder itu sendiri dulu.
     * Jika tidak ada, naik ke parent chain — karena sub-folder yang dibuat
     * oleh user sendiri TIDAK otomatis punya permission record tersendiri,
     * sehingga kita cukup periksa apakah ada permission write/admin di
     * salah satu ancestor-nya.
     *
     * Admin (role = 'admin' atau isAdmin()) selalu boleh.
     */
    private function canWrite($user, Folder $folder): bool
    {
        // ── Admin selalu boleh ────────────────────────────────────────────
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }

        // Cek berdasarkan kolom role jika method isAdmin tidak ada / tidak cover
        if (isset($user->role) && $user->role === 'admin') {
            return true;
        }

        // ── Cek permission dari folder ini naik ke atas ───────────────────
        $current = $folder;

        while ($current) {
            $perm = $user->folderPermissions()
                ->where('folder_id', $current->id)
                ->first();

            if ($perm) {
                // Cek apakah permission belum expired
                $isActive = ! $perm->expires_at || ! $perm->expires_at->isPast();

                if ($isActive && in_array($perm->permission, ['write', 'admin'])) {
                    return true;
                }

                // Jika ada permission tapi hanya 'read' atau expired,
                // jangan naik lebih tinggi — akses memang dibatasi di sini.
                if ($isActive) {
                    return false;
                }
            }

            // Naik ke parent
            $current = $current->parent_id
                ? Folder::find($current->parent_id)
                : null;
        }

        return false;
    }

    // ─────────────────────────────────────────────────────────────────────
    // RENDER
    // ─────────────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.explorer', [
            'expandedIds' => $this->expandedFolderIds,
        ])->layout('layouts.app', ['title' => 'Explorer - SIMAK']);
    }
}