<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'parent_id',
        'path',
        'created_by',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────────────

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

    /**
     * Semua keturunan (rekursif) — eager load dengan nested children.
     */
    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
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

    // ── Helpers ───────────────────────────────────────────────────────────

    /**
     * Bangun path dari root ke folder ini.
     * Contoh: "/Akreditasi/Dokumen Utama"
     */
    public function buildPath(): string
    {
        $segments = [$this->name];
        $parent   = $this->parent_id ? Folder::find($this->parent_id) : null;

        while ($parent) {
            array_unshift($segments, $parent->name);
            $parent = $parent->parent_id ? Folder::find($parent->parent_id) : null;
        }

        return '/' . implode('/', $segments);
    }

    /**
     * Kedalaman folder dari root (0 = root folder).
     */
    public function depth(): int
    {
        $depth  = 0;
        $parent = $this->parent_id ? Folder::find($this->parent_id) : null;

        while ($parent) {
            $depth++;
            $parent = $parent->parent_id ? Folder::find($parent->parent_id) : null;
        }

        return $depth;
    }

    /**
     * Daftar semua ancestor (dari root ke parent langsung), untuk breadcrumb.
     */
    public function ancestors(): array
    {
        $ancestors = [];
        $folder    = $this->parent_id ? Folder::find($this->parent_id) : null;

        while ($folder) {
            array_unshift($ancestors, $folder);
            $folder = $folder->parent_id ? Folder::find($folder->parent_id) : null;
        }

        return $ancestors;
    }

    // Tambahkan method berikut ke dalam class Folder yang sudah ada

/**
 * Salin semua permission dari parent folder ke folder ini.
 * Dipanggil saat folder baru dibuat.
 * Jika sudah ada permission manual untuk user yang sama, skip.
 */
public function inheritPermissionsFromParent(): void
{
    if (! $this->parent_id) {
        return; // root folder, tidak ada parent
    }

    $parentPerms = FolderPermission::where('folder_id', $this->parent_id)->get();

    foreach ($parentPerms as $parentPerm) {
        // Cek apakah user sudah punya permission di folder ini
        $exists = FolderPermission::where('folder_id', $this->id)
            ->where('user_id', $parentPerm->user_id)
            ->exists();

        if (! $exists) {
            FolderPermission::create([
                'user_id'        => $parentPerm->user_id,
                'folder_id'      => $this->id,
                'permission'     => $parentPerm->permission,
                'expires_at'     => $parentPerm->expires_at,
                'inherited_from' => $parentPerm->id,
            ]);
        }
    }
}
}
