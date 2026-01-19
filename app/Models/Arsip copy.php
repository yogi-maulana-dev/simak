<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Arsip extends Model
{
    protected $table = 'arsip';

    protected $fillable = [
        'judul',
        'deskripsi',
        'file',
        'user_id',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke DataFakultas (tabel pivot)
    public function dataFakultas()
    {
        return $this->hasMany(DataFakultas::class, 'arsip_id', 'id');
    }

    // Relasi ke Fakultas melalui DataFakultas (untuk akses mudah)
    public function fakultas()
    {
        return $this->hasOneThrough(
            Fakultas::class,
            DataFakultas::class,
            'arsip_id', // Foreign key pada tabel DataFakultas
            'id',       // Foreign key pada tabel Fakultas
            'id',       // Local key pada tabel Arsip
            'fakultas_id' // Local key pada tabel DataFakultas
        );
    }

    // Atau cara lebih sederhana: accessor untuk fakultas
    public function getFakultasAttribute()
    {
        $dataFakultas = $this->dataFakultas->first();
        return $dataFakultas ? $dataFakultas->fakultas : null;
    }

    // Accessor untuk fakultas_id
    public function getFakultasIdAttribute()
    {
        $dataFakultas = $this->dataFakultas->first();
        return $dataFakultas ? $dataFakultas->fakultas_id : null;
    }

    /**
     * Scope filter arsip sesuai role user
     */
    public function scopeVisibleFor(Builder $query, $user)
    {
        // 🔥 SUPERADMIN → lihat semua
        if ($user->role->name === 'superadmin') {
            return $query;
        }

        // 👁️ ASESOR → lihat sesuai fakultas
        if ($user->role->name === 'asesor' || $user->role->name === 'asesor_fakultas') {
            return $query->whereHas('dataFakultas', function ($q) use ($user) {
                $q->where('fakultas_id', $user->fakultas_id);
            });
        }

        // ADMIN FAKULTAS → lihat sesuai fakultas
        if ($user->role->name === 'admin_fakultas') {
            return $query->whereHas('dataFakultas', function ($q) use ($user) {
                $q->where('fakultas_id', $user->fakultas_id);
            });
        }

        // ADMIN PRODI → lihat sesuai prodi
        if ($user->role->name === 'admin_prodi' || $user->role->name === 'asesor_prodi') {
            return $query->whereHas('user', function ($q) use ($user) {
                $q->where('prodi_id', $user->prodi_id);
            });
        }

        // 👤 USER / lainnya → hanya arsip sendiri
        return $query->where('user_id', $user->id);
    }

    /**
     * Scope untuk superadmin (semua arsip)
     */
    public function scopeForSuperadmin(Builder $query)
    {
        return $query;
    }

    /**
     * Scope untuk admin fakultas/asesor
     */
    public function scopeForFakultas(Builder $query, $fakultasId)
    {
        return $query->whereHas('dataFakultas', function ($q) use ($fakultasId) {
            $q->where('fakultas_id', $fakultasId);
        });
    }

    /**
     * Scope untuk admin prodi/asesor prodi
     */
    public function scopeForProdi(Builder $query, $prodiId)
    {
        return $query->whereHas('user', function ($q) use ($prodiId) {
            $q->where('prodi_id', $prodiId);
        });
    }

    /**
     * Eager load untuk menghindari N+1
     */
    public function scopeWithRelations($query)
    {
        return $query->with(['user', 'dataFakultas.fakultas', 'user.fakultas', 'user.prodi']);
    }


    /**
     * Get fakultas through user (akses melalui relasi user)
     */
    public function getFakultasAttribute()
    {
        return $this->user->fakultas ?? null;
    }

    /**
     * Get prodi through user (akses melalui relasi user)
     */
    public function getProdiAttribute()
    {
        return $this->user->prodi ?? null;
    }

    /**
     * Get fakultas_id through user
     */
    public function getFakultasIdAttribute()
    {
        return $this->user->fakultas_id ?? null;
    }

    /**
     * Get prodi_id through user
     */
    public function getProdiIdAttribute()
    {
        return $this->user->prodi_id ?? null;
    }

    /**
     * Get the file URL.
     */
    public function getFileUrlAttribute()
    {
        if (!$this->file) return null;
        
        if (\Storage::disk('public')->exists($this->file)) {
            return \Storage::url($this->file);
        }
        
        return asset('storage/' . $this->file);
    }
}