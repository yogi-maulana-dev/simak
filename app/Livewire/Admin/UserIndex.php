<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class UserIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    public function mount()
    {
        // Cek hanya superadmin yang bisa akses
        $user = Auth::user();
        if (!$user || $user->role->name !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function deleteUser($userId)
    {
        // Cek apakah user yang login adalah superadmin
        $currentUser = Auth::user();
        if ($currentUser->role->name !== 'superadmin') {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus user.');
            return;
        }
        
        $user = User::find($userId);
        
        if ($user) {
            // Cek apakah user yang dihapus adalah diri sendiri
            if ($user->id === $currentUser->id) {
                session()->flash('error', 'Tidak dapat menghapus akun sendiri.');
                return;
            }
            
            // Cek apakah user yang dihapus adalah superadmin
            if ($user->role->name === 'superadmin') {
                session()->flash('error', 'Tidak dapat menghapus superadmin.');
                return;
            }
            
            $user->delete();
            session()->flash('success', 'User berhasil dihapus.');
        }
    }
    
 public function render()
{
    $users = User::with(['role', 'fakultas', 'prodi'])
        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->perPage);
    
    return view('livewire.admin.user-index', [
        'users' => $users,
    ])->layout('layouts.app');
}
}