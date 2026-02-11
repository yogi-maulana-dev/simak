<?php

namespace App\Livewire\AdminArsip;

use App\Models\User;
use App\Models\Arsip;
use App\Models\Prodi;
use Livewire\Component;
use App\Models\Fakultas;
use App\Models\DataProdis;
use Illuminate\Support\Str;
use App\Models\DataFakultas;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Create extends Component
{
    use WithFileUploads;

    public $judul = '';
    public $deskripsi = '';
    public $fakultas_id = '';
    public $prodi_id = '';
    public $user_id = '';
    public $file;
    public $is_public = false; // Tambahkan ini

    public $prodiOptions = [];

    protected $rules = [
        'judul' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'fakultas_id' => 'required|exists:fakultas,id',
        'prodi_id' => 'nullable|exists:prodi,id',
        'user_id' => 'required|exists:users,id',
        'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
    ];

    protected $messages = [
        'prodi_id.exists' => 'Program studi yang dipilih tidak valid.',
        'file.required' => 'File arsip wajib diupload.',
        'file.max' => 'Ukuran file maksimal 10MB.',
    ];

    public function mount()
    {
        if (!Auth::user()->isSuperadmin()) {
            abort(403, 'Hanya Superadmin yang dapat mengakses halaman ini.');
        }
        
        // Set default user to logged in user
        $this->user_id = Auth::id();
        $this->setUserData($this->user_id);
    }

    // Ketika user dipilih, set otomatis fakultas dan prodi
    public function updatedUserId($value)
    {
        $this->setUserData($value);
    }

    // Method untuk set data user
    private function setUserData($userId)
    {
        if ($userId) {
            $user = User::with(['fakultas', 'prodi'])->find($userId);
            
            if ($user) {
                // Set fakultas_id sesuai user jika ada
                if ($user->fakultas_id) {
                    $this->fakultas_id = $user->fakultas_id;
                    
                    // Load prodi dari fakultas tersebut
                    $this->prodiOptions = Prodi::where('fakultas_id', $user->fakultas_id)->get();
                    
                    // Set prodi_id jika user punya prodi dan prodi tersebut ada di fakultas
                    if ($user->prodi_id) {
                        $prodiExists = $this->prodiOptions->contains('id', $user->prodi_id);
                        $this->prodi_id = $prodiExists ? $user->prodi_id : '';
                    }
                } else {
                    // Jika user tidak punya fakultas, reset
                    $this->fakultas_id = '';
                    $this->prodi_id = '';
                    $this->prodiOptions = [];
                }
            }
        }
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Pastikan prodi_id null jika kosong
            $prodiId = !empty($this->prodi_id) ? $this->prodi_id : null;
            
            // 1. Simpan file dengan nama yang unik
            $originalName = $this->file->getClientOriginalName();
            $extension = $this->file->getClientOriginalExtension();
            $fileName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;
            
            // 2. Simpan ke storage
            $filePath = $this->file->storeAs('arsip', $fileName, 'public');
            
            // 3. Cek apakah file tersimpan
            if (!Storage::disk('public')->exists($filePath)) {
                throw new \Exception('Gagal menyimpan file ke storage.');
            }

            // 4. Dapatkan user data yang dipilih (uploader)
            $uploadedUser = User::find($this->user_id);
            if (!$uploadedUser) {
                throw new \Exception('User tidak ditemukan.');
            }

            // 5. Simpan data arsip ke database
            $arsip = Arsip::create([
                'judul' => $this->judul,
                'deskripsi' => $this->deskripsi,
                'fakultas_id' => $this->fakultas_id,
                'prodi_id' => $prodiId,
                'user_id' => $this->user_id,
                'file' => $filePath,
                'is_public' => $this->is_public,
            ]);

            // 6. Tentukan role dan simpan ke tabel yang sesuai
            $roleId = $uploadedUser->role_id ?? 0;
            
            // Default simpan ke DataFakultas
            DataFakultas::create([
                'arsip_id' => $arsip->id,
                'user_id' => $this->user_id,
                'fakultas_id' => $this->fakultas_id,
                'prodi_id' => $prodiId,
                'role_id' => $roleId,
            ]);

            DB::commit();

            session()->flash('success', 'Arsip berhasil dibuat!');
            return redirect()->route('admin.arsip.index');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus file jika ada error setelah upload
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            
            session()->flash('error', 'Gagal membuat arsip: ' . $e->getMessage());
            \Log::error('Error membuat arsip: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    public function updatedFakultasId($value)
    {
        if ($value) {
            $this->prodiOptions = Prodi::where('fakultas_id', $value)->get();
            // Reset prodi_id jika fakultas berubah
            $this->reset('prodi_id');
        } else {
            $this->prodiOptions = [];
            $this->reset('prodi_id');
        }
    }

    public function render()
    {
        $selectedUser = $this->user_id ? User::with(['fakultas', 'prodi'])->find($this->user_id) : null;
        
        return view('livewire.admin-arsip.create', [
            'fakultas' => Fakultas::orderBy('nama_fakultas')->get(),
            'users' => User::orderBy('name')->get(),
            'selectedUser' => $selectedUser,
        ])->layout('layouts.app', ['title' => 'Tambah Arsip']);
    }
}