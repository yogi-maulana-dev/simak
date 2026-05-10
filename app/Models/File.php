<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'folder_id',
        'original_name',
        'stored_name',
        'mime_type',
        'size',
        'disk',
        'uploaded_by',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────────

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function sharedLinks(): MorphMany
    {
        return $this->morphMany(SharedLink::class, 'shareable');
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    /**
     * Path relatif di dalam disk (misal: documents/nama_file.pdf)
     */
    public function storagePath(): string
    {
        if (empty($this->stored_name)) {
            return '';
        }
        return 'documents/' . $this->stored_name;
    }

    /**
     * URL publik file (jika disk = public dan symlink terpasang)
     */
    public function url(): string
    {
        if (empty($this->stored_name)) {
            return '';
        }
        return Storage::disk($this->disk ?? 'public')->url($this->storagePath());
    }

    /**
     * Ekstensi file (lowercase)
     */
    public function extension(): string
    {
        return strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION));
    }

    /**
     * Format ukuran file
     */
    public function formattedSize(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < 3) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 1) . ' ' . $units[$i];
    }

    /**
     * Ikon kelas untuk tampilan
     */
    public function iconClass(): string
    {
        return match ($this->extension()) {
            'pdf'        => 'file-pdf text-red-500',
            'doc','docx' => 'file-word text-blue-600',
            'xls','xlsx' => 'file-excel text-green-600',
            'ppt','pptx' => 'file-powerpoint text-orange-500',
            default      => 'file text-gray-400',
        };
    }

    /**
     * Cek apakah file fisik benar-benar ada di storage
     */
    public function fileExists(): bool
    {
        if (empty($this->stored_name)) {
            return false;
        }
        return Storage::disk($this->disk ?? 'public')->exists($this->storagePath());
    }

    /**
     * Hapus record file jika fisiknya tidak ada (opsional)
     */
    public function deleteIfMissing(): bool
    {
        if (!$this->fileExists()) {
            $this->forceDelete();
            return true;
        }
        return false;
    }



    public function activeSharedLink(): ?SharedLink
{
    return $this->sharedLinks()
        ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
        ->latest()
        ->first();
}
}