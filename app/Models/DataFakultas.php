<?php

namespace App\Models;

use App\Models\Arsip;
use App\Models\Fakultas;
use Illuminate\Database\Eloquent\Model;

class DataFakultas extends Model
{
    protected $table = 'data_fakultas';
    public $timestamps = false;

    protected $fillable = [
        'id_data_fakultas',
        'arsip_id',
        'user_id',
        'fakultas_id',
        'role_id',
    ];

        public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }
}
