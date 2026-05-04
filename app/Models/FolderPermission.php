<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FolderPermission extends Model
{
    protected $fillable = [
        'user_id',
        'folder_id',
        'permission',
        'expires_at',
        // 'inherited_from' sengaja TIDAK dimasukkan fillable agar tidak pernah
        // terisi secara tidak sengaja dari mass-assignment.
        // Jika fitur inherit diaktifkan, tambahkan kembali di sini dan jalankan migration.
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // ── Relations ─────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isActive(): bool
    {
        return ! $this->isExpired();
    }
}
