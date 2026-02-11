<?php

namespace App\Livewire\Arsip;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Arsip;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Edit extends Component
{
    use WithFileUploads, AuthorizesRequests;

    public Arsip $arsip;

    public $judul;
    public $deskripsi;
    public $file_baru;
    public $fakultas_id;
    public $prodi_id;

    public $fakultas = [];
    public $prodis = [];

    public function mount(Arsip $arsip)
    {
        $user = auth()->user();
 

        // ===== NILAI AWAL = DATA ARSIP (PENTING) =====
        $this->arsip       = $arsip;
        $this->judul       = $arsip->judul;
        $this->deskripsi   = $arsip->deskripsi;
        $this->fakultas_id = $arsip->fakultas_id;
        $this->prodi_id    = $arsip->prodi_id;

        // ===== ROLE BASED OPTION (BUKAN VALUE) =====

        // SUPERADMIN / ADMIN UNIV
        if ($user->isSuperAdmin() || $user->hasRole('admin_univ')) {
            $this->fakultas = Fakultas::all();
            $this->prodis = Prodi::where('fakultas_id', $this->fakultas_id)->get();
        }

        // ADMIN FAKULTAS
        elseif ($user->hasRole('admin_fakultas')) {
            $this->fakultas = Fakultas::where('id', $user->fakultas_id)->get();
            $this->prodis   = Prodi::where('fakultas_id', $user->fakultas_id)->get();
        }

        // ADMIN PRODI
        elseif ($user->hasRole('admin_prodi')) {
            $this->fakultas = Fakultas::where('id', $user->fakultas_id)->get();
            $this->prodis   = Prodi::where('id', $user->prodi_id)->get();
        }
    }

    public function updatedFakultasId($value)
    {
        if (auth()->user()->isSuperAdmin() || auth()->user()->hasRole('admin_univ')) {
            $this->prodi_id = null;
            $this->prodis = Prodi::where('fakultas_id', $value)->get();
        }
    }

   public function update()
{
    $user = auth()->user();

    // ===============================
    // VALIDASI DASAR
    // ===============================
    $this->validate([
        'judul'     => 'required|string',
        'file_baru' => 'nullable|file|max:10240',
    ]);

    // ===============================
    // PAKSA FAKULTAS & PRODI SESUAI ROLE
    // ===============================

    // ADMIN FAKULTAS
    if ($user->hasRole('admin_fakultas')) {
        $this->fakultas_id = $user->fakultas_id;
        $this->prodi_id    = null;
    }

    // ADMIN PRODI
    elseif ($user->hasRole('admin_prodi')) {
        $this->fakultas_id = null;
        $this->prodi_id    = $user->prodi_id;
    }

    // SUPERADMIN / ADMIN UNIV
    // (boleh pilih manual dari form, jadi tidak dipaksa)

    // ===============================
    // HAK UPLOAD FILE
    // ===============================
    $bolehUpload = in_array($user->role->name, [
        'superadmin',
        'admin_univ',
        'admin_fakultas',
        'admin_prodi',
    ]);

    if ($this->file_baru && $bolehUpload) {
        if ($this->arsip->file && Storage::disk('public')->exists($this->arsip->file)) {
            Storage::disk('public')->delete($this->arsip->file);
        }

        $this->arsip->file = $this->file_baru->store('arsip', 'public');
    }

    // ===============================
    // UPDATE DATA
    // ===============================
    $this->arsip->update([
        'judul'       => $this->judul,
        'deskripsi'   => $this->deskripsi,
        'fakultas_id' => $this->fakultas_id,
        'prodi_id'    => $this->prodi_id,
    ]);

    session()->flash('success', 'Arsip berhasil diperbarui');

    return redirect()->route('arsip.index');
}



    public function render()
    {
        return view('livewire.arsip.edit')
            ->layout('layouts.app');
    }
}
