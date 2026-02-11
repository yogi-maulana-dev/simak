<?php

namespace App\Livewire\DataArsip;

use Livewire\Component;
use App\Models\Arsip;
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
    ->leftJoin('prodi', 'arsip.prodi_id', '=', 'prodi.id')          // join prodi
    ->leftJoin('fakultas', 'arsip.fakultas_id', '=', 'fakultas.id') // join fakultas
    ->where('arsip.prodi_id', $user->prodi_id)                      // filter sesuai prodi user
    ->select('arsip.*', 'prodi.nama_prodi', 'fakultas.nama_fakultas')
    ->get();




        } else {
            $arsips = collect();
        }
       

//                         $user = auth()->user();
// dd($user->fakultas_id,$user->prodi_id,);


        return view('livewire.data-arsip.index', compact('arsips'))
               ->layout('layouts.app');
    }
}
