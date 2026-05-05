<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class Folder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'parent_id',
        'path',
        'created_by',
        'is_system',
        'kode_lamp',
        'uuid',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Folder $folder) {
            if (empty($folder->uuid)) {
                $folder->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

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

    // Tambahkan ke app/Models/Folder.php

public function sharedLinks(): MorphMany
{
    return $this->morphMany(SharedLink::class, 'shareable');
}

public function activeSharedLink(): ?SharedLink
{
    return $this->sharedLinks()
        ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
        ->latest()
        ->first();
}
}