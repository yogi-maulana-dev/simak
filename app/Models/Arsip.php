<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Arsip extends Model
{
    protected $table = 'arsip';
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'judul',
        'deskripsi',
        'file',
        'user_id',
        'fakultas_id',
        'prodi_id',
        'is_public',
    ];

    /**
     * Relasi ke user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke fakultas (langsung dari kolom fakultas_id)
     */
public function fakultas(): BelongsTo
{
    return $this->belongsTo(Fakultas::class, 'fakultas_id');
}

public function prodi(): BelongsTo
{
    return $this->belongsTo(Prodi::class, 'prodi_id');
}

 public function dataFakultas()
    {
        return $this->hasMany(DataFakultas::class, 'arsip_id', 'id');
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

    /**
     * Scope filter arsip sesuai role user
     */
    public function scopeVisibleFor(Builder $query, $user)
    {
        if ($user->role->name === 'superadmin') {
            return $query;
        }

        if (in_array($user->role->name, ['asesor', 'asesor_fakultas', 'admin_fakultas'])) {
            return $query->where('fakultas_id', $user->fakultas_id);
        }

        if (in_array($user->role->name, ['admin_prodi', 'asesor_prodi'])) {
            return $query->where('prodi_id', $user->prodi_id);
        }

        return $query->where('user_id', $user->id);
    }
}