<?php

namespace App\Livewire\Arsip;

use App\Models\Arsip;
use App\Models\Prodi;
use Livewire\Component;
use App\Models\Fakultas;
use Illuminate\Support\Str;
use App\Models\DataFakultas;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    use WithFileUploads;

    public $judul;
    public $deskripsi;
    public $fakultas_id;
    public $prodi_id;
    public $file;
    public $prodis = [];

    protected $rules = [
        'judul' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:5120',
    ];

    public function mount()
    {
        // Set default fakultas/prodi based on user role
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
    }

    public function loadProdis()
    {
        if ($this->fakultas_id) {
            $this->prodis = Prodi::where('fakultas_id', $this->fakultas_id)->get();
        } else {
            $this->prodis = [];
        }
    }

    public function updatedFakultasId()
    {
        $this->prodi_id = null;
        $this->loadProdis();
    }

    public function save()
    {
          $user = Auth::user();
        $this->validate();

        // Handle file upload
      $fileName = time().'_'.$this->file->getClientOriginalName();

    // ✅ SIMPAN KE storage/app/public/arsip
    $filePath = $this->file->storeAs('arsip', $fileName, 'public');
        // Create arsip
            $arsip = Arsip::create([
                'judul' => $this->judul,
                'deskripsi' => $this->deskripsi,
                'file' => $filePath, // atau $filePath tergantung struktur
                'user_id' => $user->id,
                'fakultas_id' => $this->fakultas_id,
                'prodi_id' => $this->prodi_id,
            ]);

            // Create DataFakultas record
            DataFakultas::create([
                'id_data_fakultas' => Str::uuid(),
                'arsip_id' => $arsip->id,
                'user_id' => $user->id,
                'fakultas_id'=> $user->fakultas_id,
                'prodi_id'    => $user->prodi_id,
                 'role_id'    => $user->role_id,
            ]);


        session()->flash('success', 'Arsip berhasil ditambahkan.');
        return redirect()->route('arsip.index');
    }

    public function render()
    {
        $fakultas = Fakultas::orderBy('nama_fakultas')->get();
      
        return view('livewire.arsip.create', [
            'fakultas' => $fakultas,
        ])->extends('layouts.app')->section('content');
    }
}