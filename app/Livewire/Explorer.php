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

    // ── Navigasi ──────────────────────────────────────────────────────────

    public ?int $currentFolderId = null;
    public array $folderHistory  = [];
    public int   $historyIndex   = -1;

    // ── UI ────────────────────────────────────────────────────────────────

    public string $viewMode           = 'grid';
    public bool   $showNewFolderModal = false;
    public bool   $showUploadModal    = false;
    public bool   $showRenameModal    = false;
    public bool   $showDeleteModal    = false;
    public string $search             = '';

    // ── Share ─────────────────────────────────────────────────────────────

    public bool    $showShareModal  = false;
    public ?int    $shareItemId     = null;
    public string  $shareItemType   = ''; // 'file' | 'folder'
    public string  $shareItemName   = '';
    public string  $sharePermission = 'download';
    public ?string $shareExpiresAt  = null;
    public ?string $shareUrl        = null;
    public bool    $shareCopied     = false;

    // ── Sidebar ───────────────────────────────────────────────────────────

    public array $expandedFolderIds = [];

    // ── Form: Buat Folder ─────────────────────────────────────────────────

    #[Validate('required|string|max:255', message: [
        'required' => 'Nama folder wajib diisi.',
        'max'      => 'Nama folder maksimal 255 karakter.',
    ])]
    public string $newFolderName = '';

    // ── Form: Rename ──────────────────────────────────────────────────────

    #[Validate('required|string|max:255', message: [
        'required' => 'Nama wajib diisi.',
    ])]
    public string $renameName     = '';
    public ?int   $renameItemId   = null;
    public string $renameItemType = '';

    // ── Form: Delete ──────────────────────────────────────────────────────

    public ?int   $deleteItemId   = null;
    public string $deleteItemType = '';
    public string $deleteItemName = '';

    // ── Upload ────────────────────────────────────────────────────────────

    #[Validate([
        'uploads.*' => ['file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx'],
    ], message: [
        'uploads.*.max'   => 'Ukuran file maksimal 10 MB.',
        'uploads.*.mimes' => 'Format tidak didukung. Gunakan: PDF, Word, Excel, atau PowerPoint.',
    ])]
    public array $uploads = [];

    // ── Selection ─────────────────────────────────────────────────────────

    public array $selected = [];

    // ─────────────────────────────────────────────────────────────────────
    // COMPUTED
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
                fn (Folder $f) => str_contains(
                    strtolower($f->name),
                    strtolower($this->search)
                )
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

        $user  = auth()->user();
        $query = File::where('folder_id', $this->currentFolderId)
            ->whereNull('deleted_at')
            ->with('uploader');

        if (! $user->isSuperAdmin()) {
            $folder = Folder::find($this->currentFolderId);
            if ($folder && $folder->kode_lamp !== $user->kode_lamp) {
                $hasPermission = $user->folderPermissions()
                    ->where('folder_id', $this->currentFolderId)
                    ->where(fn ($q) => $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now()))
                    ->exists();

                if (! $hasPermission) {
                    return collect();
                }
            }
        }

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
            array_unshift($crumbs, ['id' => $folder->id, 'name' => $folder->name]);
            $folder = $folder->parent_id ? Folder::find($folder->parent_id) : null;
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

    #[Computed]
    public function canWriteCurrentFolder(): bool
    {
        if (! $this->currentFolderId) {
            return false;
        }

        $folder = Folder::find($this->currentFolderId);
        return $folder && $this->canWrite(auth()->user(), $folder);
    }

    #[Computed]
    public function userCanCreateFolder(): bool
    {
        return auth()->user()->canCreateFolder();
    }

    #[Computed]
    public function userCanUpload(): bool
    {
        return auth()->user()->canUpload();
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
        $folder = Folder::findOrFail($folderId);
        abort_unless($this->canAccess(auth()->user(), $folder), 403);

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
            $this->userCanCreateFolder,
            $this->userCanUpload,
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // BUAT FOLDER
    // ─────────────────────────────────────────────────────────────────────

    public function openNewFolderModal(): void
    {
        abort_unless(auth()->user()->canCreateFolder(), 403);

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

        $user   = auth()->user();
        $parent = Folder::findOrFail($this->currentFolderId);

        abort_unless($this->canWrite($user, $parent), 403, 'Akses ditolak.');

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
            'kode_lamp'  => $parent->kode_lamp ?? $user->kode_lamp,
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
        $user = auth()->user();

        if ($type === 'folder') {
            $folder = Folder::findOrFail($id);
            abort_unless($this->canWrite($user, $folder), 403);
            $this->renameName = $folder->name;
        } else {
            $file   = File::findOrFail($id);
            $folder = Folder::findOrFail($file->folder_id);
            abort_unless($this->canWrite($user, $folder), 403);
            $this->renameName = $file->original_name;
        }

        $this->renameItemId   = $id;
        $this->renameItemType = $type;
        $this->resetValidation('renameName');
        $this->showRenameModal = true;
    }

    public function renameItem(): void
    {
        $this->validateOnly('renameName');

        $user = auth()->user();

        if ($this->renameItemType === 'folder') {
            $folder = Folder::findOrFail($this->renameItemId);
            abort_unless($this->canWrite($user, $folder), 403);
            $folder->update(['name' => $this->renameName]);
        } else {
            $file   = File::findOrFail($this->renameItemId);
            $folder = Folder::findOrFail($file->folder_id);
            abort_unless($this->canWrite($user, $folder), 403);
            $file->update(['original_name' => $this->renameName]);
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
        $user = auth()->user();

        if ($type === 'folder') {
            $folder = Folder::findOrFail($id);
            abort_unless($this->canWrite($user, $folder), 403);
        } else {
            $file   = File::findOrFail($id);
            $folder = Folder::findOrFail($file->folder_id);
            abort_unless($this->canWrite($user, $folder), 403);
        }

        $this->deleteItemId   = $id;
        $this->deleteItemType = $type;
        $this->deleteItemName = $name;
        $this->showDeleteModal = true;
    }

    public function deleteItem(): void
    {
        $user = auth()->user();

        if ($this->deleteItemType === 'folder') {
            $folder = Folder::findOrFail($this->deleteItemId);
            abort_unless($this->canWrite($user, $folder), 403);
            $this->softDeleteFolderRecursive($folder);

            $this->expandedFolderIds = array_values(
                array_filter($this->expandedFolderIds, fn ($id) => $id !== $this->deleteItemId)
            );
        } else {
            $file   = File::findOrFail($this->deleteItemId);
            $folder = Folder::findOrFail($file->folder_id);
            abort_unless($this->canWrite($user, $folder), 403);

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
        abort_unless(auth()->user()->canUpload(), 403);

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
        $file   = File::findOrFail($fileId);
        $user   = auth()->user();
        $folder = Folder::findOrFail($file->folder_id);

        abort_unless($this->canAccess($user, $folder), 403);

        \App\Support\ActivityLogger::log(
            action: 'file_download',
            description: "Mengunduh file \"{$file->original_name}\"",
            subject: $file,
        );

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
    // SHARE
    // ─────────────────────────────────────────────────────────────────────

    public function openShareModal(int $id, string $type, string $name): void
    {
        $user = auth()->user();

        if ($type === 'file') {
            $file = File::findOrFail($id);

            // Hanya uploader atau super_admin
            if (! ($file->uploaded_by === $user->id || $user->isSuperAdmin())) {
                $this->dispatch('notify',
                    type: 'warning',
                    message: 'Hanya pengunggah atau Super Admin yang dapat berbagi file ini.'
                );
                return;
            }
        } else {
            $folder = Folder::findOrFail($id);

            // Hanya pembuat folder atau super_admin
            if (! ($folder->created_by === $user->id || $user->isSuperAdmin())) {
                $this->dispatch('notify',
                    type: 'warning',
                    message: 'Hanya pembuat folder atau Super Admin yang dapat berbagi folder ini.'
                );
                return;
            }
        }

        $this->shareItemId     = $id;
        $this->shareItemType   = $type;
        $this->shareItemName   = $name;
        $this->sharePermission = 'download';
        $this->shareExpiresAt  = null;
        $this->shareCopied     = false;

        // Cek apakah sudah punya link aktif
        $model            = $type === 'file'
            ? File::find($id)
            : Folder::find($id);
        $activeLink       = $model?->activeSharedLink();
        $this->shareUrl   = $activeLink?->url();

        // Jika ada link aktif, isi ulang permission & expires dari data tersimpan
        if ($activeLink) {
            $this->sharePermission = $activeLink->permission;
            $this->shareExpiresAt  = $activeLink->expires_at?->format('Y-m-d\TH:i');
        }

        $this->showShareModal = true;
    }

    public function createShareLink(): void
    {
        $this->validate([
            'sharePermission' => 'required|in:view,download',
            'shareExpiresAt'  => 'nullable|date|after:now',
        ], [
            'shareExpiresAt.after' => 'Tanggal kedaluwarsa harus di masa mendatang.',
        ]);

        $user = auth()->user();

        $modelClass = $this->shareItemType === 'file'
            ? File::class
            : Folder::class;

        $model = $modelClass::findOrFail($this->shareItemId);

        // Validasi ulang hak berbagi
        if ($this->shareItemType === 'file') {
            if (! ($model->uploaded_by === $user->id || $user->isSuperAdmin())) {
                $this->dispatch('notify', type: 'error', message: 'Akses ditolak.');
                return;
            }
        } else {
            if (! ($model->created_by === $user->id || $user->isSuperAdmin())) {
                $this->dispatch('notify', type: 'error', message: 'Akses ditolak.');
                return;
            }
        }

        // Hapus link lama jika ada
        $model->sharedLinks()->delete();

        // Buat link baru
        $link = \App\Models\SharedLink::create([
            'token'          => \App\Models\SharedLink::generateToken(),
            'shareable_type' => $modelClass,
            'shareable_id'   => $this->shareItemId,
            'created_by'     => $user->id,
            'permission'     => $this->sharePermission,
            'expires_at'     => $this->shareExpiresAt ?: null,
        ]);

        $this->shareUrl    = $link->url();
        $this->shareCopied = false;

        $this->dispatch('notify', type: 'success', message: 'Link berbagi berhasil dibuat.');
        $this->dispatch('share-link-created', url: $this->shareUrl);
    }

    public function revokeShareLink(): void
    {
        $user = auth()->user();

        $modelClass = $this->shareItemType === 'file'
            ? File::class
            : Folder::class;

        $model = $modelClass::findOrFail($this->shareItemId);

        if ($this->shareItemType === 'file') {
            if (! ($model->uploaded_by === $user->id || $user->isSuperAdmin())) {
                $this->dispatch('notify', type: 'error', message: 'Akses ditolak.');
                return;
            }
        } else {
            if (! ($model->created_by === $user->id || $user->isSuperAdmin())) {
                $this->dispatch('notify', type: 'error', message: 'Akses ditolak.');
                return;
            }
        }

        $model->sharedLinks()->delete();

        $this->shareUrl    = null;
        $this->shareCopied = false;

        $this->dispatch('notify', type: 'success', message: 'Link berbagi berhasil dicabut.');
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

        if ($user->isSuperAdmin()) {
            return $query->orderBy('name')->get();
        }

        $explicitIds = $user->activeFolderIds();

        $query->where(function ($q) use ($user, $explicitIds) {
            if ($user->kode_lamp) {
                $q->where('kode_lamp', $user->kode_lamp);
            }

            if (! empty($explicitIds)) {
                $q->orWhereIn('id', $explicitIds);
            }
        });

        return $query->orderBy('name')->get();
    }

    private function canAccess($user, Folder $folder): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->kode_lamp && $folder->kode_lamp === $user->kode_lamp) {
            return true;
        }

        $current = $folder;
        while ($current) {
            $perm = $user->folderPermissions()
                ->where('folder_id', $current->id)
                ->first();

            if ($perm && $perm->isActive()) {
                return true;
            }

            $current = $current->parent_id
                ? Folder::find($current->parent_id)
                : null;
        }

        return false;
    }

    private function canWrite($user, Folder $folder): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (! $user->canUpload()) {
            return false;
        }

        if ($user->kode_lamp && $folder->kode_lamp === $user->kode_lamp) {
            return true;
        }

        $current = $folder;
        while ($current) {
            $perm = $user->folderPermissions()
                ->where('folder_id', $current->id)
                ->first();

            if ($perm && $perm->isActive()) {
                if (in_array($perm->permission, ['write', 'admin'])) {
                    return true;
                }
                return false;
            }

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