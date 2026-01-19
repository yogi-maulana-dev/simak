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
    public $is_public = false;

    public $prodiOptions = [];

    protected $rules = [
        'judul' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'fakultas_id' => 'required|exists:fakultas,id',
        'prodi_id' => 'nullable',
        'user_id' => 'required|exists:users,id',
        'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
        'is_public' => 'boolean',
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
            $user = User::find($userId);
            
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
            
            // 3. Debug: Cek apakah file tersimpan
            if (!Storage::disk('public')->exists($filePath)) {
                throw new \Exception('Gagal menyimpan file ke storage.');
            }

            // 4. Simpan data ke database
            Arsip::create([
                'judul' => $this->judul,
                'deskripsi' => $this->deskripsi,
                'fakultas_id' => $this->fakultas_id,
                'prodi_id' => $this->prodi_id,
                'user_id' => $this->user_id,
                'file' => $filePath, // Simpan path relatif
                'is_public' => $this->is_public,
                'slug' => Str::slug($this->judul) . '-' . time(),
            ]);

                  DataFakultas::create([
                'id_data_fakultas' => Str::uuid(),
                'arsip_id' => $arsip->id,
                  'user_id' => $this->user_id,
                'fakultas_id'=> $this->fakultas_id,
                'prodi_id'    => $this->prodi_id,
                 'role_id'    => $user->role_id,
            ]);

            session()->flash('success', 'Arsip berhasil dibuat!');
            return redirect()->route('admin.arsip.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat arsip: ' . $e->getMessage());
            \Log::error('Error membuat arsip: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Untuk cek user yang sedang dipilih
        $selectedUser = $this->user_id ? User::with(['fakultas', 'prodi'])->find($this->user_id) : null;
        
        return view('livewire.admin-arsip.create', [
            'fakultas' => Fakultas::orderBy('nama_fakultas')->get(),
            'users' => User::orderBy('name')->get(),
            'selectedUser' => $selectedUser,
        ])->layout('layouts.app');
    }
}