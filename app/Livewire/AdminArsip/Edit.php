<?php

namespace App\Livewire\AdminArsip;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Arsip;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Edit extends Component
{
    use WithFileUploads;

    public Arsip $arsip;
    public $judul;
    public $deskripsi;
    public $fakultas_id;
    public $prodi_id;
    public $user_id;
    public $newFile;
    public $newThumbnail;
    public $is_public;
    public $prodiOptions = [];

    protected $rules = [
        'judul' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'fakultas_id' => 'required|exists:fakultas,id',
        'prodi_id' => 'nullable|exists:prodi,id',
        'user_id' => 'required|exists:users,id',
        'newFile' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip,rar|max:10240',
        'newThumbnail' => 'nullable|image|max:2048',
        'is_public' => 'boolean',
    ];

    public function mount(Arsip $arsip)
    {
        // Hanya superadmin yang bisa akses
        if (auth()->user()->role->name !== 'superadmin') {
            abort(403, 'Akses ditolak. Hanya superadmin yang dapat mengakses halaman ini.');
        }

        $this->arsip = $arsip;
        $this->judul = $arsip->judul;
        $this->deskripsi = $arsip->deskripsi;
        $this->fakultas_id = $arsip->fakultas_id;
        $this->prodi_id = $arsip->prodi_id;
        $this->user_id = $arsip->user_id;
        $this->is_public = $arsip->is_public;

        // Load prodi options if fakultas is set
        if ($this->fakultas_id) {
            $this->prodiOptions = Prodi::where('fakultas_id', $this->fakultas_id)->get();
        }
    }

    public function updatedFakultasId($value)
    {
        if ($value) {
            $this->prodiOptions = Prodi::where('fakultas_id', $value)->get();
        } else {
            $this->prodiOptions = [];
        }
        $this->prodi_id = $this->arsip->prodi_id;
    }

    public function update()
    {
        $this->validate();

        try {
            // Update file if new file is uploaded
            if ($this->newFile) {
                // Delete old file
                if ($this->arsip->file && Storage::disk('public')->exists($this->arsip->file)) {
                    Storage::disk('public')->delete($this->arsip->file);
                }
                
                // Store new file
                $this->arsip->file = $this->newFile->store('arsip/files', 'public');
            }

            // Update thumbnail if new thumbnail is uploaded
            if ($this->newThumbnail) {
                // Delete old thumbnail
                if ($this->arsip->thumbnail && Storage::disk('public')->exists($this->arsip->thumbnail)) {
                    Storage::disk('public')->delete($this->arsip->thumbnail);
                }
                
                // Store and resize new thumbnail
                $thumbnailName = $this->newThumbnail->store('arsip/thumbnails', 'public');
                $manager = new ImageManager(new Driver());
                $image = $manager->read(storage_path('app/public/' . $thumbnailName));
                $image->scale(width: 300);
                $image->save(storage_path('app/public/' . $thumbnailName));
                
                $this->arsip->thumbnail = $thumbnailName;
            }

            // Update arsip data
            $this->arsip->update([
                'judul' => $this->judul,
                'deskripsi' => $this->deskripsi,
                'fakultas_id' => $this->fakultas_id,
                'prodi_id' => $this->prodi_id,
                'user_id' => $this->user_id,
                'is_public' => $this->is_public,
            ]);

            session()->flash('success', 'Arsip berhasil diperbarui!');
            return redirect()->route('admin.arsip.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui arsip: ' . $e->getMessage());
        }
    }

    public function getFakultasProperty()
    {
        return Fakultas::orderBy('nama_fakultas')->get();
    }

    public function getUsersProperty()
    {
        return User::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.admin-arsip.edit', [
            'fakultas' => $this->fakultas,
            'users' => $this->users,
        ])->layout('layouts.app');
    }
}