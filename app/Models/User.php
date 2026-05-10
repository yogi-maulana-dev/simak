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
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'         => 'datetime',
            'password'                  => 'hashed',
            'is_active'                 => 'boolean',
            'two_factor_secret'         => 'encrypted',
            'two_factor_recovery_codes' => 'encrypted:array',
            'two_factor_confirmed_at'   => 'datetime',
        ];
    }

    // ── 2FA helpers ──

    public function hasTwoFactorEnabled(): bool
    {
        return ! is_null($this->two_factor_confirmed_at) && ! is_null($this->two_factor_secret);
    }

    // ── Relationships ─────────────────────────────────────────────────────

    public function folderPermissions(): HasMany
    {
        return $this->hasMany(FolderPermission::class);
    }

    // ── Role Checks ───────────────────────────────────────────────────────

   public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin' || $this->is_super_admin === true;
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