<?php

namespace App\Livewire\AdminArsip;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Arsip;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\User;
use App\Models\DataFakultas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Edit extends Component
{
    use WithFileUploads;

    public Arsip $arsip;
    
    // Properti untuk form
    public $judul = '';
    public $deskripsi = '';
    public $fakultas_id = ''; // Ini hanya untuk dropdown display
    public $prodi_id = '';
    public $user_id = '';
    public $file;
    public $old_file = '';
    public $is_public = false;
    
    public $prodiOptions = [];
    public $selectedUser = null; // Tambahkan untuk menyimpan data user yang dipilih

    protected $rules = [
        'judul' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'fakultas_id' => 'required|exists:fakultas,id',
        'prodi_id' => 'nullable|exists:prodi,id',
        'user_id' => 'required|exists:users,id',
        'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
        'is_public' => 'boolean',
    ];

    public function mount(Arsip $arsip)
    {
        if (auth()->user()->role->name !== 'superadmin') {
            abort(403, 'Akses ditolak. Hanya superadmin yang dapat mengakses halaman ini.');
        }
        
        $this->arsip = $arsip;
        
        // Set data dari arsip ke properti form
        $this->judul = $this->arsip->judul;
        $this->deskripsi = $this->arsip->deskripsi;
        $this->fakultas_id = $this->arsip->fakultas_id;
        $this->prodi_id = $this->arsip->prodi_id;
        $this->user_id = $this->arsip->user_id;
        $this->old_file = $this->arsip->file;
        $this->is_public = $this->arsip->is_public;
        
        // Load user data saat mount
        $this->loadUserData($this->user_id);
        
        // Load prodi options berdasarkan fakultas
        if ($this->fakultas_id) {
            $this->prodiOptions = Prodi::where('fakultas_id', $this->fakultas_id)->get();
        }
    }

    // Method untuk load data user
    private function loadUserData($userId)
    {
        if ($userId) {
            $this->selectedUser = User::with(['fakultas', 'prodi', 'role'])->find($userId);
            
            if ($this->selectedUser && $this->selectedUser->fakultas_id) {
                // Set fakultas_id dari user (bukan dari form)
                $this->fakultas_id = $this->selectedUser->fakultas_id;
                
                // Load prodi dari fakultas user
                $this->prodiOptions = Prodi::where('fakultas_id', $this->selectedUser->fakultas_id)->get();
                
                // Set prodi_id dari user jika ada
                if ($this->selectedUser->prodi_id) {
                    $prodiExists = $this->prodiOptions->contains('id', $this->selectedUser->prodi_id);
                    $this->prodi_id = $prodiExists ? $this->selectedUser->prodi_id : '';
                }
            }
        } else {
            $this->selectedUser = null;
            $this->fakultas_id = '';
            $this->prodi_id = '';
            $this->prodiOptions = [];
        }
    }

    // Otomatis dipanggil ketika user_id berubah
    public function updatedUserId($value)
    {
        $this->loadUserData($value);
    }

    // Otomatis dipanggil ketika fakultas_id berubah (untuk filter prodi saja)
    public function updatedFakultasId($value)
    {
        if ($value) {
            $this->prodiOptions = Prodi::where('fakultas_id', $value)->get();
            // Reset prodi_id jika tidak ada di fakultas baru
            if (!$this->prodiOptions->contains('id', $this->prodi_id)) {
                $this->prodi_id = '';
            }
        } else {
            $this->prodiOptions = [];
            $this->prodi_id = '';
        }
    }

    public function update()
    {
        $this->validate();

        try {
            // Pastikan user yang dipilih valid
            if (!$this->selectedUser) {
                throw new \Exception('User tidak valid atau tidak ditemukan.');
            }

            // PASTIKAN: Gunakan fakultas_id dan role_id dari user, bukan dari form
            $userFakultasId = $this->selectedUser->fakultas_id;
            $userRoleId = $this->selectedUser->role_id;
            
            if (!$userFakultasId) {
                throw new \Exception('User yang dipilih tidak memiliki fakultas.');
            }

            // Validasi: Prodi harus sesuai dengan fakultas user
            if ($this->prodi_id) {
                $prodi = Prodi::find($this->prodi_id);
                if (!$prodi || $prodi->fakultas_id != $userFakultasId) {
                    throw new \Exception('Program studi tidak valid untuk fakultas user.');
                }
            }

            // Pastikan prodi_id null jika kosong
            $this->prodi_id = $this->prodi_id ?: null;
            
            // 1. Handle file upload jika ada file baru
            $filePath = $this->old_file;
            if ($this->file) {
                // Hapus file lama jika ada
                if ($this->old_file && Storage::disk('public')->exists($this->old_file)) {
                    Storage::disk('public')->delete($this->old_file);
                }
                
                // Simpan file baru
                $originalName = $this->file->getClientOriginalName();
                $extension = $this->file->getClientOriginalExtension();
                $fileName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;
                $filePath = $this->file->storeAs('arsip', $fileName, 'public');
            }

            // 2. Update data arsip (gunakan fakultas dari USER)
            $this->arsip->update([
                'judul' => $this->judul,
                'deskripsi' => $this->deskripsi,
                'fakultas_id' => $userFakultasId, // PAKAI INI dari user
                'prodi_id' => $this->prodi_id,
                'user_id' => $this->user_id,
                'file' => $filePath,
                'is_public' => $this->is_public,
                'slug' => Str::slug($this->judul) . '-' . time(),
            ]);

            // 3. Update data_fakultas (PAKAI DATA DARI USER)
            // Hapus semua relasi lama
            $this->arsip->dataFakultas()->delete();
            
            // Buat relasi baru dengan data dari USER
            DataFakultas::create([
                'arsip_id' => $this->arsip->id,
                'user_id' => $this->user_id,
                'fakultas_id' => $userFakultasId, // Dari user
                'prodi_id' => $this->prodi_id,
                'role_id' => $userRoleId, // Dari user
            ]);

            session()->flash('success', 'Arsip berhasil diperbarui!');
            return redirect()->route('admin.arsip.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui arsip: ' . $e->getMessage());
            \Log::error('Error memperbarui arsip: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin-arsip.edit', [
            'fakultas' => Fakultas::orderBy('nama_fakultas')->get(),
            'users' => User::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}