<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;
          protected $table = 'prodi';

    protected $fillable = ['nama_prodi', 'fakultas_id'];

 // Relasi ke fakultas
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    // Relasi ke users yang memiliki prodi ini
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relasi ke arsip melalui user
    public function arsips()
    {
        return $this->hasManyThrough(
            Arsip::class,
            User::class,
            'prodi_id', // Foreign key on users table
            'user_id',  // Foreign key on arsip table
            'id',       // Local key on prodi table
            'id'        // Local key on users table
        );
    }
}
