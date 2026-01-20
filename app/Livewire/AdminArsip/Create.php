<?php

namespace App\Livewire\AdminArsip;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Arsip;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\User;
use App\Models\DataFakultas; // Tambahkan ini
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
    ];

    public function mount()
    {
        if (auth()->user()->role->name !== 'superadmin') {
            abort(403, 'Akses ditolak. Hanya superadmin yang dapat mengakses halaman ini.');
        }
        
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
            $user = User::with('fakultas', 'prodi')->find($userId);
            
            if ($user && $user->fakultas_id) {
                // Set fakultas_id sesuai user
                $this->fakultas_id = $user->fakultas_id;
                
                // Load prodi dari fakultas tersebut
                $this->prodiOptions = Prodi::where('fakultas_id', $user->fakultas_id)->get();
                
                // Set prodi_id jika user punya prodi dan prodi tersebut ada di fakultas
                if ($user->prodi_id) {
                    $prodiExists = $this->prodiOptions->contains('id', $user->prodi_id);
                    $this->prodi_id = $prodiExists ? $user->prodi_id : '';
                } else {
                    $this->prodi_id = '';
                }
            } else {
                // Reset jika user tidak punya fakultas
                $this->fakultas_id = '';
                $this->prodi_id = '';
                $this->prodiOptions = [];
            }
        } else {
            $this->fakultas_id = '';
            $this->prodi_id = '';
            $this->prodiOptions = [];
        }
    }

    public function save()
{
    $this->validate();

    try {
        // Pastikan prodi_id null jika kosong
        $this->prodi_id = $this->prodi_id ?: null;
        
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
            'prodi_id' => $this->prodi_id,
            'user_id' => $this->user_id, // User yang dipilih (uploader)
            'file' => $filePath,
        ]);

        // 6. Simpan ke tabel yang sesuai berdasarkan role user yang dipilih
        $roleId = $uploadedUser->role_id;
        
        if ($roleId == 3) {
            // Jika user yang dipilih adalah admin_fakultas -> simpan ke DataFakultas
            DataFakultas::create([
                'id_data_fakultas' => Str::uuid(),
                'arsip_id' => $arsip->id,
                'user_id' => $this->user_id,
                'fakultas_id' => $this->fakultas_id,
                'role_id' => $roleId,
            ]);
        } elseif ($roleId == 4) {
            // Jika user yang dipilih adalah admin_prodi -> simpan ke DataProdi
            // Pastikan sudah import model DataProdi di atas
            // use App\Models\DataProdi;
            DataProdi::create([
                'id_data_prodi' => Str::uuid(),
                'arsip_id' => $arsip->id,
                'user_id' => $this->user_id,
                'fakultas_id' => $this->fakultas_id,
                'prodi_id' => $this->prodi_id,
                'role_id' => $roleId,
            ]);
        } elseif ($roleId == 2) {
            // Jika user yang dipilih adalah admin_univ
            // Bisa disimpan ke DataFakultas atau buat tabel khusus DataUniversitas
            DataFakultas::create([
                'id_data_fakultas' => Str::uuid(),
                'arsip_id' => $arsip->id,
                'user_id' => $this->user_id,
                'fakultas_id' => $this->fakultas_id,
                'role_id' => $roleId,
            ]);
        } elseif ($roleId == 1) {
            // Jika user yang dipilih adalah superadmin
            // Bisa disimpan ke DataFakultas atau buat tabel khusus
            DataFakultas::create([
                'id_data_fakultas' => Str::uuid(),
                'arsip_id' => $arsip->id,
                'user_id' => $this->user_id,
                'fakultas_id' => $this->fakultas_id,
                'role_id' => $roleId,
            ]);
        } else {
            // Untuk role lain, default ke DataFakultas
            DataFakultas::create([
                'id_data_fakultas' => Str::uuid(),
                'arsip_id' => $arsip->id,
                'user_id' => $this->user_id,
                'fakultas_id' => $this->fakultas_id,
                'prodi_id' => $this->prodi_id,
                'role_id' => $roleId,
            ]);
        }

        session()->flash('success', 'Arsip berhasil dibuat!');
        return redirect()->route('admin.arsip.index');
        
    } catch (\Exception $e) {
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
            $this->prodi_id = '';
        } else {
            $this->prodiOptions = [];
            $this->prodi_id = '';
        }
    }

    public function render()
    {
        $selectedUser = $this->user_id ? User::with(['fakultas', 'prodi'])->find($this->user_id) : null;
        
        return view('livewire.admin-arsip.create', [
            'fakultas' => Fakultas::orderBy('nama_fakultas')->get(),
            'users' => User::orderBy('name')->get(),
            'selectedUser' => $selectedUser,
        ])->layout('layouts.app');
    }
}