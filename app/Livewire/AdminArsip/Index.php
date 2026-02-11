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
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $selectedFakultas = null;
    public $selectedProdi = null;
    public $selectedUser = null;
    public $confirmingDelete = false;
    public $deleteId = null;
    public $deleteJudul = '';

    protected $paginationTheme = 'tailwind';

    // Listeners untuk refresh otomatis
    protected $listeners = ['refreshComponent' => '$refresh'];

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
                    $this->deleteFile($arsip->file);
                    $arsip->delete();
                    session()->flash('message', 'Arsip "' . $arsip->judul . '" berhasil dihapus.');
                } catch (\Exception $e) {
                    session()->flash('error', 'Gagal menghapus arsip: ' . $e->getMessage());
                    \Log::error('Delete arsip error: ' . $e->getMessage());
                }
            }
        }
        $this->reset(['confirmingDelete', 'deleteId', 'deleteJudul']);
    }

    private function deleteFile($filePath)
    {
        if (!$filePath) return;
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return;
        }
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
            return;
        }
        $publicPath = public_path($filePath);
        if (file_exists($publicPath)) {
            unlink($publicPath);
            return;
        }
        $storagePath = storage_path('app/public/' . $filePath);
        if (file_exists($storagePath)) {
            unlink($storagePath);
        }
    }

    // Gunakan updated instead of updating untuk kompatibilitas lebih baik
    public function updated($propertyName)
    {
        if ($propertyName === 'search') {
            $this->resetPage();
        }
        if ($propertyName === 'selectedFakultas') {
            $this->selectedProdi = null;
            $this->resetPage();
        }
        if (in_array($propertyName, ['selectedProdi', 'selectedUser', 'perPage'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        // Build query step by step
        $query = Arsip::query();

        // Eager load relationships untuk performa
        $query->with([
            'user:id,name,email,fakultas_id,prodi_id',
            'user.fakultas:id,nama_fakultas',
            'user.prodi:id,nama_prodi',
            'dataFakultas',
            'dataFakultas.fakultas:id,nama_fakultas'
        ]);

        // PENCARIAN - Gunakan trim untuk menghindari spasi kosong
        if (!empty(trim($this->search))) {
            $searchTerm = trim($this->search);
            
            $query->where(function ($q) use ($searchTerm) {
                // Pencarian di judul arsip
                $q->where('judul', 'LIKE', '%' . $searchTerm . '%');
                
                // Pencarian di deskripsi arsip
                $q->orWhere('deskripsi', 'LIKE', '%' . $searchTerm . '%');
                
                // Pencarian di user (nama dan email)
                $q->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'LIKE', '%' . $searchTerm . '%')
                              ->orWhere('email', 'LIKE', '%' . $searchTerm . '%');
                });
                
                // Pencarian di fakultas (dari relasi user)
                $q->orWhereHas('user.fakultas', function ($fakultasQuery) use ($searchTerm) {
                    $fakultasQuery->where('nama_fakultas', 'LIKE', '%' . $searchTerm . '%');
                });
                
                // Pencarian di prodi (dari relasi user)
                $q->orWhereHas('user.prodi', function ($prodiQuery) use ($searchTerm) {
                    $prodiQuery->where('nama_prodi', 'LIKE', '%' . $searchTerm . '%');
                });
                
                // Pencarian di fakultas (dari dataFakultas)
                $q->orWhereHas('dataFakultas.fakultas', function ($fakultasQuery) use ($searchTerm) {
                    $fakultasQuery->where('nama_fakultas', 'LIKE', '%' . $searchTerm . '%');
                });
            });
        }

        // Filter Fakultas
        if (!empty($this->selectedFakultas)) {
            $query->whereHas('dataFakultas', function ($q) {
                $q->where('fakultas_id', $this->selectedFakultas);
            });
        }

        // Filter Prodi
        if (!empty($this->selectedProdi)) {
            $query->whereHas('user', function ($q) {
                $q->where('prodi_id', $this->selectedProdi);
            });
        }

        // Filter User
        if (!empty($this->selectedUser)) {
            $query->where('user_id', $this->selectedUser);
        }

        // Get paginated results
        $arsips = $query->latest('created_at')->paginate($this->perPage);

        // Data untuk dropdown
        $fakultasList = Fakultas::orderBy('nama_fakultas')->get();
        
        $prodiList = $this->selectedFakultas 
            ? Prodi::where('fakultas_id', $this->selectedFakultas)->orderBy('nama_prodi')->get()
            : collect([]);

        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('livewire.admin-arsip.index', [
            'arsips' => $arsips,
            'fakultasList' => $fakultasList,
            'prodiList' => $prodiList,
            'users' => $users,
           ])->layout('layouts.app');
    }
}