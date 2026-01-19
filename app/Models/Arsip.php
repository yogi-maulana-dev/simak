<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Arsip extends Model
{
    protected $table = 'arsip';

    protected $fillable = [
        'judul',
        'deskripsi',
        'file',
        'user_id',
    ];



    /**
     * Relasi ke user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi many-to-many ke fakultas melalui tabel pivot data_fakultas
     */
    public function fakultas(): BelongsToMany
    {
        return $this->belongsToMany(
            Fakultas::class,
            'data_fakultas', // Nama tabel pivot
            'arsip_id',      // Foreign key di tabel pivot untuk Arsip
            'fakultas_id'    // Foreign key di tabel pivot untuk Fakultas
        );
    }

    /**
     * Relasi langsung ke tabel pivot
     */
    public function dataFakultas(): HasMany
    {
        return $this->hasMany(DataFakultas::class, 'arsip_id', 'id');
    }

    /**
     * Get fakultas pertama (gunakan eager loading untuk menghindari N+1)
     */
    public function getFirstFakultasAttribute()
    {
        // Gunakan relasi yang sudah di-load atau load jika belum
        if ($this->relationLoaded('fakultas')) {
            return $this->fakultas->first();
        }
        
        return $this->fakultas()->first();
    }

    /**
     * Get fakultas_id pertama (tanpa circular reference)
     */
    public function getFakultasIdAttribute()
    {
        if ($this->relationLoaded('fakultas')) {
            return $this->fakultas->first()->id ?? null;
        }
        
        $fakultas = $this->fakultas()->first();
        return $fakultas ? $fakultas->id : null;
    }

    /**
     * Get nama fakultas pertama (untuk display)
     */
    public function getNamaFakultasAttribute()
    {
        if ($this->relationLoaded('fakultas')) {
            return $this->fakultas->first()->nama_fakultas ?? null;
        }
        
        $fakultas = $this->fakultas()->first();
        return $fakultas ? $fakultas->nama_fakultas : null;
    }

    /**
     * Get prodi dari user
     */
    public function getProdiAttribute()
    {
        if ($this->relationLoaded('user')) {
            return $this->user->prodi ?? null;
        }
        
        return $this->user()->with('prodi')->first()->prodi ?? null;
    }

    /**
     * Get prodi_id dari user
     */
    public function getProdiIdAttribute()
    {
        if ($this->relationLoaded('user')) {
            return $this->user->prodi_id ?? null;
        }
        
        return $this->user()->first()->prodi_id ?? null;
    }

    /**
     * Get nama prodi (untuk display)
     */
    public function getNamaProdiAttribute()
    {
        $prodi = $this->prodi;
        return $prodi ? $prodi->nama_prodi : null;
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
     * Attach fakultas ke arsip
     */
    public function attachFakultas($fakultasId, $roleId = null)
    {
        $existing = $this->dataFakultas()
            ->where('fakultas_id', $fakultasId)
            ->first();
            
        if (!$existing) {
            DataFakultas::create([
                'arsip_id' => $this->id,
                'user_id' => $this->user_id,
                'fakultas_id' => $fakultasId,
                'role_id' => $roleId,
            ]);
        }
    }

    /**
     * Sync fakultas
     */
// Di dalam method syncFakultas di Model Arsip
public function syncFakultas(array $fakultasIds, $roleId = null)
{
    \Log::info('Syncing fakultas for arsip ID: ' . $this->id);
    \Log::info('Fakultas IDs to sync: ', $fakultasIds);
    
    // Hapus semua relasi lama
    $deleted = $this->dataFakultas()->delete();
    \Log::info('Deleted ' . $deleted . ' old relations');
    
    // Tambahkan yang baru
    foreach ($fakultasIds as $fakultasId) {
        DataFakultas::create([
            'arsip_id' => $this->id,
            'user_id' => $this->user_id,
            'fakultas_id' => $fakultasId,
            'role_id' => $roleId ?? auth()->user()->role_id,
        ]);
        \Log::info('Created relation for fakultas ID: ' . $fakultasId);
    }
}

    /**
     * Scope untuk eager loading relasi yang sering digunakan
     */
    public function scopeWithRelations($query)
    {
        return $query->with(['user', 'fakultas', 'user.fakultas', 'user.prodi']);
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

        // 👁️ ASESOR / ADMIN FAKULTAS → lihat sesuai fakultas
        if (in_array($user->role->name, ['asesor', 'asesor_fakultas', 'admin_fakultas'])) {
            return $query->whereHas('fakultas', function ($q) use ($user) {
                $q->where('fakultas.id', $user->fakultas_id);
            });
        }

        // ADMIN PRODI / ASESOR PRODI → lihat sesuai prodi
        if (in_array($user->role->name, ['admin_prodi', 'asesor_prodi'])) {
            return $query->whereHas('user', function ($q) use ($user) {
                $q->where('prodi_id', $user->prodi_id);
            });
        }

        // 👤 USER / lainnya → hanya arsip sendiri
        return $query->where('user_id', $user->id);
    }
}