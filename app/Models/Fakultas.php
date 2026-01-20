<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory, HasUuids;
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = ['nama_fakultas'];

    public function prodis()
    {
        return $this->hasMany(Prodi::class, 'fakultas_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'fakultas_id', 'id');
    }

    public function dataFakultas()
    {
        return $this->hasMany(DataFakultas::class, 'fakultas_id', 'id');
    }
}