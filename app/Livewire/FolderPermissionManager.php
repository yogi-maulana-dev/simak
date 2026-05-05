<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Folder;
use App\Models\FolderPermission;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Kelola Akses - SIMAK')]
class FolderPermissionManager extends Component
{
    public string  $search         = '';
    public ?int    $activeFolderId = null;

    // Form grant
    public string  $selectedUserId = '';
    public string  $permission     = 'read';
    public ?string $expiresAt      = null;

    public function mount(): void
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);
    }

    // ── Computed ──────────────────────────────────────────────────────────

    #[Computed]
    public function folders()
    {
        return Folder::whereNull('deleted_at')
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('kode_lamp')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function activeFolder(): ?Folder
    {
        return $this->activeFolderId ? Folder::find($this->activeFolderId) : null;
    }

    #[Computed]
    public function folderPermissions()
    {
        if (! $this->activeFolderId) {
            return collect();
        }

        return FolderPermission::with('user')
            ->where('folder_id', $this->activeFolderId)
            ->get();
    }

    #[Computed]
    public function users()
    {
        return User::where('role', '!=', 'super_admin')
            ->orderBy('prodi')
            ->orderBy('name')
            ->get();
    }

    // ── Actions ───────────────────────────────────────────────────────────

    public function selectFolder(int $folderId): void
    {
        $this->activeFolderId = $folderId;
        $this->reset(['selectedUserId', 'permission', 'expiresAt']);
        unset($this->activeFolder, $this->folderPermissions);
    }

    public function grantPermission(): void
    {
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'permission'     => 'required|in:read,write,admin',
            'expiresAt'      => 'nullable|date|after:now',
        ]);

        FolderPermission::updateOrCreate(
            [
                'folder_id' => $this->activeFolderId,
                'user_id'   => $this->selectedUserId,
            ],
            [
                'permission' => $this->permission,
                'expires_at' => $this->expiresAt ?: null,
            ]
        );

        $this->reset(['selectedUserId', 'permission', 'expiresAt']);
        unset($this->folderPermissions);

        $this->dispatch('notify', type: 'success', message: 'Akses berhasil diberikan.');
    }

    public function revokePermission(int $permissionId): void
    {
        FolderPermission::findOrFail($permissionId)->delete();
        unset($this->folderPermissions);

        $this->dispatch('notify', type: 'success', message: 'Akses berhasil dicabut.');
    }

    public function updatedSearch(): void
    {
        unset($this->folders);
        $this->activeFolderId = null;
    }

    public function render()
    {
        return view('livewire.folder-permission-manager')
            ->layout('layouts.app', ['title' => 'Kelola Akses - SIMAK']);
    }
}