<?php

namespace App\Livewire\Arsip;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Arsip;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    public string $search = '';
    public int $perPage = 10;
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // Delete state
    public bool $confirmingDelete = false;
    public ?int $deleteId = null;
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
    public function confirmDelete(int $id): void
    {
        $arsip = Arsip::findOrFail($id);
        
        // Authorization
        $this->authorize('delete', $arsip);

        $this->deleteId = $id;
        $this->deleteJudul = $arsip->judul;
        $this->confirmingDelete = true;
    }

    /**
     * Execute delete with proper file handling
     */
    public function delete(): void
    {
        try {
            // Validasi ID
            if (!$this->deleteId) {
                throw new \Exception('ID arsip tidak valid');
            }

            $arsip = Arsip::findOrFail($this->deleteId);

            // Double authorization check
            $this->authorize('delete', $arsip);

            // Delete physical file if exists
            if ($arsip->file) {
                $filePath = str_replace('/storage/', '', $arsip->file);
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
                
                // Hapus juga thumbnail jika ada
                $thumbnailPath = str_replace('/storage/', '', $arsip->thumbnail);
                if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
                    Storage::disk('public')->delete($thumbnailPath);
                }
            }

            $arsip->delete();

            $this->reset(['confirmingDelete', 'deleteId', 'deleteJudul']);
            
            // Flash message
            session()->flash('success', 'Arsip berhasil dihapus!');
            
            // Emit event untuk refresh jika perlu
            $this->dispatch('arsip-deleted');
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            session()->flash('error', 'Arsip tidak ditemukan atau sudah dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus arsip: ' . $e->getMessage());
        }
    }

    /**
     * Cancel delete confirmation
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