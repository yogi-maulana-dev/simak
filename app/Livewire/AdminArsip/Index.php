<?php

namespace App\Livewire\AdminArsip;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Arsip;
use App\Models\User;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;
    public ?int $selectedFakultas = null;
    public ?int $selectedProdi = null;
    public ?int $selectedUser = null;
    
    public bool $confirmingDelete = false;
    public ?int $deleteId = null;
    public string $deleteJudul = '';

    public function mount()
    {
        if (!Auth::user()->isSuperadmin()) {
            abort(403, 'Hanya Superadmin yang dapat mengakses halaman ini.');
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'selectedFakultas', 'selectedProdi', 'selectedUser']);
        $this->resetPage();
    }

    public function resetSort()
    {
        $this->resetPage();
    }

    public function confirmDelete($id, $judul)
    {
        $this->confirmingDelete = true;
        $this->deleteId = $id;
        $this->deleteJudul = $judul;
    }

public function delete()
{
    if ($this->deleteId) {
        $arsip = Arsip::find($this->deleteId);
        
        if ($arsip) {
            try {
                // Hapus file dari berbagai kemungkinan lokasi
                $this->deleteFile($arsip->file);
                
                // Hapus dari database
                $arsip->delete();
                
                session()->flash('success', 'Arsip "' . $arsip->judul . '" berhasil dihapus.');
                
            } catch (\Exception $e) {
                session()->flash('error', 'Gagal menghapus arsip: ' . $e->getMessage());
                \Log::error('Delete arsip error: ' . $e->getMessage());
            }
        }
    }
    
    $this->confirmingDelete = false;
    $this->deleteId = null;
    $this->deleteJudul = '';
}

private function deleteFile($filePath)
{
    if (!$filePath) return;
    
    // Coba hapus dari storage disk 'public'
    if (Storage::disk('public')->exists($filePath)) {
        Storage::disk('public')->delete($filePath);
        return;
    }
    
    // Coba hapus dengan path lengkap
    if (Storage::exists($filePath)) {
        Storage::delete($filePath);
        return;
    }
    
    // Coba hapus dari public folder
    $publicPath = public_path($filePath);
    if (file_exists($publicPath)) {
        unlink($publicPath);
        return;
    }
    
    // Coba hapus dengan path storage/app/public
    $storagePath = storage_path('app/public/' . $filePath);
    if (file_exists($storagePath)) {
        unlink($storagePath);
        return;
    }
}

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedFakultas()
    {
        $this->reset('selectedProdi');
        $this->resetPage();
    }

    public function render()
    {
        // Gunakan withRelations scope yang sudah ada di model Arsip
        $query = Arsip::withRelations();

        // Filter pencarian
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('fakultas', function ($q2) {
                            $q2->where('nama_fakultas', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('prodi', function ($q2) {
                            $q2->where('nama_prodi', 'like', '%' . $this->search . '%');
                        });
                  })
                  ->orWhereHas('dataFakultas.fakultas', function ($q) {
                      $q->where('nama_fakultas', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filter fakultas (melalui dataFakultas)
        if ($this->selectedFakultas) {
            $query->whereHas('dataFakultas', function ($q) {
                $q->where('fakultas_id', $this->selectedFakultas);
            });
        }

        // Filter prodi (melalui user)
        if ($this->selectedProdi) {
            $query->whereHas('user', function ($q) {
                $q->where('prodi_id', $this->selectedProdi);
            });
        }

        // Filter user
        if ($this->selectedUser) {
            $query->where('user_id', $this->selectedUser);
        }

        $arsips = $query->latest()->paginate($this->perPage);

        return view('livewire.admin-arsip.index', [
            'arsips' => $arsips,
            'fakultasList' => Fakultas::orderBy('nama_fakultas')->get(),
            'prodiList' => $this->selectedFakultas 
                ? Prodi::where('fakultas_id', $this->selectedFakultas)->orderBy('nama_prodi')->get()
                : collect(),
            'users' => User::orderBy('name')->get(['id', 'name']),
        ])->layout('layouts.app');
    }
}