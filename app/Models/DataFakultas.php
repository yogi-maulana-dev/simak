<?php

namespace App\Models;

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
}
