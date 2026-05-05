<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'prodi',
        'kode_lamp',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────

    public function folderPermissions(): HasMany
    {
        return $this->hasMany(FolderPermission::class);
    }

    // ── Role Checks ───────────────────────────────────────────────────────

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdminProdi(): bool
    {
        return $this->role === 'admin_prodi';
    }

    public function isAdmin(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canUpload(): bool
    {
        return in_array($this->role, ['super_admin', 'admin_prodi']);
    }

    public function canCreateFolder(): bool
    {
        return in_array($this->role, ['super_admin', 'admin_prodi']);
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    public function activeFolderIds(): array
    {
        return $this->folderPermissions()
            ->where(fn ($q) => $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', now()))
            ->pluck('folder_id')
            ->toArray();
    }

    public function roleBadge(): string
    {
        return match ($this->role) {
            'super_admin'  => 'Super Admin',
            'admin_prodi'  => 'Admin Prodi',
            default        => 'User',
        };
    }
}