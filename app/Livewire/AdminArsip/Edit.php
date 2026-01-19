<?php

namespace App\Livewire\AdminArsip;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Arsip;
use App\Models\Fakultas;
use App\Models\User;
use App\Models\DataFakultas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Edit extends Component
{
    use WithFileUploads;

    public $arsipId;
    public $judul = '';
    public $deskripsi = '';
    public $user_id = '';
    public $fakultas_ids = [];
    public $file;
    public $oldFile;
    public $is_public = false;
    
    public $currentArsip = null;
    public $selectedUser = null;
    public $autoFillFakultas = true; // Flag untuk auto-fill

    protected $rules = [
        'judul' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'user_id' => 'required|exists:users,id',
        'fakultas_ids' => 'required|array|min:1',
        'fakultas_ids.*' => 'exists:fakultas,id',
        'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
        'is_public' => 'boolean',
    ];

    public function mount($arsip)
    {
        if (auth()->user()->role->name !== 'superadmin') {
            abort(403, 'Akses ditolak. Hanya superadmin yang dapat mengakses halaman ini.');
        }
        
        $this->arsipId = $arsip;
        $this->loadArsipData();
    }

    private function loadArsipData()
    {
        $this->currentArsip = Arsip::with(['fakultas', 'user'])->findOrFail($this->arsipId);
        
        $this->judul = $this->currentArsip->judul;
        $this->deskripsi = $this->currentArsip->deskripsi;
        $this->user_id = $this->currentArsip->user_id;
        $this->fakultas_ids = $this->currentArsip->fakultas->pluck('id')->toArray();
        $this->oldFile = $this->currentArsip->file;
        $this->is_public = $this->currentArsip->is_public;
        
        $this->selectedUser = User::with(['fakultas', 'prodi'])->find($this->user_id);
    }

    // Method ini akan dipanggil OTOMATIS ketika user_id berubah
    public function updatedUserId($value)
    {
        $this->selectedUser = $value ? User::with(['fakultas', 'prodi'])->find($value) : null;
        
        // Jika auto-fill aktif dan user memiliki fakultas, update fakultas_ids
        if ($this->autoFillFakultas && $this->selectedUser && $this->selectedUser->fakultas_id) {
            $this->fakultas_ids = [$this->selectedUser->fakultas_id];
        }
    }

    // Method untuk toggle auto-fill
    public function toggleAutoFill()
    {
        $this->autoFillFakultas = !$this->autoFillFakultas;
        
        // Jika diaktifkan kembali, update fakultas_ids sesuai user
        if ($this->autoFillFakultas && $this->selectedUser && $this->selectedUser->fakultas_id) {
            $this->fakultas_ids = [$this->selectedUser->fakultas_id];
        }
    }

    public function update()
    {
        $this->validate();

        try {
            // Handle file
            $filePath = $this->oldFile;
            if ($this->file) {
                // Delete old file
                if ($this->oldFile && Storage::disk('public')->exists($this->oldFile)) {
                    Storage::disk('public')->delete($this->oldFile);
                }
                
                // Save new file
                $fileName = time() . '_' . $this->file->getClientOriginalName();
                $filePath = $this->file->storeAs('arsip', $fileName, 'public');
            }
            
            // Update arsip
            $this->currentArsip->update([
                'judul' => $this->judul,
                'deskripsi' => $this->deskripsi,
                'user_id' => $this->user_id,
                'file' => $filePath,
                'is_public' => $this->is_public,
                'slug' => Str::slug($this->judul) . '-' . time(),
            ]);

            // Sync fakultas
            $this->syncFakultas($this->fakultas_ids);

            session()->flash('success', 'Arsip berhasil diperbarui!');
            return redirect()->route('admin.arsip.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui arsip: ' . $e->getMessage());
            \Log::error('Error update arsip: ' . $e->getMessage());
        }
    }

    private function syncFakultas(array $fakultasIds)
    {
        // Hapus semua relasi lama
         DataFakultas::where('arsip_id', $this->currentArsip->id)->delete();
    
    // Ambil user ID yang sudah diupdate
    $userId = $this->user_id; // Gunakan property yang sudah diupdate
        
        // // Tambahkan yang baru
        // foreach ($fakultasIds as $fakultasId) {
        //     DataFakultas::create([
        //         'arsip_id' => $this->currentArsip->id,
        //         'user_id' => $this->user_id,
        //         'fakultas_id' => $fakultasId,
        //         'role_id' => auth()->user()->role_id,
        //     ]);
        // }
    }

    public function render()
    {
        return view('livewire.admin-arsip.edit', [
            'fakultas' => Fakultas::orderBy('nama_fakultas')->get(),
            'users' => User::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}