<?php

namespace App\Livewire\DataArsip;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public function render()
    {
        $user = auth()->user();

        // ambil nama role
        $role = is_object($user->role)
            ? $user->role->name
            : $user->role;

        if ($role === 'asesor_prodi') {

   $arsips = DB::table('arsip')
    ->leftJoin('fakultas', 'arsip.fakultas_id', '=', 'fakultas.id')
    ->leftJoin('prodi', 'arsip.prodi_id', '=', 'prodi.id')
    ->where('arsip.user_id', $user->id)
    ->select('arsip.*', 'fakultas.nama_fakultas as nama_fakultas', 'prodi.nama_prodi as nama_prodi')
    ->get();



        } else {
            $arsips = collect();
        }

        return view('livewire.data-arsip.index', compact('arsips'))
            ->layout('layouts.app');
    }
}
