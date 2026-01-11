<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;
    protected $fillable = [
        'judul',
        'file',
        'kategori_id',
        'fakultas_id',
        'prodi_id',
        'user_id'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // app/Models/Arsip.php
public function scopeVisibleFor($query, $user)
{
    if ($user->isSuperAdmin() || $user->hasRole('admin_univ')) {
        return $query;
    }

    if ($user->hasRole('admin_fakultas') || $user->hasRole('asesor_fakultas')) {
        return $query->where('fakultas_id', $user->fakultas_id);
    }

    if ($user->hasRole('admin_prodi') || $user->hasRole('asesor_prodi')) {
        return $query->where('prodi_id', $user->prodi_id);
    }

    return $query->whereRaw('1=0');
}

}
