<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;
     protected $fillable = ['nama_fakultas'];

 public function prodis()
    {
        return $this->hasMany(Prodi::class);
    }

    // Relasi ke users yang memiliki fakultas ini
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relasi ke DataFakultas (tabel pivot arsip-fakultas)
    public function dataFakultas()
    {
        return $this->hasMany(DataFakultas::class);
    }
}
