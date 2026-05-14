<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'user_name', 'user_email',
        'action', 'description',
        'subject_type', 'subject_id',
        'source',
        'ip_address', 'latitude', 'longitude', 'user_agent', 'metadata',
        'created_at',
    ];

    protected $casts = [
        'metadata'   => 'array',
        'created_at' => 'datetime',
        'latitude'   => 'float',
        'longitude'  => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Label aksi yang ramah dibaca + ikon + warna.
     */
    public function actionInfo(): array
    {
        return match (true) {
            // ── Auth
            $this->action === 'login'         => ['label' => 'Login berhasil',  'color' => 'green',   'icon' => 'login'],
            $this->action === 'logout'        => ['label' => 'Logout',          'color' => 'gray',    'icon' => 'logout'],
            $this->action === 'login_failed'  => ['label' => 'Login gagal',     'color' => 'red',     'icon' => 'shield'],
            $this->action === 'login_blocked' => ['label' => 'Login diblokir',  'color' => 'red',     'icon' => 'shield'],

            // ── File
            str_contains($this->action, 'file_upload')   => ['label' => 'Unggah file',  'color' => 'emerald',  'icon' => 'upload'],
            str_contains($this->action, 'file_download') => ['label' => 'Unduh file',   'color' => 'blue',     'icon' => 'download'],
            str_contains($this->action, 'file_view')     => ['label' => 'Lihat file',   'color' => 'indigo',   'icon' => 'eye'],
            str_contains($this->action, 'file_rename')   => ['label' => 'Ubah nama file', 'color' => 'yellow', 'icon' => 'pencil'],
            str_contains($this->action, 'file_delete')   => ['label' => 'Hapus file',   'color' => 'red',      'icon' => 'trash'],
            str_contains($this->action, 'file_create')   => ['label' => 'File dibuat',  'color' => 'emerald',  'icon' => 'upload'],

            // ── Folder
            str_contains($this->action, 'folder_create') => ['label' => 'Buat folder',  'color' => 'blue',     'icon' => 'folder-plus'],
            str_contains($this->action, 'folder_rename') => ['label' => 'Ubah nama folder', 'color' => 'yellow', 'icon' => 'pencil'],
            str_contains($this->action, 'folder_delete') => ['label' => 'Hapus folder', 'color' => 'red',      'icon' => 'trash'],

            // ── Share
            str_contains($this->action, 'share_create')  => ['label' => 'Buat link berbagi', 'color' => 'purple', 'icon' => 'share'],
            str_contains($this->action, 'share_revoke')  => ['label' => 'Cabut link berbagi', 'color' => 'orange', 'icon' => 'share'],

            // ── User mgmt
            $this->action === 'user_create'   => ['label' => 'Tambah pengguna',         'color' => 'blue',   'icon' => 'user'],
            $this->action === 'user_delete'   => ['label' => 'Hapus pengguna',          'color' => 'red',    'icon' => 'user'],
            $this->action === 'user_password' => ['label' => 'Ganti password pengguna', 'color' => 'yellow', 'icon' => 'key'],
            $this->action === 'user_toggle'   => ['label' => 'Aktif/Nonaktifkan akun',  'color' => 'orange', 'icon' => 'user'],

            default => ['label' => $this->action, 'color' => 'gray', 'icon' => 'info'],
        };
    }

    public function isFromTrigger(): bool
    {
        return $this->source === 'trigger';
    }
}
