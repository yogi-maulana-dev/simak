<?php

namespace App\Livewire\AdminArsip;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Arsip;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    use WithFileUploads;

    public $judul = '';
    public $deskripsi = '';
    public $fakultas_id = '';
    public $prodi_id = '';
    public $user_id = '';
    public $file;
    public $thumbnail;
    public $is_public = false;

    public $prodiOptions = [];

    protected $rules = [
        'judul' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'fakultas_id' => 'required|exists:fakultas,id',
        'prodi_id' => 'nullable|exists:prodi,id',
        'user_id' => 'required|exists:users,id',
        'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
        'thumbnail' => 'nullable|image|max:2048',
        'is_public' => 'boolean',
    ];

    public function mount()
    {
        if (auth()->user()->role->name !== 'superadmin') {
            abort(403, 'Akses ditolak. Hanya superadmin yang dapat mengakses halaman ini.');
        }
        
        $this->user_id = Auth::id();
    }

    public function updatedFakultasId($value)
    {
        if ($value) {
            $this->prodiOptions = Prodi::where('fakultas_id', $value)->get();
        } else {
            $this->prodiOptions = [];
        }
        $this->prodi_id = '';
    }

    public function save()
    {
        $this->validate();

        try {
            $fileName = $this->file->store('arsip/files', 'public');
            
            $thumbnailName = null;
            if ($this->thumbnail) {
                $thumbnailName = $this->thumbnail->store('arsip/thumbnails', 'public');
            }

            Arsip::create([
                'judul' => $this->judul,
                'deskripsi' => $this->deskripsi,
                'fakultas_id' => $this->fakultas_id,
                'prodi_id' => $this->prodi_id,
                'user_id' => $this->user_id,
                'file' => $fileName,
                'thumbnail' => $thumbnailName,
                'is_public' => $this->is_public,
                'slug' => Str::slug($this->judul) . '-' . time(),
            ]);

            session()->flash('success', 'Arsip berhasil dibuat!');
            return redirect()->route('admin.arsip.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat arsip: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin-arsip.create', [
            'fakultas' => Fakultas::orderBy('nama_fakultas')->get(),
            'users' => User::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}