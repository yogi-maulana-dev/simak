<?php

namespace App\Livewire\Arsip;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Arsip;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;

    public Arsip $arsip;

    public $judul, $deskripsi;
    public $file_baru;
    public $fakultas_id, $prodi_id;

    public $fakultas = [];
    public $prodis = [];

    public function mount(Arsip $arsip)
    {
        $user = auth()->user();
        
      if ($user->hasRole('fakultas')) {
            $this->fakultas_id = $user->fakultas_id;
            $this->loadProdis();
        }
        
        if ($user->hasRole('prodi')) {
            $this->fakultas_id = $user->fakultas_id;
            $this->prodi_id = $user->prodi_id;
            $this->loadProdis();
        }

        // assign data
        $this->arsip = $arsip;
        $this->judul = $arsip->judul;
        $this->deskripsi = $arsip->deskripsi;
        $this->fakultas_id = $arsip->fakultas_id;
        $this->prodi_id = $arsip->prodi_id;

        // SUPERADMIN / ADMIN UNIV
        if ($user->isSuperAdmin() || $user->hasRole('admin_univ')) {
            $this->fakultas = Fakultas::all();
            $this->prodis = Prodi::where('fakultas_id', $this->fakultas_id)->get();
        }

        // ADMIN FAKULTAS
        if ($user->hasRole('admin_fakultas')) {
            $this->fakultas = Fakultas::where('id', $user->fakultas_id)->get();
            $this->prodis = Prodi::where('fakultas_id', $user->fakultas_id)->get();
        }

        // ADMIN PRODI
        if ($user->hasRole('admin_prodi')) {
            $this->fakultas = Fakultas::where('id', $user->fakultas_id)->get();
            $this->prodis = Prodi::where('id', $user->prodi_id)->get();
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
        $this->validate([
            'judul' => 'required',
            'fakultas_id' => 'required',
            'prodi_id' => 'nullable',
            'file_baru' => 'nullable|file|max:10240',
        ]);

        // jika upload file baru
        if ($this->file_baru) {
            if ($this->arsip->file && Storage::disk('public')->exists($this->arsip->file)) {
                Storage::disk('public')->delete($this->arsip->file);
            }

            $this->arsip->file = $this->file_baru->store('arsip', 'public');
        }

        $this->arsip->update([
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'fakultas_id' => $this->fakultas_id,
            'prodi_id' => $this->prodi_id,
        ]);

        return redirect()->route('arsip.index');
    }

    public function render()
    {
        return view('livewire.arsip.edit')
            ->layout('layouts.app');
    }
}
