<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DataProdis extends Model
{
    protected $table = 'data_prodis';
    
    protected $fillable = [
        'id_data_prodis', // Tambahkan ini
        'arsip_id',
        'user_id',
        'fakultas_id',
        'prodi_id',
        'role_id',
    ];

    public $timestamps = true;

    // Set primary key sebagai UUID
    protected $primaryKey = 'id_data_prodis';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Boot method untuk generate UUID otomatis
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

    /**
     * Get the arsip that owns the data fakultas.
     */
    public function arsip()
    {
        return $this->belongsTo(Arsip::class, 'arsip_id');
    }

    /**
     * Get the user that owns the data fakultas.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the fakultas that owns the data fakultas.
     */
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    /**
     * Get the role that owns the data fakultas.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}