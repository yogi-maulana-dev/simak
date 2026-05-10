<?php

namespace App\Livewire;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Explorer extends Component
{
    use WithFileUploads;

    public ?int $currentFolderId = null;
    public array $history = [];
    public int $historyIndex = -1;
    public string $search = '';
    public string $viewMode = 'grid';
    public array $selectedItems = [];

    public bool $showNewFolderModal = false;
    public string $newFolderName = '';

    public bool $showUploadModal = false;
    public array $uploads = [];

    public bool $showRenameModal = false;
    public ?int $renameItemId = null;
    public string $renameItemType = '';
    public string $renameName = '';

    public bool $showDeleteModal = false;
    public ?int $deleteItemId = null;
    public string $deleteItemType = '';
    public string $deleteItemName = '';

    public bool $showShareModal = false;
    public ?int $shareItemId = null;
    public string $shareItemType = '';
    public string $shareItemName = '';
    public string $sharePermission = 'view';
    public ?string $shareExpiresAt = null;
    public ?string $shareUrl = null;

    public bool $showQuickFolderModal = false;
    public ?int $quickFolderParentId = null;
    public string $quickFolderName = '';

    public array $expandedIds = [];

    protected $queryString = ['currentFolderId' => ['except' => null]];
    protected $listeners = ['refreshExplorer' => '$refresh', 'notify' => 'handleNotify'];

    public function handleNotify($payload = null)
    {
        if ($payload) {
            session()->flash('notify', $payload);
            $this->dispatch('notify', $payload);
        }
    }

    // COMPUTED (sama seperti sebelumnya, tidak perlu diubah)
    public function getRootFoldersProperty()
    {
        $user = Auth::user();
        $query = Folder::whereNull('parent_id')->whereNull('deleted_at');
        if (!$user->isSuperAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereHas('permissions', fn($p) => $p->where('user_id', $user->id)
                      ->where(fn($exp) => $exp->whereNull('expires_at')->orWhere('expires_at', '>', now())));
            });
        }
        return $query->orderBy('name')->get();
    }

    public function getSubFoldersProperty()
    {
        if (!$this->currentFolderId) return collect();
        $folder = Folder::find($this->currentFolderId);
        if (!$folder) return collect();
        $user = Auth::user();
        if (!$user->isSuperAdmin() && !$folder->hasReadAccess($user->id)) return collect();
        $query = $folder->children();
        if (!$user->isSuperAdmin()) {
            $query->where(fn($q) => $q->where('created_by', $user->id)
                ->orWhereHas('permissions', fn($p) => $p->where('user_id', $user->id)));
        }
        return $query->orderBy('name')->get();
    }

    public function getCurrentFilesProperty()
    {
        if (!$this->currentFolderId) return collect();
        $folder = Folder::find($this->currentFolderId);
        if (!$folder) return collect();
        $user = Auth::user();
        if (!$user->isSuperAdmin() && !$folder->hasReadAccess($user->id)) return collect();
        $query = $folder->files();
        if ($this->search) $query->where('original_name', 'like', '%' . $this->search . '%');
        return $query->orderBy('original_name')->get();
    }

    public function getBreadcrumbsProperty()
    {
        if (!$this->currentFolderId) return [];
        $folder = Folder::find($this->currentFolderId);
        if (!$folder) return [];
        $ancestors = $folder->ancestors();
        $crumbs = [];
        foreach ($ancestors as $anc) $crumbs[] = ['id' => $anc->id, 'name' => $anc->name];
        $crumbs[] = ['id' => $folder->id, 'name' => $folder->name];
        return $crumbs;
    }

    public function getCurrentFolderProperty() { return $this->currentFolderId ? Folder::find($this->currentFolderId) : null; }
    public function getCanGoBackProperty() { return $this->historyIndex > 0; }
    public function getCanGoForwardProperty() { return $this->historyIndex < count($this->history) - 1; }
    public function getSelectedCountProperty() { return count($this->selectedItems); }
    public function getIsEmptyProperty() { return $this->subFolders->isEmpty() && $this->currentFiles->isEmpty(); }

    // Navigation
    public function openFolder($folderId)
    {
        if (!$folderId) {
            $this->currentFolderId = null;
            $this->addToHistory(null);
            return;
        }
        $folder = Folder::find($folderId);
        if (!$folder || !$folder->hasReadAccess(Auth::id())) {
            $this->dispatch('notify', type: 'error', message: 'Akses ditolak.');
            return;
        }
        $this->currentFolderId = $folderId;
        $this->addToHistory($folderId);
        $this->search = '';
    }

    public function goHome() { $this->openFolder(null); }
    public function goBack() { if ($this->historyIndex > 0) $this->currentFolderId = $this->history[--$this->historyIndex]; }
    public function goForward() { if ($this->historyIndex < count($this->history)-1) $this->currentFolderId = $this->history[++$this->historyIndex]; }
    public function refresh() { $this->dispatch('$refresh'); }
    public function clearSearch() { $this->search = ''; }
    private function addToHistory($folderId) { if ($this->historyIndex < count($this->history)-1) $this->history = array_slice($this->history, 0, $this->historyIndex+1); $this->history[] = $folderId; $this->historyIndex = count($this->history)-1; }

    // Selection
    public function toggleSelect($id, $type) { $key = $type.'_'.$id; if (isset($this->selectedItems[$key])) unset($this->selectedItems[$key]); else $this->selectedItems[$key] = $type; }
    public function isSelected($id, $type) { return isset($this->selectedItems[$type.'_'.$id]); }
    public function clearSelection() { $this->selectedItems = []; }

    // Create folder via action bar
public function openNewFolderModal()
{
    if (!$this->currentFolderId) {
        $this->dispatch('notify', type: 'error', message: 'Pilih folder terlebih dahulu.');
        return;
    }
    $folder = Folder::find($this->currentFolderId);
    if (!$folder) {
        $this->dispatch('notify', type: 'error', message: 'Folder tidak ditemukan. Hubungin Superadmin untuk memperbaiki data.');
        return;
    }
    if (!$folder->hasWriteAccess(Auth::id())) {
        $this->dispatch('notify', type: 'error', message: 'Anda tidak memiliki izin untuk membuat sub-folder di sini.');
        return;
    }
    $this->newFolderName = '';
    $this->showNewFolderModal = true;
}

    public function createFolder()
    {
        $this->validate(['newFolderName' => 'required|string|max:255']);
        $parentId = $this->currentFolderId;
        if ($parentId && !Folder::find($parentId)->hasWriteAccess(Auth::id())) {
            $this->dispatch('notify', type: 'error', message: 'Tidak diizinkan.');
            $this->showNewFolderModal = false;
            return;
        }
        Folder::create(['name' => $this->newFolderName, 'parent_id' => $parentId, 'created_by' => Auth::id(), 'uuid' => (string) Str::uuid()]);
        $this->dispatch('notify', type: 'success', message: 'Folder berhasil dibuat.');
        $this->showNewFolderModal = false;
        $this->newFolderName = '';
        $this->dispatch('$refresh');
    }

    // Upload
public function openUploadModal()
{
    if (!$this->currentFolderId) {
        $this->dispatch('notify', type: 'error', message: 'Pilih folder terlebih dahulu.');
        return;
    }
    $folder = Folder::find($this->currentFolderId);
    if (!$folder) {
        $this->dispatch('notify', type: 'error', message: 'Folder tidak ditemukan. Hubungin Superadmin untuk memperbaiki data.');
        return;
    }
    if (!$folder->hasWriteAccess(Auth::id())) {
        $this->dispatch('notify', type: 'error', message: 'Anda tidak memiliki izin untuk mengunggah file di sini.');
        return;
    }
    $this->uploads = [];
    $this->showUploadModal = true;
}
public function uploadFiles()
{
    $this->validate([
        'uploads.*' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
    ]);

    $folder = Folder::find($this->currentFolderId);
    if (!$folder->hasWriteAccess(Auth::id())) {
        $this->dispatch('notify', type: 'error', message: 'Tidak diizinkan.');
        $this->showUploadModal = false;
        return;
    }

    foreach ($this->uploads as $upload) {
        // Generate nama unik untuk stored_name
        $originalName = $upload->getClientOriginalName();
        $extension = $upload->getClientOriginalExtension();
        $storedName = \Illuminate\Support\Str::random(40) . '.' . $extension;
        
        // Simpan file ke storage/app/public/documents
        $path = $upload->storeAs('documents', $storedName, 'public');

        File::create([
            'folder_id'      => $folder->id,
            'original_name'  => $originalName,
            'stored_name'    => $storedName,
            'extension'      => $extension,
            'size'           => $upload->getSize(),
            'mime_type'      => $upload->getMimeType(),
            'uploaded_by'    => Auth::id(),
            'disk'           => 'public',
        ]);
    }

    $this->dispatch('notify', type: 'success', message: count($this->uploads) . ' file berhasil diunggah.');
    $this->showUploadModal = false;
    $this->uploads = [];
    $this->dispatch('$refresh');
}

public function downloadFile($fileId)
{
    $file = File::findOrFail($fileId);
    if (!$file->folder->hasReadAccess(Auth::id())) {
        abort(403);
    }

    if (!$file->fileExists()) {
        $file->forceDelete();
        $this->dispatch('notify', type: 'error', message: 'File sudah tidak ada, data dibersihkan.');
        $this->dispatch('$refresh');
        return redirect()->back();
    }

    return response()->download(
        Storage::disk($file->disk ?? 'public')->path($file->storagePath()),
        $file->original_name
    );
}

    // Rename
    public function openRenameModal($id, $type)
    {
        if ($type === 'folder') {
            $folder = Folder::findOrFail($id);
            if (!$folder->hasWriteAccess(Auth::id())) {
                $this->dispatch('notify', type: 'error', message: 'Tidak diizinkan rename folder.');
                return;
            }
            $this->renameName = $folder->name;
        } else {
            $file = File::findOrFail($id);
            if (!$file->folder->hasWriteAccess(Auth::id())) {
                $this->dispatch('notify', type: 'error', message: 'Tidak diizinkan rename file.');
                return;
            }
            $this->renameName = $file->original_name;
        }
        $this->renameItemId = $id;
        $this->renameItemType = $type;
        $this->showRenameModal = true;
    }

    public function renameItem()
    {
        $this->validate(['renameName' => 'required|string|max:255']);
        if ($this->renameItemType === 'folder') {
            $folder = Folder::findOrFail($this->renameItemId);
            if (!$folder->hasWriteAccess(Auth::id())) {
                $this->dispatch('notify', type: 'error', message: 'Akses ditolak.');
                $this->showRenameModal = false;
                return;
            }
            $folder->name = $this->renameName;
            $folder->save();
            $folder->path = $folder->buildPath();
            $folder->saveQuietly();
        } else {
            $file = File::findOrFail($this->renameItemId);
            if (!$file->folder->hasWriteAccess(Auth::id())) {
                $this->dispatch('notify', type: 'error', message: 'Akses ditolak.');
                $this->showRenameModal = false;
                return;
            }
            $file->original_name = $this->renameName;
            $file->save();
        }
        $this->dispatch('notify', type: 'success', message: 'Nama berhasil diubah.');
        $this->showRenameModal = false;
        $this->renameItemId = null;
        $this->renameItemType = '';
        $this->renameName = '';
        $this->dispatch('$refresh');
    }

    // Delete
public function openDeleteModal($id, $type, $name)
{
    if ($type === 'folder') {
        $folder = Folder::findOrFail($id);
        $canWrite = $folder->hasWriteAccess(Auth::id());
        \Log::info('Delete folder check', [
            'user_id' => Auth::id(),
            'folder_id' => $id,
            'can_write' => $canWrite,
            'created_by' => $folder->created_by,
            'is_superadmin' => Auth::user()->isSuperAdmin(),
        ]);
        if (!$canWrite) {
            $this->dispatch('notify', type: 'error', message: 'Tidak diizinkan hapus folder (debug: hasWriteAccess=false).');
            return;
        }
        }
        $this->deleteItemId = $id;
        $this->deleteItemType = $type;
        $this->deleteItemName = $name;
        $this->showDeleteModal = true;
    }

    public function deleteItem()
    {
        if ($this->deleteItemType === 'folder') {
            $this->deleteFolderRecursive(Folder::findOrFail($this->deleteItemId));
        } else {
            $file = File::findOrFail($this->deleteItemId);
            if ($file->path && Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
            $file->delete();
        }
        $this->dispatch('notify', type: 'success', message: 'Item dihapus.');
        $this->showDeleteModal = false;
        $this->clearSelection();
        $this->dispatch('$refresh');
    }

    private function deleteFolderRecursive(Folder $folder)
    {
        foreach ($folder->files as $file) {
            if ($file->path && Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
            $file->delete();
        }
        foreach ($folder->children as $child) {
            $this->deleteFolderRecursive($child);
        }
        $folder->delete();
    }

    // Share (sederhana)
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


    // Quick folder (superadmin)
    public function openQuickFolderModal($parentId = null)
    {
        if (!Auth::user()->isSuperAdmin()) {
            $this->dispatch('notify', type: 'error', message: 'Hanya superadmin.');
            return;
        }
        $this->quickFolderParentId = $parentId;
        $this->quickFolderName = '';
        $this->showQuickFolderModal = true;
    }

    public function createQuickFolder()
    {
        $this->validate(['quickFolderName' => 'required|string|max:255']);
        Folder::create(['name' => $this->quickFolderName, 'parent_id' => $this->quickFolderParentId, 'created_by' => Auth::id(), 'uuid' => (string) Str::uuid()]);
        $this->dispatch('notify', type: 'success', message: "Folder '{$this->quickFolderName}' dibuat.");
        $this->showQuickFolderModal = false;
        $this->quickFolderName = '';
        $this->quickFolderParentId = null;
        $this->dispatch('$refresh');
    }

    public function toggleExpand($folderId)
    {
        if (in_array($folderId, $this->expandedIds)) {
            $this->expandedIds = array_diff($this->expandedIds, [$folderId]);
        } else {
            $this->expandedIds[] = $folderId;
        }
    }

    public function render()
    {
        return view('livewire.explorer');
    }
}