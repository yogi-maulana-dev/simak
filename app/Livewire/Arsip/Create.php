<?php

namespace App\Livewire\Arsip;

use App\Models\Arsip;
use App\Models\Prodi;
use Livewire\Component;
use App\Models\Fakultas;
use Illuminate\Support\Str;
use App\Models\DataFakultas;
use App\Models\DataProdis;
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

    $fileName = time().'_'.$this->file->getClientOriginalName();
    $filePath = $this->file->storeAs('arsip', $fileName, 'public');


    // ADMIN FAKULTAS
    if ($user->role_id === '33333333-3333-3333-3333-333333333333') {


    $arsip = Arsip::create([
        'judul'       => $this->judul,
        'deskripsi'   => $this->deskripsi,
        'file'        => $filePath,
        'user_id'     => $user->id,
        'fakultas_id' => $user->fakultas_id,
    ]);

        DataFakultas::create([
            'id_data_fakultas' => Str::uuid(),
            'arsip_id' => $arsip->id,
            'user_id' => $user->id,
            'fakultas_id' => $user->fakultas_id,
            'role_id' => $user->role_id,
        ]);
    }

    // ADMIN PRODI
    if ($user->role_id === '44444444-4444-4444-4444-444444444444') {

       $arsip = Arsip::create([
        'judul'       => $this->judul,
        'deskripsi'   => $this->deskripsi,
        'file'        => $filePath,
        'user_id'     => $user->id,
        'fakultas_id' => $user->fakultas_id,
        'prodi_id'    => $user->prodi_id,
    ]);

        DataProdis::create([
            'id_data_prodis' => Str::uuid(),
            'arsip_id' => $arsip->id,
            'user_id' => $user->id,
            'fakultas_id' => $user->fakultas_id,
            'prodi_id' => $user->prodi_id,
            'role_id' => $user->role_id,
        ]);
    }

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