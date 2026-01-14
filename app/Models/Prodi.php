<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;
          protected $table = 'prodi';

    protected $fillable = ['nama_prodi', 'fakultas_id'];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function arsips()
    {
        return $this->hasMany(Arsip::class);
    }
}
