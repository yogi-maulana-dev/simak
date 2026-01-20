<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory, HasUuids;
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $table = 'prodi';
    protected $fillable = ['nama_prodi', 'fakultas_id'];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'prodi_id', 'id');
    }

    public function arsips()
    {
        return $this->hasManyThrough(
            Arsip::class,
            User::class,
            'prodi_id',
            'user_id',
            'id',
            'id'
        );
    }
}