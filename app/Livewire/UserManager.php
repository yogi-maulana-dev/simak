<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class UserManager extends Component
{
    use WithPagination;

    // ── Search ────────────────────────────────────────────────────────────
    public string $search = '';

    // ── Add User Modal ─────────────────────────────────────────────────────
    public bool $showAddModal   = false;
    public string $newName      = '';
    public string $newEmail     = '';
    public string $newRole      = 'admin_prodi';
    public string $newProdi     = '';

    // ── Change Password Modal ──────────────────────────────────────────────
    public bool $showPasswordModal  = false;
    public ?int $passwordUserId     = null;
    public string $passwordUserName = '';
    public string $newPassword      = '';
    public string $confirmPassword  = '';

    // ── Delete Confirm Modal ───────────────────────────────────────────────
    public bool $showDeleteModal  = false;
    public ?int $deleteUserId     = null;
    public string $deleteUserName = '';

    // ── Notification ───────────────────────────────────────────────────────
    public string $flash        = '';
    public string $flashType    = 'success'; // success | error

    // ── Computed ───────────────────────────────────────────────────────────

    #[Computed]
    public function users()
    {
        return User::when($this->search, fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
            )
            ->orderBy('name')
            ->paginate(15);
    }

    // ── Lifecycle ──────────────────────────────────────────────────────────

    public function mount(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ── Add User ───────────────────────────────────────────────────────────

    public function openAddModal(): void
    {
        $this->reset(['newName', 'newEmail', 'newRole', 'newProdi']);
        $this->newRole  = 'admin_prodi';
        $this->showAddModal = true;
    }

    public function addUser(): void
    {
        $this->validate([
            'newName'  => 'required|string|max:255',
            'newEmail' => 'required|email|unique:users,email',
            'newRole'  => 'required|in:super_admin,admin_prodi,user',
            'newProdi' => 'nullable|string|max:255',
        ], [], [
            'newName'  => 'nama',
            'newEmail' => 'email',
            'newRole'  => 'peran',
            'newProdi' => 'prodi',
        ]);

        $newUser = User::create([
            'name'      => $this->newName,
            'email'     => $this->newEmail,
            'password'  => Hash::make('uml_it@1234'),
            'role'      => $this->newRole,
            'prodi'     => $this->newProdi ?: null,
            'is_active' => true,
        ]);

        ActivityLogger::log(
            action: 'user_create',
            description: "Menambahkan pengguna baru \"{$newUser->name}\" ({$newUser->email}, {$newUser->role})",
            subject: $newUser,
        );

        $this->showAddModal = false;
        $this->flash('Pengguna berhasil ditambahkan. Password default: uml_it@1234');
        unset($this->users);
    }

    // ── Toggle Active ──────────────────────────────────────────────────────

    public function toggleActive(int $userId): void
    {
        $user = User::findOrFail($userId);

        // Jangan nonaktifkan diri sendiri
        if ($user->id === auth()->id()) {
            $this->flash('Tidak dapat menonaktifkan akun sendiri.', 'error');
            return;
        }

        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLogger::log(
            action: 'user_toggle',
            description: "Akun {$user->name} {$status}",
            subject: $user,
            metadata: ['is_active' => $user->is_active],
        );

        $this->flash("Akun {$user->name} berhasil {$status}.");
        unset($this->users);
    }

    // ── Change Password ────────────────────────────────────────────────────

    public function openPasswordModal(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->passwordUserId   = $userId;
        $this->passwordUserName = $user->name;
        $this->newPassword      = '';
        $this->confirmPassword  = '';
        $this->showPasswordModal = true;
    }

    public function changePassword(): void
    {
        $this->validate([
            'newPassword'     => 'required|string|min:6',
            'confirmPassword' => 'required|same:newPassword',
        ], [], [
            'newPassword'     => 'password baru',
            'confirmPassword' => 'konfirmasi password',
        ]);

        $target = User::findOrFail($this->passwordUserId);
        $target->update([
            'password' => Hash::make($this->newPassword),
        ]);

        ActivityLogger::log(
            action: 'user_password',
            description: "Mengganti password pengguna \"{$target->name}\"",
            subject: $target,
        );

        $this->showPasswordModal = false;
        $this->flash("Password {$this->passwordUserName} berhasil diubah.");
    }

    // ── Delete ─────────────────────────────────────────────────────────────

    public function openDeleteModal(int $userId): void
    {
        $user = User::findOrFail($userId);

        if ($user->id === auth()->id()) {
            $this->flash('Tidak dapat menghapus akun sendiri.', 'error');
            return;
        }

        $this->deleteUserId   = $userId;
        $this->deleteUserName = $user->name;
        $this->showDeleteModal = true;
    }

    public function deleteUser(): void
    {
        $user = User::findOrFail($this->deleteUserId);

        if ($user->id === auth()->id()) {
            $this->flash('Tidak dapat menghapus akun sendiri.', 'error');
            $this->showDeleteModal = false;
            return;
        }

        $name  = $user->name;
        $email = $user->email;

        ActivityLogger::log(
            action: 'user_delete',
            description: "Menghapus pengguna \"{$name}\" ({$email})",
            subject: $user,
        );

        $user->delete();

        $this->showDeleteModal = false;
        $this->flash("Pengguna {$name} berhasil dihapus.");
        unset($this->users);
    }

    // ── Flash Helper ───────────────────────────────────────────────────────

    private function flash(string $message, string $type = 'success'): void
    {
        $this->flash     = $message;
        $this->flashType = $type;

        $this->dispatch('flashMessage');
    }

    // ── Render ─────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.user-manager')
            ->layout('layouts.app');
    }
}
