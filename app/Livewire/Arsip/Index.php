<?php

namespace App\Livewire\Arsip;

use App\Models\Arsip;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    public string $search = '';
    public int $perPage = 10;
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // Delete state
    public bool $confirmingDelete = false;
    public ?string $deleteId = null;

    public ?string $deleteJudul = null;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'q'],
        'perPage' => ['except' => 10, 'as' => 'limit'],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    /**
     * Reset page when searching
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Sort data
     */
    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        
        $this->resetPage();
    }

    /**
     * Reset sorting
     */
    public function resetSort(): void
    {
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }

    /**
     * Confirm delete with additional info
     */
public function confirmDelete(string $id): void
{
    Log::info('confirmDelete', [
        'id' => $id,
        'type' => gettype($id),
    ]);

    $arsip = Arsip::find($id);

    if (! $arsip) {
        session()->flash('error', 'Arsip tidak ditemukan');
        return;
    }

    if (! auth()->user()->can('delete', $arsip)) {
        session()->flash('error', 'Anda tidak punya izin');
        return;
    }

    $this->deleteId = $id;
    $this->deleteJudul = $arsip->judul;
    $this->confirmingDelete = true;
}



    /**
     * Execute delete
     */
    public function delete(): void
    {
        try {
            if (!$this->deleteId) {
                throw new \Exception('ID arsip tidak valid');
            }

            Log::info('Deleting arsip:', ['id' => $this->deleteId]);
            
            $arsip = Arsip::find($this->deleteId);
            
            if (!$arsip) {
                throw new \Exception('Arsip tidak ditemukan');
            }

            // Double authorization check
            if (!auth()->user()->can('delete', $arsip)) {
                throw new \Exception('Anda tidak memiliki izin untuk menghapus arsip ini');
            }

            // Delete physical file if exists
            if ($arsip->file) {
                $filePath = str_replace('/storage/', '', $arsip->file);
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                    Log::info('File deleted:', ['path' => $filePath]);
                }
            }

            $judul = $arsip->judul;
            $arsip->delete();

            Log::info('Arsip deleted successfully:', ['id' => $this->deleteId]);
            
            $this->reset(['confirmingDelete', 'deleteId', 'deleteJudul']);
            
            session()->flash('success', "Arsip '{$judul}' berhasil dihapus!");
            
            // Reset page jika ini item terakhir di halaman
            if (Arsip::count() % $this->perPage === 0) {
                $this->resetPage();
            }
            
        } catch (\Exception $e) {
            Log::error('Delete failed:', ['error' => $e->getMessage()]);
            session()->flash('error', 'Gagal menghapus arsip: ' . $e->getMessage());
            $this->reset(['confirmingDelete', 'deleteId', 'deleteJudul']);
        }
    }

    /**
     * Cancel delete
     */
    public function cancelDelete(): void
    {
        $this->reset(['confirmingDelete', 'deleteId', 'deleteJudul']);
    }


    /**
     * Download file arsip
     */
public function download(int $id): StreamedResponse
{
    $arsip = Arsip::findOrFail($id);
    $this->authorize('view', $arsip);

    $path = $arsip->file;

    if ($path && Storage::disk('public')->exists($path)) {
        return Storage::disk('public')->download(
            $path,
            $arsip->judul . '.' . pathinfo($path, PATHINFO_EXTENSION)
        );
    }

    abort(404, 'File tidak ditemukan');
}

   public function mount()
    {
        if (auth()->user()->role->name === 'superadmin') {
            Log::warning('Super Admin mencoba akses arsip', ['user_id' => auth()->id()]);
            abort(403, 'Maaf, Anda tidak bisa akses halaman ini');
        }
    }
    
    public function render()
    {
        // Query dasar dengan hak akses
        // Query dasar dengan hak akses
        $query = Arsip::visibleFor(auth()->user())
            ->with(['user.fakultas']);   // ← INI PENTING


        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                  ->orWhereHas('fakultas', function ($q) {
                      $q->where('nama_fakultas', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('prodi', function ($q) {
                      $q->where('nama_prodi', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply sorting
        $validSortFields = ['judul', 'created_at', 'updated_at'];
        if (in_array($this->sortField, $validSortFields)) {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            $query->latest();
        }

        // Get paginated results
        $arsips = $query->paginate($this->perPage);

        return view('livewire.arsip.index', [
            'arsips' => $arsips,
            'totalArsip' => Arsip::visibleFor(auth()->user())->count(),
        ])->layout('layouts.app');
    }
}