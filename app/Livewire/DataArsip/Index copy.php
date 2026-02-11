<?php

namespace App\Livewire\DataArsip;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use AuthorizesRequests;

    public function render()
    {
        $user = auth()->user();
        $role = $user->role->name; // asesor_prodi / asesor_fakultas

        $query = DB::table('arsip')
            ->join('data_fakultas', 'arsip.id', '=', 'data_fakultas.arsip_id')
            ->where('data_fakultas.user_id', $user->id);

        // 🔹 ASESOR PRODI
        if ($role === 'asesor_prodi') {
            $query
                // ->join('prodi', 'prodi.fakultas_id', '=', 'data_fakultas.fakultas_id')
                   ->join('prodi', 'prodi.fakultas_id', '=', 'data_fakultas.fakultas_id')
                ->select(
                    'arsip.*',
                    'prodi.nama_prodi'
                );
        }

        // 🔹 ASESOR FAKULTAS
        elseif ($role === 'asesor_fakultas') {
            $query
                ->join('fakultas', 'fakultas.id', '=', 'data_fakultas.fakultas_id')
                ->select(
                    'arsip.*',
                    'fakultas.nama_fakultas'
                );
        }

        return view('livewire.data-arsip.index', [
            'arsip' => $query->distinct()->get()
        ])->layout('layouts.app');
    }
}
