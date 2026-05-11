<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Folder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'parent_id', 'path', 'created_by', 'is_system', 'kode_lamp', 'uuid'
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Folder $folder) {
            if (empty($folder->uuid)) {
                $folder->uuid = (string) Str::uuid();
            }
            if (empty($folder->path)) {
                $folder->path = '';
            }
        });

        static::created(function (Folder $folder) {
            $folder->path = $folder->buildPath();
            $folder->saveQuietly();
        });
    }

    // ==================== RELATIONSHIPS ====================
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Folder::class, 'parent_id')
            ->whereNull('deleted_at')
            ->orderBy('name');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class)
            ->whereNull('deleted_at')
            ->orderBy('original_name');
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(FolderPermission::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sharedLinks(): MorphMany
    {
        return $this->morphMany(SharedLink::class, 'shareable');
    }

    // ==================== HELPERS ====================
    public function buildPath(): string
    {
        $segments = [$this->name];
        $parent = $this->parent_id ? self::find($this->parent_id) : null;

        while ($parent) {
            array_unshift($segments, $parent->name);
            $parent = $parent->parent_id ? self::find($parent->parent_id) : null;
        }

        return '/' . implode('/', $segments);
    }

    public function ancestors(): array
    {
        $ancestors = [];
        $folder = $this->parent_id ? self::find($this->parent_id) : null;

        while ($folder) {
            array_unshift($ancestors, $folder);
            $folder = $folder->parent_id ? self::find($folder->parent_id) : null;
        }

        return $ancestors;
    }

    public function hasReadAccess(int $userId): bool
    {
        $user = User::find($userId);
        if ($user && $user->isSuperAdmin()) return true;
        if ($this->created_by === $userId) return true;
        $perm = $this->permissions()
            ->where('user_id', $userId)
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->first();
        return !is_null($perm);
    }

public function hasWriteAccess(int $userId): bool
{
    $user = User::find($userId);
    if ($user && $user->isSuperAdmin()) return true;
    if ($this->created_by === $userId) return true;
    
    $perm = $this->permissions()
        ->where('user_id', $userId)
        ->whereIn('permission', ['write', 'admin']) // ← tambahkan 'admin'
        ->where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        })
        ->first();
    
    return !is_null($perm);
}

/**
 * Ambil link berbagi aktif (belum expired)
 */
public function activeSharedLink(): ?SharedLink
{
    return $this->sharedLinks()
        ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
        ->latest()
        ->first();
}

/**
 * Cek apakah folder ini adalah turunan dari folder lain.
 */
public function isDescendantOf(Folder $ancestor): bool
{
    $current = $this;
    while ($current->parent_id) {
        if ($current->parent_id === $ancestor->id) {
            return true;
        }
        $current = $current->parent;
    }
    return false;
}

/**
 * Cek apakah folder ini adalah leluhur dari folder lain.
 */
public function isAncestorOf(Folder $descendant): bool
{
    return $descendant->isDescendantOf($this);
}



}