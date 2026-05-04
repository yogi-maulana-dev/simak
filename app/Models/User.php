<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\FolderPermission;
use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function folderPermissions(): HasMany
{
    return $this->hasMany(FolderPermission::class);
}


public function isAdmin(): bool
{
    // Sesuaikan dengan sistem role Anda:
    return in_array($this->role, ['super_admin', 'admin_it']);

    // Jika pakai Spatie Permission:
    // return $this->hasAnyRole(['super_admin', 'admin_it']);
}

public function activeFolderIds(): array
{
    return $this->folderPermissions()
        ->where(fn ($q) => $q->whereNull('expires_at')
            ->orWhere('expires_at', '>', now()))
        ->pluck('folder_id')
        ->toArray();
}

}
