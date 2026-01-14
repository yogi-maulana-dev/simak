<?php

namespace App\Models;

use App\Models\User;
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

    public function dataFakultas()
    {
        return $this->hasMany(DataFakultas::class, 'arsip_id', 'id');
    }

    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
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
        if ($user->role->name === 'asesor') {
            return $query->whereHas('dataFakultas', function ($q) use ($user) {
                $q->where('fakultas_id', $user->fakultas_id);
            });
        }

        // 👤 USER / ADMIN → hanya arsip sendiri
        return $query->where('user_id', $user->id);
    }


    public function getFakultasIdAttribute()
    {
        $dataFakultas = $this->dataFakultas->first();
        return $dataFakultas ? $dataFakultas->fakultas_id : null;
    }
    
    /**
     * Get prodi_id (jika ada relasi serupa)
     */
    public function getProdiIdAttribute()
    {
        // Sesuaikan dengan struktur Anda
        return null;
    }

    
}
